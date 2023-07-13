
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Login</h4>
                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php } ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php

// koneksi

$host = 'localhost';
$usernamedb = 'root';
$passwordb = '';
$database = 'bloking';

$connection = new mysqli($host, $usernamedb, $passwordb, $database);

if ($connection->connect_error) {
    die("Koneksi database gagal: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $connection->query($query);

    if ($result) {
        // Memeriksa apakah ada pengguna dengan username yang cocok
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Memeriksa kecocokan password
            if (password_verify($password, $user['password'])) {
                session_start();
                
                // Memeriksa peran pengguna 
                $role = $user['role'];
                if ($role === 'admin') {
                    // alihkan ke halaman admin
                    header("Location: editpasien.php");
                    exit;
                } else {
                    // alihkan ke halaman user
                    header("Location: pasien.php");
                    exit;
                }
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Terjadi kesalahan dalam login.";
    }
}
?>

<?php
$connection->close();
?>
