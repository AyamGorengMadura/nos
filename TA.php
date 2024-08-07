<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityid = $_POST['activityid'];

    // Ambil data db
    $query = "SELECT judul, periode, tahun, coe FROM activities WHERE activityid = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("s", $activityid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $coe = $row['coe'];
        $judul = $row['judul'];
        $periode = $row['periode'];
        $tahun = $row['tahun'];

        // Ambil input bab
        $chapters = $_POST['chapter_name'];
        $sections = [];

        foreach ($chapters as $rabIndex => $chapterNames) {
            foreach ($chapterNames as $chapterCount => $chapterName) {
                $section = [
                    'name' => htmlspecialchars($chapterName),
                    'points' => isset($_POST['latar_belakang'][$rabIndex][$chapterCount]) ? $_POST['latar_belakang'][$rabIndex][$chapterCount] : [],
                    'images' => isset($_FILES['business_technical_images']) ? $_FILES['business_technical_images'] : [],
                    'image_descriptions' => isset($_POST['gambar_deskripsi']) ? $_POST['gambar_deskripsi'] : []
                ];
                $sections[] = $section;
            }
        }

        $mpdf = new Mpdf();

        // Cover start
        $mpdf->WriteHTML("<h1 style='text-align: center;'>TECHNICAL ASSESMENT</h1><br>");
        $mpdf->WriteHTML("<h2 style='text-align: center;'>$judul</h2>");
        $mpdf->WriteHTML("<h2 style='text-align: center;'>Periode: $periode, Tahun: $tahun</h2>");

        // Ubah $coe menjadi vertikal
        $coe_array = explode(',', $coe); // Misalnya $coe dipisahkan oleh koma
        $coe_vertical = implode('<br>', $coe_array);
        $mpdf->WriteHTML("<p style='text-align: center;'>$coe_vertical</p>");
        // Cover end

        // Tambahkan header
        $mpdf->SetHTMLHeader("<div style='text-align: center;'>
            <p>$judul, Periode: $periode, Tahun: $tahun<p>
        </div>");

        // Loop melalui setiap section
        foreach ($sections as $index => $section) {
            $sectionName = htmlspecialchars($section['name']);
            $mpdf->AddPage();
            $mpdf->WriteHTML("<h3>$sectionName</h3>");

            // Tambahkan poin
            if (isset($section['points']) && is_array($section['points'])) {
                foreach ($section['points'] as $pointIndex => $point) {
                    $mpdf->WriteHTML("<p>" . chr(97 + $pointIndex) . ". $point</p>"); // chr(97) == 'a'
                }
            }

            // Loop through each image for this chapter
            if (isset($_FILES['business_technical_images']) && is_array($_FILES['business_technical_images']['tmp_name'])) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                foreach ($_FILES['business_technical_images']['tmp_name'] as $imgIndex => $tmpName) {
                    // Check for upload errors
                    if ($_FILES['business_technical_images']['error'][$imgIndex] === UPLOAD_ERR_OK) {
                        $file_type = mime_content_type($tmpName);
                        $fileData = file_get_contents($tmpName);

                        // Validate and convert image if necessary
                        if (!in_array($file_type, $allowed_types)) {
                            $image = imagecreatefromstring($fileData);
                            ob_start();
                            imagepng($image);
                            $fileData = ob_get_contents();
                            ob_end_clean();
                            $file_type = 'image/png';
                            imagedestroy($image);
                        }

                        // Prepare base64 image
                        $base64 = base64_encode($fileData);
                        $deskripsi = htmlspecialchars($section['image_descriptions'][$imgIndex] ?? ""); // Use empty if not set
                        $mpdf->WriteHTML('<div style="text-align:center;"><img src="data:' . $file_type . ';base64,' . $base64 . '" style="width: 100%; max-width: 200px;"/><p>' . $deskripsi . '</p></div>');
                    }
                }
            }
        }

        // Output a PDF file
        $mpdf->Output('technical_assessment.pdf', \Mpdf\Output\Destination::INLINE);
    } else {
        echo "Activity ID tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="card text-white bg-secondary mt-4 mb-4 mx-auto col-10">
        <div class="container mt-5">
            <h2>TA</h2>
            <h6>Technical Assessment</h6>
            <form action="" method="post" class="mt-3" enctype="multipart/form-data">
                <a class="btn btn-primary" href="index.php" role="button">Kembali</a>

                <div class="form-group">
                    <label for="activityid">Activity ID</label>
                    <input type="text" class="form-control" id="activityid" name="activityid" required>
                </div>

                <div id="rab_container">
                    <h3>RAB Sections</h3>
                    <div class="rab-section" id="rab_1">
                        <h4>RAB 1</h4>
                        <button type="button" class="btn btn-success mb-2" onclick="addChapter(1)">Tambah Bab</button>
                        <div class="chapter_container" id="chapter_container_1"></div>
                    </div>
                </div>

                <button type="button" class="btn btn-success mt-3 mb-4" id="add_rab_btn">Tambah RAB</button>

                <button type="submit" class="btn btn-primary mt-3 mb-4">Submit</button>
            </form>
        </div>
    </div>

    <script>
        let rabCount = 1;

        // Function to add a new RAB section
        document.getElementById('add_rab_btn').addEventListener('click', function () {
            rabCount++;
            const rabContainer = document.getElementById('rab_container');
            const rabSection = document.createElement('div');
            rabSection.className = 'rab-section mt-4';
            rabSection.id = 'rab_' + rabCount;
            rabSection.innerHTML = `
                <h4>RAB ${rabCount}</h4>
                <button type="button" class="btn btn-success mb-2" onclick="addChapter(${rabCount})">Tambah Bab</button>
                <div class="chapter_container" id="chapter_container_${rabCount}"></div>
            `;
            rabContainer.appendChild(rabSection);
        });

        // Function to add a new Chapter within a specific RAB section
        function addChapter(rabIndex) {
            const chapterContainer = document.getElementById(`chapter_container_${rabIndex}`);
            const chapterCount = chapterContainer.children.length + 1; // Count existing chapters

            const chapterSection = document.createElement('div');
            chapterSection.className = 'chapter-section mt-3';
            chapterSection.id = `chapter_${rabIndex}_${chapterCount}`;

            chapterSection.innerHTML = `
                <h5>Bab ${chapterCount}</h5>
                <div class="form-group">
                    <label for="chapter_name_${rabIndex}_${chapterCount}">Nama Bab</label>
                    <input type="text" class="form-control mb-2" name="chapter_name[${rabIndex}][${chapterCount}]" required>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('points_container_${rabIndex}_${chapterCount}', 'latar_belakang[${rabIndex}][${chapterCount}][]')">Tambah Poin</button>
                <div class="points_container" id="points_container_${rabIndex}_${chapterCount}"></div>
                <button type="button" class="btn btn-primary mt-2" onclick="addImageInput('image_container_${rabIndex}_${chapterCount}')">Tambah Gambar</button>
                <div class="image_container" id="image_container_${rabIndex}_${chapterCount}"></div>
            `;

            chapterContainer.appendChild(chapterSection);
        }

        function addInput(groupId, inputName) {
            const group = document.getElementById(groupId);
            const input = document.createElement('div');
            input.innerHTML = '<input type="text" class="form-control mb-2" name="' + inputName + '" required>';
            group.appendChild(input);
        }

        function addImageInput(groupId) {
            const group = document.getElementById(groupId);
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = '<input type="file" class="form-control-file" name="business_technical_images[]" multiple><input type="text" class="form-control mt-2" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar">';
            group.appendChild(div);
        }
    </script>
</body>

</html> 