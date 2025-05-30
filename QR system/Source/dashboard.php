<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAMS for Computer Laboratory</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.50) 0%, rgba(240,240,240,0.50) 100%), radial-gradient(at top center, rgba(255,255,255,0.60) 0%, rgba(220,220,220,0.60) 120%) #f0f0f0;
            background-blend-mode: multiply, multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .attendance-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
        }

        .attendance-container > div:last-child {
            width: 64%;
            margin-left: auto;
        }

        .title {
            font-size: 50px; /* Increased font size */
            font-weight: 700; /* Bold text */
            margin-bottom: 20px;
            color: #333; /* Dark color for better contrast */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3), 0 0 25px rgba(0,0,0,0.2), 0 0 5px rgba(0,0,0,0.2); /* Shadow effects */
            word-wrap: break-word; /* Ensures long words break and wrap */
            text-align: center; /* Center-align text */
            max-width: 90%; /* Limit width to avoid overflow */
        }
    </style>
</head>
<body>
    <?php
    session_start(); // Start the session

    // Check if the user is logged in, if not redirect to login page
    if (!isset($_SESSION['fname'])) {
        header("Location: login.php");
        exit();
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-4" href="#">AAMS for Computer Laboratory</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="masterlist.php">List of Students</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="subject.php">Subjects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="class.php">Class</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <div class="title">Hi <?php echo htmlspecialchars($_SESSION['fname']); ?>, Welcome to the Automated Attendance Monitoring System for Computer Laboratory</div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
