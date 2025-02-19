<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sadproj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM userss WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_as'] = $row['user_as'];
            header("Location: homepage.php");
            exit();
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Invalid credentials";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .login-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
            background: #ffffff;
        }
        .login-form .form-container {
            max-width: 400px;
            width: 100%;
        }
        .login-form h2 {
            margin-bottom: 20px;
        }
        .login-image {
            flex: 1;
            background: url('https://source.unsplash.com/800x800/?shoes,urban') no-repeat center center;
            background-size: cover;
        }
        .btn-custom {
            background-color: #fdbb2d;
            border: none;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #e0a800;
        }
        .text-small {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="form-container">
                <h2>Log in</h2>
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="email@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="#" class="text-decoration-none text-small">Forgot password?</a>
                </div>
                <p class="text-center mt-3 text-small">Don't have an account? <a href="signup.php">Register here</a></p>
            </div>
        </div>
        <div class="login-image"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
