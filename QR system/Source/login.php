<?php
session_start();
include "./conn/conn.php"; // Ensure this file sets up your PDO connection properly

// Check if user is already logged in
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php"); // Redirect to dashboard or another page
    exit();
}

$alertMessage = '';
$alertClass = '';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $hashed_password = $row['password'];

        if (password_verify($pass, $hashed_password)) {
            $_SESSION['id'] = $row['tb_user'];
            $_SESSION['fname'] = $row['fname'];
            header("Location: dashboard.php");
            exit();
        } else {
            $alertClass = 'alert-danger';
            $alertMessage = 'Wrong Password';
        }
    } else {
        $alertClass = 'alert-danger';
        $alertMessage = 'Wrong Email or Password';
    }

    $stmt = null; // Close the statement
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .alert-fade-out {
            opacity: 1;
            transition: opacity 1s ease-out;
        }
        .alert-hide {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-box box mt-5 p-4 border rounded">
            <h3 class="custom-title center-title">Automated Attendance Monitoring System for Computer Laboratory</h3>
            <hr>
            <form action="#" method="POST">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control password" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle" id="togglePassword"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                </div>

                <input type="submit" name="login" id="submit" value="Login" class="btn btn-primary btn-block">

                <div class="links mt-3">
                    Don't have an account? <a href="register.php">Signup Now</a>
                </div>

                <?php if ($alertMessage): ?>
                <div class='alert <?= $alertClass ?> alert-fade-out' id="alert-box" style='margin-top: 15px;'><?= $alertMessage ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const toggle = document.querySelector(".toggle"),
            input = document.querySelector(".password");
        toggle.addEventListener("click", () => {
            if (input.type === "password") {
                input.type = "text";
                toggle.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                toggle.classList.replace("fa-eye-slash", "fa-eye");
            }
        });

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
