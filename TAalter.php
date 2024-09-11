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

    $uploadDir = 'assets/upload';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_POST['site_id'] as $index => $siteID) {
        // Combine multiple descriptions into a string
        $deskripsiArray = $_POST['gambar_deskripsi'][$index]; 
        $gambarDeskripsi = implode(',', $deskripsiArray); // Concatenate descriptions

        $images = $_FILES['business_technical_images']['name'][$index];
        $imagePaths = [];
        foreach ($images as $key => $imageName) {
            $targetFile = $uploadDir . basename($imageName);
            if (move_uploaded_file($_FILES['business_technical_images']['tmp_name'][$index][$key], $targetFile)) {
                $imagePaths[] = $targetFile;
            }
        }

        $imagePathsString = implode(',', $imagePaths);

        // Save data to the database
        $stmt = $dbconn->prepare("INSERT INTO program (program, departemen, quartal, siteid, gambar, deskripsi_gambar, program_title) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $activity, $departemen, $quartal, $siteID, $imagePathsString, $gambarDeskripsi, $programID);
        $stmt->execute();
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
    </style>
    <script>
        $(document).ready(function(){
            $('#add-image').click(function(){
                var index = $('#image-wrapper .image-section').length;

                var newImageSection = `
                    <div class="image-section mt-3">
                        <label for="business_technical_images" class="form-label">Upload Images</label>
                        <input type="file" class="form-control-file" name="business_technical_images[` + index + `][]" accept="image/jpeg, image/png, image/gif" multiple>

                        <label for="gambar_deskripsi" class="form-label mt-2">Deskripsi Gambar</label>
                        <input type="text" class="form-control" name="gambar_deskripsi[` + index + `][]" placeholder="Deskripsi Gambar" required>
                    </div>
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
        <center><h1>Technical Assessment</h1></center>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3 mt-3">
                <label for="activity" class="form-label">Pilih Aktivitas</label>
                <select class="form-control" name="activity" id="activity">
                    <option value="Relokasi Combat">Relokasi Combat</option>
                    <option value="Program1">Program 1</option>
                    <option value="Program2">Program 2</option>
                    <option value="Program3">Program 3</option>
                    <option value="Program4">Program 4</option>
                </select>
            </div>

            <div class="mb-3 mt-3">
                <label for="quartal" class="form-label">Pilih Quartal</label>
                <select class="form-control" name="quartal" id="quartal">
                    <option value="Q1">Q1</option>
                    <option value="Q2">Q2</option>
                    <option value="Q3">Q3</option>
                    <option value="Q4">Q4</option>
                </select>
            </div>

            <div class="mb-3 mt-3">
                <label for="departemen" class="form-label">Pilih Departemen</label>
                <select class="form-control" name="departemen" id="departemen">
                    <option value="NOP">NOP</option>
                    <option value="NOS">NOS</option>
                </select>
            </div>

            <div>
                <div class="mb-3">
                    <label for="siteID" class="form-label">Masukkan Site ID</label>
                    <select class="form-control" name="site_id[]">
                        <?php foreach ($siteIDs as $id): ?>
                            <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="image-section" id="image-wrapper">
                        <label for="business_technical_images" class="form-label mt-2">Upload Images</label>
                        <input type="file" class="form-control-file" name="business_technical_images[0][]" accept="image/jpeg, image/png, image/gif" multiple>

                        <label for="gambar_deskripsi" class="form-label mt-2">Deskripsi Gambar</label>
                        <input type="text" class="form-control" name="gambar_deskripsi[0][]" placeholder="Deskripsi Gambar" required>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <button type="button" id="add-image" class="btn btn-secondary mt-4">Tambah Gambar</button>
                <button type="submit" class="btn btn-primary mt-4">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
