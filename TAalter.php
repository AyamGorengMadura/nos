<?php
ini_set('pcre.backtrack_limit', '10000000');
require_once __DIR__ . '/vendor/autoload.php';
require 'koneksi.php';

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$siteIDs = [];

// Fetch all Site IDs from the database
$query = "SELECT SiteID FROM datasite";
$result = $dbconn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $siteIDs[] = $row['SiteID'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity = $_POST['activity'] ?? '';
    $departemen = $_POST['departemen'] ?? '';
    $quartal = $_POST['quartal'] ?? '';
    $tahun = $_POST['tahun'] ?? '';  // Tambahkan tahun ke dalam form
    $programID = strtoupper($quartal . '-' . $activity . '-' . $departemen);

    $uploadDir = 'assets/upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Loop through each uploaded file
    foreach ($_FILES['business_technical_images']['name'] as $key => $imageName) {
        if (empty($imageName)) continue; // Skip if no file

        $tmpName = $_FILES['business_technical_images']['tmp_name'][$key];
        $targetFile = $uploadDir . basename($imageName); // Fix: Use $imageName directly

        // Move uploaded file
        if (move_uploaded_file($tmpName, $targetFile)) {
            // Get corresponding description
            $gambarDeskripsi = $_POST['gambar_deskripsi'][$key] ?? '';

            // Get corresponding site ID
            $siteID = $_POST['site_id'] ?? '';

            // Insert into database
            $stmt = $dbconn->prepare("INSERT INTO program (program, departemen, quartal, siteid, gambar, deskripsi_gambar, program_title, tahun) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $activity, $departemen, $quartal, $siteID, $targetFile, $gambarDeskripsi, $programID, $tahun);

            // Uncomment for debugging
            // if ($stmt->execute()) {
            //     echo "File " . htmlspecialchars($imageName) . " berhasil diunggah dan disimpan ke database.<br>";
            // } else {
            //     echo "Error: " . $stmt->error . " untuk file " . htmlspecialchars($imageName) . "<br>";
            // }
            $stmt->close();
        } else {
            echo "Gagal mengunggah file " . htmlspecialchars($imageName) . "<br>";
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
    <title>Technical Assessment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0; /* Warna abu-abu terang */
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .form-group label {
            width: 200px; /* Adjust the width of the label */
            margin-right: 1rem;
            flex-shrink: 0;
        }
        .form-control {
            max-width: 300px; /* Adjust the width of the dropdown */
        }
        .form-buttons {
            display: flex;
            gap: 55px; /* Menambahkan jarak antar tombol */
            margin-top: 20px; /* Adjust spacing from form fields */
        }
        .form-buttons button {
            flex: 1; /* Make buttons the same width */
            margin-right: 10px; /* Space between buttons */
            max-width: 150px; /* Set the max width of buttons */
            padding: 10px; /* Adjust padding */
            font-size: 14px; /* Adjust font size */
        }
        .form-buttons button:last-child {
            margin-right: 0; /* Remove margin from the last button */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Technical Assessment Form</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="activity">Aktivitas:</label>
                <select class="form-control" name="activity" id="activity" required>
                    <option value="Relokasi Combat">Relokasi Combat</option>
                    <option value="Program1">Program 1</option>
                    <option value="Program2">Program 2</option>
                    <option value="Program3">Program 3</option>
                    <option value="Program4">Program 4</option>
                    <option value="" disabled selected>Pilih Aktivitas</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quartal">Quartal:</label>
                <select class="form-control" name="quartal" id="quartal" required>
                    <option value="Q1">Q1</option>
                    <option value="Q2">Q2</option>
                    <option value="Q3">Q3</option>
                    <option value="Q4">Q4</option>
                    <option value="" disabled selected>Pilih Quartal</option>
                </select>
            </div>
            <div class="form-group">
                <label for="departemen">Departemen:</label>
                <select class="form-control" name="departemen" id="departemen" required>
                    <option value="NOP">NOP</option>
                    <option value="NOS">NOS</option>
                    <option value="" disabled selected>Pilih Departemen</option>
                </select>
            </div>
            <div class="form-group">
                <label for="site_id">Site ID:</label>
                <select class="form-control" name="site_id" id="site_id" required>
                    <?php foreach ($siteIDs as $id): ?>
                        <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                    <?php endforeach; ?>
                    <option value="" disabled selected>Pilih Site ID</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <select class="form-control" id="tahunDropdown" name="tahun" required>
                    <option value="" disabled selected>Pilih Tahun</option>
                </select>
            </div>
            <div id="image-container">
                <div class="form-group">
                    <label>Upload Gambar:</label>
                    <input type="file" class="form-control-file" name="business_technical_images[]" accept="image/*" multiple required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Gambar:</label>
                    <input type="text" class="form-control" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar" required>
                </div>
            </div>
            <!-- Buttons container -->
            <div class="form-buttons">
                <button type="button" id="add-image" class="btn btn-secondary mb-3">Tambah Gambar</button>
                <button type="submit" class="btn btn-primary mb-3">Submit</button>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function(){
        $('#add-image').click(function(){
            var newImageInput = `
                <div class="form-group">
                    <label>Upload Gambar:</label> 
                    <input type="file" class="form-control-file" name="business_technical_images[]" accept="image/*" multiple required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Gambar:</label>
                    <input type="text" class="form-control" name="gambar_deskripsi[]" placeholder="Deskripsi Gambar" required>
                </div>
            `;
            $('#image-container').append(newImageInput);
        });

        // Mendapatkan elemen dropdown
        const dropdown = document.getElementById('tahunDropdown');

        // Mendapatkan tahun sekarang
        const currentYear = new Date().getFullYear();

        // Mengisi dropdown dengan tahun sekarang hingga 5 tahun ke depan
        for (let i = 0; i <= 5; i++) {
            const option = document.createElement('option');
            option.value = currentYear + i;
            option.textContent = currentYear + i;
            dropdown.appendChild(option);
        }

        // SweetAlert confirmation on form submit
        $('form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            Swal.fire({
                title: 'Success!',
                text: 'Form has been submitted successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form after confirmation
                }
            });
        });
    });
    </script>
</body>
</html>
