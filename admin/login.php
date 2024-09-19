<?php
session_start();

// Daftar akun admin manual
$admin_accounts = [
    "siswantoro" => "admin01",
    "satura" => "admin02",
    "fajar" => "admin03"
];

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi username dan password dengan array admin_accounts
    if (array_key_exists($username, $admin_accounts) && $admin_accounts[$username] === $password) {
        // Regenerasi ID sesi untuk keamanan tambahan
        session_regenerate_id(true);

        // Set sesi pengguna
        $_SESSION['admin_username'] = $username;

       // Jika login berhasil
        if (array_key_exists($username, $admin_accounts) && $admin_accounts[$username] === $password) {
            $_SESSION['admin_username'] = $username;
            header("Location: datasite.php");
            exit();
        } else {
            $error = "Username atau password salah.";
        }
    }

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
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    Admin Login
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
