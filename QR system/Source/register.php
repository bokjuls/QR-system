<?php
session_start();
include "./conn/conn.php";

$alertMessage = null;
$alertClass = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $middle = trim($_POST['middle']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Simple validation
    $errors = [];

    if (empty($fname)) {
        $errors[] = 'First name is required.';
    }
    if (empty($lname)) {
        $errors[] = 'Last name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    // Check for existing email
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Email is already registered.';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO tbl_user (fname, middle, lname, email, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$fname, $middle, $lname, $email, $hashed_password])) {
            $alertClass = 'alert-success';
            $alertMessage = 'Registration successful. <a href="login.php">Login here</a>';
        } else {
            $alertClass = 'alert-danger';
            $alertMessage = 'Registration failed. Please try again.';
        }
    } else {
        $alertClass = 'alert-danger';
        $alertMessage = '<ul>';
        foreach ($errors as $error) {
            $alertMessage .= "<li>$error</li>";
        }
        $alertMessage .= '</ul>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* CSS for fading out the alert */
        .alert-fade-out {
            opacity: 1;
            transition: opacity 3s ease-in-out;
        }
        .alert-hide {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-box box mt-5 p-4 border rounded">
            <header class="h4 mb-4">Signup</header>
            <hr>
            <form action="#" method="POST">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="First Name" name="fname" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Middle Name" name="middle">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Last Name" name="lname" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon4"><i class="fa fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon5"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control password" placeholder="Password" name="password" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon6"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control password" placeholder="Confirm Password" name="confirm_password" required>
                    </div>
                </div>
                <input type="submit" value="Signup" class="btn btn-primary btn-block">
                <div class="links mt-3">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
            <?php if ($alertMessage): ?>
                <div class='alert <?= $alertClass ?> alert-fade-out' id="alert-box" style='margin-top: 15px;'><?= $alertMessage ?></div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var alertBox = $('#alert-box');
            if (alertBox.length) {
                setTimeout(function() {
                    alertBox.addClass('alert-hide');
                }, 2000); // Adjust the time to how long the alert should stay before fading out
            }
        });
    </script>
</body>

</html>