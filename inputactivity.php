<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $periode = $_POST['periode'];
    $tahun = $_POST['tahun'];
    $activityid = $_POST['activityid'];
    $siteid = $_POST['siteid'];

    // Validasi apakah activityid sudah dipakai
    $query = "SELECT activityid FROM activities WHERE activityid = ?";
    $stmt = $dbconn->prepare($query);
    $stmt->bind_param("i", $activityid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika activityid sudah dipakai, cari ID yang belum digunakan
        $suggested_id = $activityid;
        do {
            $suggested_id++;
            $stmt->bind_param("i", $suggested_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } while ($result->num_rows > 0);

        // Tampilkan pesan kesalahan dan saran ID yang bisa digunakan
        echo "<script>
            alert('Activity ID $activityid sudah dipakai. Silakan gunakan ID yang bisa dipakai, misalnya $suggested_id.');
            window.history.back();
        </script>";
        exit;
    } else {
        // Jika activityid belum dipakai, lakukan insert data ke database
        $insert_query = "INSERT INTO activities (activityid, siteid, judul, periode, tahun) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $dbconn->prepare($insert_query);
        $insert_stmt->bind_param("iisss", $activityid, $siteid, $judul, $periode, $tahun);

        if ($insert_stmt->execute()) {
            echo "<script>alert('Data berhasil disimpan!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data!'); window.history.back();</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Activity</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Input Activity</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>

            <div class="form-group">
                <label for="periode">Periode</label>
                <input type="text" class="form-control" id="periode" name="periode" required>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun</label>
                <input type="text" class="form-control" id="tahun" name="tahun" required>
            </div>

            <div class="form-group">
                <label for="activityid">Activity ID (PK)</label>
                <input type="number" class="form-control" id="activityid" name="activityid" required>
            </div>

            <div class="form-group">
                <label for="siteid">Site ID (FK)</label>
                <input type="number" class="form-control" id="siteid" name="siteid" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>