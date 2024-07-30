<?php

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/vendor/autoload.php';

    $mpdf = new \Mpdf\Mpdf();

    $title = $_POST['title'] ?? '';
    $backgrounds = $_POST['backgrounds'] ?? [];
    $assessments = $_POST['assessments'] ?? [];
    $scopes = $_POST['scopes'] ?? [];
    $summary = $_POST['summary'] ?? '';

    $html = "<h1>$title</h1>";

    $html .= "<h2>Latar Belakang</h2>";
    foreach ($backgrounds as $background) {
        $html .= "<p>$background</p>";
    }

    $html .= "<h2>Business dan Technical Assessment</h2>";
    foreach ($assessments as $assessment) {
        $poin = $assessment['poin'] ?? '';
        $rincian = $assessment['rincian'] ?? '';
        $html .= "<p><strong>Poin:</strong> $poin</p>";
        $html .= "<p><strong>Rincian:</strong> $rincian</p>";
    }

    $html .= "<h2>Ruang Lingkup Pekerjaan</h2>";
    foreach ($scopes as $scope) {
        $html .= "<p>$scope</p>";
    }

    $html .= "<h2>Summary</h2>";
    $html .= "<p>$summary</p>";

    $mpdf->WriteHTML($html);

    $mpdf->Output();

    echo "<script>window.open('$pdfFilename', '_blank');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Assessment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        function addBackground() {
            const container = document.getElementById('backgroundContainer');
            const div = document.createElement('div');
            div.classList.add('mb-2');
            div.innerHTML = `
                <label for="lbelakang">Rincian A</label>
                <textarea name="backgrounds[]" class="form-control mt-2" required></textarea>
            `;
            container.appendChild(div);
        }

        function addAssessment() {
            const container = document.getElementById('assessmentContainer');
            const div = document.createElement('div');
            div.classList.add('mb-2');
            div.innerHTML = `
                <label>Poin:</label>
                <input type="text" name="assessments[][poin]" class="form-control mt-2" required>
                <label>Rincian:</label>
                <textarea name="assessments[][rincian]" class="form-control mt-2" required></textarea>
            `;
            container.appendChild(div);
        }

        function addScope() {
            const container = document.getElementById('scopeContainer');
            const div = document.createElement('div');
            div.classList.add('mb-2');
            div.innerHTML = `
                <label for="rlp">Rincian A</label>
                <textarea name="scopes[]" class="form-control mt-2" required></textarea>
            `;
            container.appendChild(div);
        }
    </script>
    <style>
        body {
            background-image: url(https://th.bing.com/th/id/OIP.ntr3O0LR4AzkKQEcbrWkAAHaEo?rs=1&pid=ImgDetMain);
        }
    </style>
</head>

<body>
    <div class="card text-white bg-secondary col-8 mt-3 mx-auto">
        <div class="card-body col-10 mx-auto">
            <h1>Technical Assessment Form</h1>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Kembali</a>

            <form action="" method="post">
                <div class="">
                    <label for="title" class="">Title:</label>
                    <br>
                    <input class="mt-2" type="text" id="title" name="title" required>
                </div>

                <h3 class="mt-3">Latar Belakang</h3>
                <div id="backgroundContainer">
                    <div class="mb-2">
                        <label for="lbelakang">Rincian A</label>
                        <textarea name="backgrounds[]" class="form-control mt-2" required></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-light mt-2" onclick="addBackground()">Tambah Rincian</button>

                <h3 class="mt-3">Business dan Technical Assessment</h3>
                <div id="assessmentContainer">
                    <div class="mb-2">
                        <label>Poin:</label>
                        <input type="text" name="assessments[][poin]" class="form-control mt-2" required>
                        <label>Rincian:</label>
                        <textarea name="assessments[][rincian]" class="form-control mt-2" required></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-light mt-2" onclick="addAssessment()">Tambah Poin</button>

                <h3 class="mt-3">Ruang Lingkup Pekerjaan</h3>
                <div id="scopeContainer">
                    <div class="mb-2">
                        <label for="rlp">Rincian A</label>
                        <textarea name="scopes[]" class="form-control mt-2" required></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-light mt-2" onclick="addScope()">Tambah Rincian</button>

                <div class="form-group mt-3">
                    <label for="summary">Summary:</label>
                    <br>
                    <textarea class="mt-2" id="summary" name="summary" cols="50" required></textarea>
                </div>

                <input class="btn btn-primary mt-3" type="submit" value="Generate PDF">
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>