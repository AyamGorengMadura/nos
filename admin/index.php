<?php
// Mulai sesi
session_start();

include ('../koneksi.php');

// Cek koneksi
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
}

// Cek jika form login telah disubmit
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user di database
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $dbconn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan informasi user ke session
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            // Redirect ke halaman dashboard
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Password is incorrect.";
        }
    } else {
        $error = "Username not found.";
    }
}

// Logout jika tombol logout ditekan
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <?php if (isset($_SESSION['admin_id'])): ?>
        <!-- Jika user sudah login, tampilkan halaman dashboard -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        Admin Dashboard
                    </div>
                    <div class="card-body">
                        <h3>Welcome, <?= $_SESSION['admin_username']; ?>!</h3>
                        <p>You are logged in as admin.</p>
                        <a href="?logout=true" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Jika belum login, tampilkan form login -->
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        Admin Login
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>
                        <form action="datasite.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
