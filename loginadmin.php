<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "softdev";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi Gagal: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE email = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();
        
        $_SESSION['admin_logged'] = true;
        $_SESSION['nama_admin'] = $admin_data['nama_admin']; 

        $conn->close();
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "Username/Email atau Password salah!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Masuk Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #0c0c0c;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .register-box {
            background-color: #111111;
            border: 1px solid #222222;
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            text-align: center;
        }

        h2 {
            color: #e5c38c;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .subtitle {
            color: #777777;
            font-size: 13px;
            margin-top: 6px;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            background-color: #161616;
            border: 1px solid #33291c;
            border-radius: 8px;
            padding: 16px 20px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input::placeholder {
            color: #555555;
        }

        .input-group input:focus {
            border-color: #bfa37a;
            background-color: #1a1a1a;
        }

        .btn-submit {
            display: block;
            width: 100%;
            background-color: #e5c38c;
            color: #111111;
            padding: 15px 0;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #d4b27b;
        }

        .alert-error {
            background-color: #2a1215;
            color: #f5c6cb;
            border: 1px solid #4d1c21;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <main class="auth-container">
        <div class="register-box">
            <h2>MASUK AKUN ADMIN</h2>
            <p class="subtitle">Panel Pengelolaan Sistem PetCareHub</p>

            <?php if (isset($error_msg)): ?>
                <div class="alert-error"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username / Email Admin" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-submit">MASUK SEKARANG</button>
            </form>
        </div>
    </main>

</body>

</html>