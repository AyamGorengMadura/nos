<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

$siteIDs = [];

// Fetch all Site IDs from the database
$query = "SELECT SiteID FROM datasite";
$result = $dbconn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $siteIDs[] = $row['SiteID'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity = $_POST['activity'];
    $departemen = $_POST['departemen'];
    $quartal = $_POST['quartal'];
    $programID = strtoupper($quartal . '-' . $activity . '-' . $departemen);

    $uploadDir = 'assets/upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_POST['site_id'] as $index => $siteID) {
    $images = $_FILES['business_technical_images']['name'][$index];  // Ambil semua gambar
    $imageTmpNames = $_FILES['business_technical_images']['tmp_name'][$index];  // Ambil file tmp name
    $imagePaths = [];

    // Iterate through each uploaded image for the current site
    foreach ($images as $key => $imageName) {
        $targetFile = $uploadDir . basename($imageName);  // Tentukan path target

        // Pindahkan file yang di-upload ke folder target
        if (move_uploaded_file($imageTmpNames[$key], $targetFile)) {
            $imagePaths[] = $targetFile;

            // Ambil deskripsi gambar yang sesuai
            $gambarDeskripsiArray = $_POST['gambar_deskripsi'][$index];
            $gambarDeskripsi = $gambarDeskripsiArray[$key];  // Deskripsi sesuai gambar

            // Simpan gambar dan deskripsi ke database
            $stmt = $dbconn->prepare("INSERT INTO program (program, departemen, quartal, siteid, gambar, deskripsi_gambar, program_title) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssss', $activity, $departemen, $quartal, $siteID, $targetFile, $gambarDeskripsi, $programID);
            $stmt->execute();
        }
    }
}


    // Redirect after form submission to prevent form resubmission on page refresh
    echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'Form has been submitted successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        };
    </script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
    <style>
        .site-section {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
        }
        .table-form th, .table-form td {
            padding: 8px;
        }
    </style>
    <script>
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
       
    });
    </script>
    <script>
        $(document).ready(function(){
            $('#add-image').click(function(){
                var index = $('#image-wrapper .image-section').length;

                var newImageSection = `
                    <tr class="image-section">
                        <td><input type="file" class="form-control-file" name="business_technical_images[` + index + `][]" accept="image/jpeg, image/png, image/gif" multiple></td>
                        <td><input type="text" class="form-control" name="gambar_deskripsi[` + index + `][]" placeholder="Deskripsi Gambar" required></td>
                    </tr>
                `;
                $('#image-wrapper').append(newImageSection);
            });

            // SweetAlert confirmation on form submit
            $('form').on('submit', function(e) {
                e.preventDefault(); // Prevent form from submitting immediately
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="card card-transparent col-10 mx-auto mt-4 mb-4 text-start">
        <center><h1 class="mb-3" >Technical Assessment</h1></center>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="table-form">
                <tr>
                    <td for="activity" class="form-label">Pilih Aktivitas</td>
                    <td> : </td>
                    <td>
                        <select class="form-control" name="activity" id="activity">
                            <option value="Relokasi Combat">Relokasi Combat</option>
                            <option value="Program1">Program 1</option>
                            <option value="Program2">Program 2</option>
                            <option value="Program3">Program 3</option>
                            <option value="Program4">Program 4</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td for="quartal" class="form-label">Pilih Quartal</td>
                    <td> : </td>
                    <td>
                        <select class="form-control" name="quartal" id="quartal">
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td for="departemen" class="form-label">Pilih Departemen</td>
                    <td> : </td>
                    <td>
                        <select class="form-control" name="departemen" id="departemen">
                            <option value="NOP">NOP</option>
                            <option value="NOS">NOS</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td for="siteID" class="form-label">Masukkan Site ID</td>
                    <td> : </td>
                    <td>
                        <select class="form-control" name="site_id[]">
                            <?php foreach ($siteIDs as $id): ?>
                                <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table-form mt-4">
                <thead>
                    <tr>
                        <th>Upload Gambar</th>
                        <th>Deskripsi Gambar</th>
                    </tr>
                </thead>
                <tbody id="image-wrapper">
                    <tr class="image-section">
                        <td><input type="file" class="form-control-file" name="business_technical_images[0][]" accept="image/jpeg, image/png, image/gif" multiple></td>
                        <td><input type="text" class="form-control" name="gambar_deskripsi[0][]" placeholder="Deskripsi Gambar" required></td>
                    </tr>
                </tbody>
            </table>
                
            <button type="button" id="add-image" class="btn btn-secondary mb-3 mt-4">Tambah Gambar</button>
            <button type="submit" class="btn btn-primary mt-4 mb-3">Submit</button>

        </form>
    </div>
</body>
</html>
