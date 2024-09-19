<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityid = $_POST['activityid'];

    // Ambil data db
    $query = "SELECT judul, periode, tahun  FROM activities WHERE activityid = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("s", $activityid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // $coe = $row['coe'];
        $judul = $row['judul'];
        $periode = $row['periode'];
        $tahun = $row['tahun'];

        // Ambil input
        $latar_belakang = isset($_POST['latar_belakang']) ? $_POST['latar_belakang'] : [];
        $business_technical_assessment = isset($_POST['business_technical_assessment']) ? $_POST['business_technical_assessment'] : [];
        $ruang_lingkup_pekerjaan = isset($_POST['ruang_lingkup_pekerjaan']) ? $_POST['ruang_lingkup_pekerjaan'] : [];
        $summary = isset($_POST['summary']) ? $_POST['summary'] : [];
        $gambar_deskripsi = isset($_POST['gambar_deskripsi']) ? $_POST['gambar_deskripsi'] : [];

        $mpdf = new Mpdf();

        // cover start
        $mpdf->WriteHTML("<h1 style='text-align: center;'>TECHNICAL ASSESMENT</h1><br>");
        $mpdf->WriteHTML("<h2 style='text-align: center;'>$judul</h2>");
        $mpdf->WriteHTML("<h2 style='text-align: center;'>Periode: $periode, Tahun: $tahun</h2>");

        // Ubah $coe menjadi vertikal
        $coe_array = explode(',', $coe); // misalnya $coe dipisahkan oleh koma
        $coe_vertical = implode('<br>', $coe_array);
        $mpdf->WriteHTML("<p style='text-align: center;'>$coe_vertical</p>");
        // cover end

        $mpdf->AddPage();

        // Tambahkan header
        $mpdf->SetHTMLHeader("<div style='text-align: center;'>
            <p>$judul, Periode: $periode, Tahun: $tahun<p>
        </div>");

        // Loop melalui setiap RAB section
        $sections = count($latar_belakang); // Asumsi semua input array memiliki panjang yang sama
        for ($i = 0; $i < $sections; $i++) {
            // Tambahkan halaman baru untuk setiap section RAB
            if ($i > 0) {
                $mpdf->AddPage();
            }

            // Latar Belakang
            $mpdf->WriteHTML("<h3>Latar Belakang</h3>
                                <p> Latar belakang dilaksanakannya pekerjaan ini adalah sebagai berikut; </p>");
            if (is_array($latar_belakang[$i])) {
                foreach ($latar_belakang[$i] as $index => $poin) {
                    $mpdf->WriteHTML("<p class='konten'>" . chr(97 + $index) . ". $poin</p>"); // chr(97) == 'a'
                }
            }

            // Business dan Technical Assessment
            $mpdf->WriteHTML("<h3>Business dan Technical Assessment</h3>");
            if (is_array($business_technical_assessment[$i])) {
                foreach ($business_technical_assessment[$i] as $index => $poin) {
                    $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
                }
            }

            // Upload gambar dengan deskripsi
            if (isset($_FILES['business_technical_images']) && is_array($_FILES['business_technical_images']['tmp_name']) && count($_FILES['business_technical_images']['tmp_name']) > 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                foreach ($_FILES['business_technical_images']['tmp_name'] as $index => $tmpName) {
                    if ($_FILES['business_technical_images']['error'][$index] === UPLOAD_ERR_OK) {
                        $file_type = mime_content_type($tmpName);
                        $fileData = file_get_contents($tmpName);

                        // Konversi gambar jika format tidak didukung
                        if (!in_array($file_type, $allowed_types)) {
                            $image = imagecreatefromstring($fileData);
                            ob_start();
                            imagepng($image);
                            $fileData = ob_get_contents();
                            ob_end_clean();
                            $file_type = 'image/png';
                            imagedestroy($image);
                        }

                        $base64 = base64_encode($fileData);
                        $deskripsi = htmlspecialchars($gambar_deskripsi[$index]);
                        $mpdf->WriteHTML('<div style="text-align:center;"><img src="data:' . $file_type . ';base64,' . $base64 . '" style="width: 100%; max-width: 600px;"/><p>' . $deskripsi . '</p></div>');
                        break; // Hentikan setelah satu gambar ditambahkan
                    }
                }
            }

            // Ruang Lingkup Pekerjaan
            $mpdf->WriteHTML("<h3>Ruang Lingkup Pekerjaan</h3>");
            if (is_array($ruang_lingkup_pekerjaan[$i])) {
                foreach ($ruang_lingkup_pekerjaan[$i] as $index => $poin) {
                    $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
                }
            }

            // Summary
            $mpdf->WriteHTML("<h3>Summary</h3>");
            if (is_array($summary[$i])) {
                foreach ($summary[$i] as $index => $poin) {
                    $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
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
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Kembali</a>
                <div class="form-group">
                    <label for="activityid">Activity ID</label>
                    <input type="text" class="form-control" id="activityid" name="activityid" required>
                </div>

                <div id="rab_container">
                    <div class="rab-section" id="rab_1">
                        <h4>RAB 1</h4>
                        <div class="form-group" id="latar_belakang_group_1">
                            <label for="latar_belakang_1">Latar Belakang</label>
                            <div><input type="text" class="form-control mb-2" name="latar_belakang[0][]" required></div>
                        </div>
                        <button type="button" class="btn btn-primary"
                            onclick="addInput('latar_belakang_group_1', 'latar_belakang[0][]')">Tambah Poin Latar
                            Belakang</button>

                        <div class="form-group" id="business_technical_group_1">
                            <label for="business_technical_assessment_1">Business dan Technical Assessment</label>
                            <div><input type="text" class="form-control mb-2" name="business_technical_assessment[0][]"
                                    required></div>
                        </div>
                        <button type="button" class="btn btn-primary"
                            onclick="addInput('business_technical_group_1', 'business_technical_assessment[0][]')">Tambah
                            Poin Business dan Technical Assessment</button>

                        <div class="form-group" id="gambar_group_1">
                            <label for="business_technical_images_1">Upload Images</label>
                            <div class="mb-2">
                                <input type="file" class="form-control-file" name="business_technical_images[]"
                                    multiple>
                                <input type="text" class="form-control mt-2" name="gambar_deskripsi[]"
                                    placeholder="Deskripsi Gambar">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addImageInput('gambar_group_1')">Tambah
                            Gambar</button>

                        <div class="form-group" id="ruang_lingkup_group_1">
                            <label for="ruang_lingkup_pekerjaan_1">Ruang Lingkup Pekerjaan</label>
                            <div><input type="text" class="form-control mb-2" name="ruang_lingkup_pekerjaan[0][]"
                                    required></div>
                        </div>
                        <button type="button" class="btn btn-primary"
                            onclick="addInput('ruang_lingkup_group_1', 'ruang_lingkup_pekerjaan[0][]')">Tambah Poin
                            Ruang Lingkup Pekerjaan</button>

                        <div class="form-group" id="summary_group_1">
                            <label for="summary_1">Summary</label>
                            <div><input type="text" class="form-control mb-2" name="summary[0][]" required></div>
                        </div>
                        <button type="button" class="btn btn-primary"
                            onclick="addInput('summary_group_1', 'summary[0][]')">Tambah Poin Summary</button>
                    </div>
                </div>

                <button type="button" class="btn btn-success mt-3 mb-4" id="add_rab_btn">Tambah RAB</button>

                <button type="submit" class="btn btn-primary mt-3 mb-4">Submit</button>
            </form>
        </div>
    </div>

    <script>
        let rabCount = 1;

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

        document.getElementById('add_rab_btn').addEventListener('click', function () {
            rabCount++;
            const rabContainer = document.getElementById('rab_container');
            const rabSection = document.createElement('div');
            rabSection.className = 'rab-section mt-4';
            rabSection.id = 'rab_' + rabCount;
            rabSection.innerHTML = `
                <h4>RAB ${rabCount}</h4>
                <div class="form-group" id="latar_belakang_group_${rabCount}">
                    <label for="latar_belakang_${rabCount}">Latar Belakang</label>
                    <div><input type="text" class="form-control mb-2" name="latar_belakang[${rabCount - 1}][]" required></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('latar_belakang_group_${rabCount}', 'latar_belakang[${rabCount - 1}][]')">Tambah Poin Latar Belakang</button>

                <div class="form-group" id="business_technical_group_${rabCount}">
                    <label for="business_technical_assessment_${rabCount}">Business dan Technical Assessment</label>
                    <div><input type="text" class="form-control mb-2" name="business_technical_assessment[${rabCount - 1}][]" required></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('business_technical_group_${rabCount}', 'business_technical_assessment[${rabCount - 1}][]')">Tambah Poin Business dan Technical Assessment</button>

                <div class="form-group" id="gambar_group_${rabCount}">
                    <label for="business_technical_images_${rabCount}">Upload Images</label>
                    <div class="mb-2">
                        <input type="file" class="form-control-file" name="business_technical_images[]" multiple>
                        <input type="text" class="form-control mt-2" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar">
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addImageInput('gambar_group_${rabCount}')">Tambah Gambar</button>

                <div class="form-group" id="ruang_lingkup_group_${rabCount}">
                    <label for="ruang_lingkup_pekerjaan_${rabCount}">Ruang Lingkup Pekerjaan</label>
                    <div><input type="text" class="form-control mb-2" name="ruang_lingkup_pekerjaan[${rabCount - 1}][]" required></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('ruang_lingkup_group_${rabCount}', 'ruang_lingkup_pekerjaan[${rabCount - 1}][]')">Tambah Poin Ruang Lingkup Pekerjaan</button>

                <div class="form-group" id="summary_group_${rabCount}">
                    <label for="summary_${rabCount}">Summary</label>
                    <div><input type="text" class="form-control mb-2" name="summary[${rabCount - 1}][]" required></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="addInput('summary_group_${rabCount}', 'summary[${rabCount - 1}][]')">Tambah Poin Summary</button>
            `;
            rabContainer.appendChild(rabSection);
        });
    </script>
</body>

</html>