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
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $dob = $_POST['dob'];
    $education = $_POST['education'];
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $user_as = $_POST['user_as'];
    $profile_picture = "default.jpg";

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $photo_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $photo_tmp = $_FILES['profile_picture']['tmp_name'];
        $photo_path = "uploads/" . $photo_name;
        move_uploaded_file($photo_tmp, $photo_path);
        $profile_picture = $photo_name;
    }

    $stmt = $conn->prepare("INSERT INTO userss (firstname, lastname, dob, education, username, password, email, phone, user_as, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $firstname, $lastname, $dob, $education, $username, $hashed_password, $email, $phone, $user_as, $profile_picture);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error signing up.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .signup-container {
           display: flex;
         min-height: 100vh;
         overflow-y: auto; 
}

        .signup-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
            background: #ffffff;
        }
        .signup-form .form-container {
            max-width: 400px;
            width: 100%;
        }
        .signup-form h2 {
            margin-bottom: 20px;
        }
        .signup-image {
            flex: 1;
            background: url('https://source.unsplash.com/800x800/?education,students') no-repeat center center;
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
    <div class="signup-container">
        <div class="signup-form">
            <div class="form-container">
                <h2>Sign Up</h2>
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="firstname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="lastname" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Educational Level</label>
                        <select class="form-control" name="education">
                            <option>Undergraduate</option>
                            <option>Graduated</option>
                            <option>Master's Degree</option>
                            <option>Doctor's Degree</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User As</label>
                        <select class="form-control" name="user_as">
                            <option>Admin</option>
                            <option>Student</option>
                            <option>Tutor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_picture">
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Sign Up</button>
                    <a href="login.php" class="btn btn-secondary w-100 mt-2">Log In</a>
                </form>
                <p class="text-center mt-3 text-small">Already have an account? <a href="login.php">Log in here</a></p>
            </div>
        </div>
        <div class="signup-image"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
