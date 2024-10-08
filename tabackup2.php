<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityid = $_POST['activityid'];

    // Ambil data db
    $query = "SELECT judul, periode, tahun FROM activities WHERE activityid = ?";
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
                    'image_descriptions' => isset($_POST['gambar_deskripsi']) ? $_POST['gambar_deskripsi'] : [],
                    'subchapters' => isset($_POST['subchapter_name'][$rabIndex][$chapterCount]) ? $_POST['subchapter_name'][$rabIndex][$chapterCount] : []
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
        $coe_array = explode(',', $coe);
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
            $mpdf->WriteHTML("<h3>" . ($index + 1) . ". $sectionName</h3>");

            // Tambahkan poin
            if (isset($section['points']) && is_array($section['points'])) {
                foreach ($section['points'] as $pointIndex => $point) {
                    $mpdf->WriteHTML("<p>" . chr(97 + $pointIndex) . ". $point</p>");
                }
            }

            // Tambahkan subchapters
            if (isset($section['subchapters']) && is_array($section['subchapters'])) {
                foreach ($section['subchapters'] as $subChapterIndex => $subChapterName) {
                    $mpdf->WriteHTML("<h4>" . chr(97 + $subChapterIndex) . ". " . htmlspecialchars($subChapterName) . "</h4>");
                    if (isset($_POST['sub_latar_belakang'][$rabIndex][$chapterCount][$subChapterIndex]) && is_array($_POST['sub_latar_belakang'][$rabIndex][$chapterCount][$subChapterIndex])) {
                        foreach ($_POST['sub_latar_belakang'][$rabIndex][$chapterCount][$subChapterIndex] as $subPointIndex => $subPoint) {
                            $mpdf->WriteHTML("<p>" . chr(97 + $subPointIndex) . ". $subPoint</p>");
                        }
                    }
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
                        $deskripsi = htmlspecialchars($section['image_descriptions'][$imgIndex] ?? "");
                        $mpdf->WriteHTML('<div style="text-align:center;"><img src="data:' . $file_type . ';base64,' . $base64 . '" style="width: 100%; max-height: 500px; max-width: 600px;"/><p>' . $deskripsi . '</p></div>');
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
            <form action="" method="post" class="mt-3" enctype="multipart/form-data" id="mainForm">
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

        document.addEventListener('DOMContentLoaded', function () {
            loadFromLocalStorage();
        });

        document.getElementById('mainForm').addEventListener('input', function () {
            saveToLocalStorage();
        });

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

        function addChapter(rabIndex) {
            const chapterContainer = document.getElementById(`chapter_container_${rabIndex}`);
            const chapterCount = chapterContainer.children.length + 1;

            const chapterSection = document.createElement('div');
            chapterSection.className = 'chapter-section mt-3';
            chapterSection.id = `chapter_${rabIndex}_${chapterCount}`;

            chapterSection.innerHTML = `
                <h5>${chapterCount}. <input type="text" class="form-control d-inline-block w-75" name="chapter_name[${rabIndex}][${chapterCount}]" required></h5>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" onclick="removeElement(this.parentNode.parentNode)">Hapus Bab</button>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('points_container_${rabIndex}_${chapterCount}', 'latar_belakang[${rabIndex}][${chapterCount}][]')">Tambah Poin</button>
                <div class="points_container" id="points_container_${rabIndex}_${chapterCount}"></div>
                <button type="button" class="btn btn-primary mt-2" onclick="addImageInput('image_container_${rabIndex}_${chapterCount}')">Tambah Gambar</button>
                <div class="image_container" id="image_container_${rabIndex}_${chapterCount}"></div>
                <button type="button" class="btn btn-primary mt-2" onclick="addSubChapter(${rabIndex}, ${chapterCount})">Tambah Sub Bab</button>
                <div class="subchapter_container" id="subchapter_container_${rabIndex}_${chapterCount}"></div>
            `;
            chapterContainer.appendChild(chapterSection);
        }

        function addInput(groupId, inputName) {
            const group = document.getElementById(groupId);
            const input = document.createElement('div');
            input.className = 'input-group mb-2';
            input.innerHTML = `
                <input type="text" class="form-control" name="${inputName}" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger" onclick="removeElement(this.parentNode.parentNode)">Hapus</button>
                </div>`;
            group.appendChild(input);
        }

        function addImageInput(groupId) {
            const group = document.getElementById(groupId);
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `
                <input type="file" class="form-control-file" name="business_technical_images[]" multiple>
                <input type="text" class="form-control mt-2" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar">
                <button type="button" class="btn btn-danger mt-2" onclick="removeElement(this.parentNode)">Hapus</button>`;
            group.appendChild(div);
        }

        function addSubChapter(rabIndex, chapterCount) {
            const subChapterContainer = document.getElementById(`subchapter_container_${rabIndex}_${chapterCount}`);
            const subChapterCount = subChapterContainer.children.length + 1;

            const subChapterSection = document.createElement('div');
            subChapterSection.className = 'subchapter-section mt-3';
            subChapterSection.id = `subchapter_${rabIndex}_${chapterCount}_${subChapterCount}`;

            subChapterSection.innerHTML = `
                <h6>${String.fromCharCode(96 + subChapterCount)}. <input type="text" class="form-control d-inline-block w-75" name="subchapter_name[${rabIndex}][${chapterCount}][${subChapterCount}]" required></h6>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" onclick="removeElement(this.parentNode.parentNode)">Hapus Sub Bab</button>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('subpoints_container_${rabIndex}_${chapterCount}_${subChapterCount}', 'sub_latar_belakang[${rabIndex}][${chapterCount}][${subChapterCount}][]')">Tambah Poin</button>
                <div class="points_container" id="subpoints_container_${rabIndex}_${chapterCount}_${subChapterCount}"></div>
                <button type="button" class="btn btn-primary mt-2" onclick="addImageInput('subimage_container_${rabIndex}_${chapterCount}_${subChapterCount}')">Tambah Gambar</button>
                <div class="image_container" id="subimage_container_${rabIndex}_${chapterCount}_${subChapterCount}"></div>
            `;

            subChapterContainer.appendChild(subChapterSection);
        }

        function removeElement(element) {
            element.parentNode.removeChild(element);
            saveToLocalStorage();
        }

        function saveToLocalStorage() {
            const form = document.getElementById('mainForm');
            const formData = new FormData(form);
            const formDataJSON = {};

            formData.forEach((value, key) => {
                if (!formDataJSON[key]) {
                    formDataJSON[key] = [];
                }
                formDataJSON[key].push(value);
            });

            localStorage.setItem('formData', JSON.stringify(formDataJSON));
        }

        function loadFromLocalStorage() {
            const formDataJSON = localStorage.getItem('formData');
            if (formDataJSON) {
                const formData = JSON.parse(formDataJSON);
                for (const key in formData) {
                    const values = formData[key];
                    const inputs = document.getElementsByName(key);
                    inputs.forEach((input, index) => {
                        if (input.type === 'file') {
                            return;
                        }
                        input.value = values[index];
                    });
                }
            }
        }
    </script>
</body>

</html>