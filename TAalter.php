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
    $programID = strtoupper($quartal . '-' . $activity . '-' . $departemen);

    $uploadDir = 'assets/upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Loop through each uploaded file
    foreach ($_FILES['business_technical_images']['name'] as $key => $imageName) {
        if (empty($imageName)) continue; // Skip if no file

        $tmpName = $_FILES['business_technical_images']['tmp_name'][$key];
        $targetFile = $uploadDir . basename($imageName);

        // Move uploaded file
        if (move_uploaded_file($tmpName, $targetFile)) {
            // Get corresponding description
            $gambarDeskripsi = $_POST['gambar_deskripsi'][$key] ?? '';

            // Get corresponding site ID
            $siteID = $_POST['site_id'] ?? '';

            // Insert into database
            $stmt = $dbconn->prepare("INSERT INTO program (program, departemen, quartal, siteid, gambar, deskripsi_gambar, program_title) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssss', $activity, $departemen, $quartal, $siteID, $targetFile, $gambarDeskripsi, $programID);
            
            // if ($stmt->execute()) {
            //     echo "File " . $imageName . " berhasil diunggah dan disimpan ke database.<br>";
            // } else {
            //     echo "Error: " . $stmt->error . " untuk file " . $imageName . "<br>";
            // }
            $stmt->close();
        } else {
            echo "Gagal mengunggah file " . $imageName . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Assessment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Technical Assessment Form</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="activity">Pilih Aktivitas:</label>
                <select class="form-control" name="activity" id="activity" required>
                    <option value="Relokasi Combat">Relokasi Combat</option>
                    <option value="Program1">Program 1</option>
                    <option value="Program2">Program 2</option>
                    <option value="Program3">Program 3</option>
                    <option value="Program4">Program 4</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quartal">Pilih Quartal:</label>
                <select class="form-control" name="quartal" id="quartal" required>
                    <option value="Q1">Q1</option>
                    <option value="Q2">Q2</option>
                    <option value="Q3">Q3</option>
                    <option value="Q4">Q4</option>
                </select>
            </div>
            <div class="form-group">
                <label for="departemen">Pilih Departemen:</label>
                <select class="form-control" name="departemen" id="departemen" required>
                    <option value="NOP">NOP</option>
                    <option value="NOS">NOS</option>
                </select>
            </div>
            <div class="form-group">
                <label for="site_id">Pilih Site ID:</label>
                <select class="form-control" name="site_id" id="site_id" required>
                    <?php foreach ($siteIDs as $id): ?>
                        <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($id) ?></option>
                    <?php endforeach; ?>
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
            <button type="button" id="add-image" class="btn btn-secondary mb-3">Tambah Gambar</button>
            <button type="submit" class="btn btn-primary">Submit</button>
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
    });
    </script>
</body>
</html>