<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

use Mpdf\Mpdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityid = $_POST['activityid'];

    // Ambil data dari database
    $query = "SELECT judul, periode, tahun FROM activities WHERE activityid = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("s", $activityid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $judul = $row['judul'];
        $periode = $row['periode'];
        $tahun = $row['tahun'];

        // Ambil data dari form input
        $latar_belakang = $_POST['latar_belakang'];
        $business_technical_assessment = $_POST['business_technical_assessment'];
        $ruang_lingkup_pekerjaan = $_POST['ruang_lingkup_pekerjaan'];
        $summary = $_POST['summary'];

        $mpdf = new Mpdf();

        // Add a page
        $mpdf->AddPage();

        // Write content
        $mpdf->WriteHTML("<h1>$judul</h1>");
        $mpdf->WriteHTML("<h2>Periode: $periode, Tahun: $tahun</h2>");

        // Latar Belakang
        $mpdf->WriteHTML("<h3>Latar Belakang</h3>");
        foreach ($latar_belakang as $index => $poin) {
            $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>"); // chr(97) == 'a'
        }

        // Business dan Technical Assessment
        $mpdf->WriteHTML("<h3>Business dan Technical Assessment</h3>");
        foreach ($business_technical_assessment as $index => $poin) {
            $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
        }

        // Upload gambar
        if (isset($_FILES['business_technical_images']) && count($_FILES['business_technical_images']['tmp_name']) > 0) {
            foreach ($_FILES['business_technical_images']['tmp_name'] as $index => $tmpName) {
                if ($_FILES['business_technical_images']['error'][$index] === UPLOAD_ERR_OK) {
                    $fileData = file_get_contents($tmpName);
                    $base64 = base64_encode($fileData);
                    $mpdf->WriteHTML('<img src="data:image/jpeg;base64,' . $base64 . '" style="width: 100%; max-width: 600px;"/>');
                }
            }
        }

        // Ruang Lingkup Pekerjaan
        $mpdf->WriteHTML("<h3>Ruang Lingkup Pekerjaan</h3>");
        foreach ($ruang_lingkup_pekerjaan as $index => $poin) {
            $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
        }

        // Summary
        $mpdf->WriteHTML("<h3>Summary</h3>");
        foreach ($summary as $index => $poin) {
            $mpdf->WriteHTML("<p>" . chr(97 + $index) . ". $poin</p>");
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
    <title>Form Input PDF</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Form Input Data PDF</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="activityid">Activity ID</label>
                <input type="text" class="form-control" id="activityid" name="activityid" required>
            </div>

            <div class="form-group" id="latar_belakang_group">
                <label for="latar_belakang">Latar Belakang</label>
                <div><input type="text" class="form-control mb-2" name="latar_belakang[]" required></div>
            </div>
            <button type="button" class="btn btn-secondary"
                onclick="addInput('latar_belakang_group', 'latar_belakang[]')">Tambah Poin Latar Belakang</button>

            <div class="form-group" id="business_technical_group">
                <label for="business_technical_assessment">Business dan Technical Assessment</label>
                <div><input type="text" class="form-control mb-2" name="business_technical_assessment[]" required></div>
            </div>
            <button type="button" class="btn btn-secondary"
                onclick="addInput('business_technical_group', 'business_technical_assessment[]')">Tambah Poin Business
                dan Technical Assessment</button>

            <div class="form-group">
                <label for="business_technical_images">Upload Images</label>
                <input type="file" class="form-control-file" id="business_technical_images"
                    name="business_technical_images[]" multiple>
            </div>

            <div class="form-group" id="ruang_lingkup_group">
                <label for="ruang_lingkup_pekerjaan">Ruang Lingkup Pekerjaan</label>
                <div><input type="text" class="form-control mb-2" name="ruang_lingkup_pekerjaan[]" required></div>
            </div>
            <button type="button" class="btn btn-secondary"
                onclick="addInput('ruang_lingkup_group', 'ruang_lingkup_pekerjaan[]')">Tambah Poin Ruang Lingkup
                Pekerjaan</button>

            <div class="form-group" id="summary_group">
                <label for="summary">Summary</label>
                <div><input type="text" class="form-control mb-2" name="summary[]" required></div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addInput('summary_group', 'summary[]')">Tambah Poin
                Summary</button>

            <button type="submit" class="btn btn-primary mt-3">Generate PDF</button>
        </form>
    </div>

    <script>
        function addInput(divName, inputName) {
            var newdiv = document.createElement('div');
            newdiv.innerHTML = "<input type='text' class='form-control mb-2' name='" + inputName + "' required>";
            document.getElementById(divName).appendChild(newdiv);
        }
    </script>
</body>

</html>