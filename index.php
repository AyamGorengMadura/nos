<?php
include 'koneksi.php';

// Initialize variables
$judul = $periode = $tahun = $activityid = '';
$id = 0;
$update = false;

// Create operation
if (isset($_POST['create'])) {
    $judul = $_POST['judul'];
    $periode = $_POST['periode'];
    $tahun = $_POST['tahun'];
    $coe = $_POST['COE'];
    $activityid = $_POST['activityid'];

    nl2br(htmlspecialchars($coe));

    $stmt = $dbconn->prepare("INSERT INTO activities (judul, periode, tahun, coe, activityid) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($dbconn->error));
    }

    // Corrected bind_param call
    $stmt->bind_param("ssisi", $judul, $periode, $tahun, $coe, $activityid);
    if ($stmt->execute() === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    $stmt->close();
}

// Read operation
$results = $dbconn->query("SELECT * FROM activities");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .sidebar {
            height: 100vh;
        }

        .dropdown-menu {
            position: static;
            display: block;
            visibility: hidden;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, visibility 0.3s ease;
        }

        .dropdown-menu.show {
            visibility: visible;
            max-height: 200px;
            /* Sesuaikan dengan tinggi konten dropdown */
        }
    </style>
</head>

<body>

    <div class="outercontainer">

        <!-- sidebar start -->
        <div class="col-2 row-12 card bg-dark fixed-top text-light sidebar">
            <div class="mx-auto mt-4 col-10">
                <p class="">Lorem, ipsum.</p>
                <ul class="nav nav-tabs mb-3 text-decoration-none col-10">
                    <li class="nav-item">
                        <a class="nav-link text-light mt-2 fs-4" href="#"><i class="bi bi-inbox"></i> Dashboard </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light mt-3 mb-2 fs-5" data-bs-toggle="modal"
                            data-bs-target="#modaljudul"><i class="bi fs-4 bi-file-plus-fill"></i> Judul Activity </a>
                    </li>

                    <li class="">
                        <div class="dropdown ms-2">
                            <button class="mt-3 btn btn-outline-light dropdown-toggle fs-5" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">Dokumen</button>
                            <div class="dropdown-menu rounded mt-3 bg-dark" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item fs-4 text-light rounded" id="" href="TA.php"><i
                                        class="bi bi-send-fill"></i> TA </a>
                                <a class="dropdown-item fs-4 text-light rounded" id="" href="RAB.php"><i
                                        class="bi bi-send-fill"></i> RAB </a>
                                <a class="dropdown-item fs-4 text-light rounded" id="" href="JTG-JUKB.php"><i
                                        class="bi bi-send-fill"></i> JUKB </a>
                            </div>
                        </div>
                    </li>
                    <li class="">
                        <div class="dropdown ms-2">
                            <button class="mt-3 btn btn-outline-light dropdown-toggle fs-5" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">TA</button>
                            <div class="dropdown-menu rounded mt-3 bg-dark" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item fs-4 text-light rounded" id="" href="TAalter.php"><i
                                        class="bi bi-send-fill"></i> TA </a>
                                <a class="dropdown-item fs-4 text-light rounded" id="" href="TApdf.php"><i
                                        class="bi bi-send-fill"></i> BUAT PDF</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light mt-3 fs-4" href="TA.php"><i class="bi bi-send-fill"></i> TA </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light mt-3 fs-4" href="RAB.php"><i class="bi bi-send-fill"></i> RAB </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light mt-3 fs-4" href="JTG-JUKB.php"><i class="bi bi-send-fill"></i> JUKB </a>
                    </li>

                </ul>
            </div>
        </div>
        <!-- sidebar end -->

        <!-- Modal start -->
<div class="modal fade" id="modaljudul" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <!-- Modal body -->
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-light" id="exampleModalLabel">Judul Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="activityForm" method="POST">
                    <div class="mb-3">
                        <label for="judul" class="text-light form-label">Judul Activity</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3 text-light">
                        <label for="periode" class="form-label">Periode</label>
                        <select class="form-select " id="periode" name="periode" required>
                            <option value="">Select Period</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tahun" class="text-light form-label">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="mb-3">
                        <label for="activityid" class="text-light form-label">No Activity</label>
                        <input type="text" class="form-control" id="activityid" name="activityid" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary mt-2" name="create">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- modal body end -->
    </div>
</div>

<!-- modal end -->

        <script>
            document.querySelectorAll('.dropdown-toggle').forEach(function (dropdownToggle) {
                dropdownToggle.addEventListener('click', function () {
                    var dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('show');
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</body>

</html>