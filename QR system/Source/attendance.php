<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAMS for Computer Laboratory</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

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

        .attendance-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table.dataTable thead > tr > th.sorting,
        table.dataTable thead > tr > th.sorting_asc,
        table.dataTable thead > tr > th.sorting_desc,
        table.dataTable thead > tr > th.sorting_asc_disabled,
        table.dataTable thead > tr > th.sorting_desc_disabled,
        table.dataTable thead > tr > td.sorting,
        table.dataTable thead > tr > td.sorting_asc,
        table.dataTable thead > tr > td.sorting_desc,
        table.dataTable thead > tr > td.sorting_asc_disabled,
        table.dataTable thead > tr > td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ml-4" href="#">AAMS for Computer Laboratory</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="masterlist.php">List of Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subject.php">Subjects</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="class.php">Class<span class="sr-only">(current)</span></a>
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
        <div class="attendance-container row">
            <div class="qr-container col-4">
                
                <div class="scanner-con">
                    <h5 class="text-center">Scan your QR Code here for your attendance</h5>
                    <video id="interactive" class="viewport" width="100%"></video>
                </div>

                <div class="qr-detected-container" style="display: none;">
                    <form id="attendanceForm" action="./endpoint/add-attendance.php" method="POST">
                        <h4 class="text-center">Student QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                        <?php
                        // Get the 'id' value from the URL
                        $id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
                        ?>

                        <!-- HTML code -->
                        <input type="hidden" id="class_id" name="class_id" value="<?php echo $id; ?>">
                        <!-- The submit button is removed because submission is automatic -->
                    </form>
                </div>
            </div>

            <div class="attendance-list col-8">
                <h4>List of Present Students</h4>
                <div class="table-container table-responsive">
                    <!-- Print button -->
                    <button class="btn btn-primary mb-3" onclick="printTable()">Print Attendance</button>
                    <table class="table text-center table-sm" id="attendanceTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Course & Section</th>
                                <th scope="col">Teacher</th>
                                <th scope="col">Time In</th>
                                <th scope="col">Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            include('./conn/conn.php');

                            // Check if 'id' exists in the GET request
                            if (isset($_GET['id'])) {
                                try {
                                    // Prepare the SQL query with a placeholder for the ID
                                    $stmt = $conn->prepare("
                                        SELECT * 
                                        FROM tbl_attendance 
                                        LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id 
                                        LEFT JOIN tbl_user ON tbl_user.tb_user = tbl_attendance.teacher_id 
                                        LEFT JOIN tbl_class ON tbl_class.id = tbl_attendance.class_id 
                                        WHERE tbl_class.id = :id
                                    ");

                                    // Bind the ID parameter
                                    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

                                    // Execute the statement
                                    $stmt->execute();

                                    // Fetch all results
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Loop through the results
                                    foreach ($result as $row) {
                                        $attendanceID = htmlspecialchars($row["tbl_attendance_id"]);
                                        $fName = htmlspecialchars($row["f_name"]);
                                        $mName = htmlspecialchars($row["m_name"]);
                                        $lName = htmlspecialchars($row["l_name"]);
                                        $teacher = htmlspecialchars($row["fname"]);
                                        $studentCourse = htmlspecialchars($row["course_section"]);
                                        $timeIn = htmlspecialchars($row["time_in"]);
                                        $timeOut = htmlspecialchars($row["time_out"]);
                        ?>
                            <tr>
                                <th scope="row"><?= $attendanceID ?></th>
                                <td><?= $fName ?> <?= $mName ?> <?= $lName ?></td>
                                <td><?= $studentCourse ?></td>
                                <td><?= $teacher ?></td>
                                <td><?= $timeIn ?></td>
                                <td><?= $timeOut ?></td>
                            </tr>
                        <?php
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . htmlspecialchars($e->getMessage());
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Instascan JS -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
    let scanner;

    function startScanner() {
        scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

        scanner.addListener('scan', function (content) {
            document.getElementById("detected-qr-code").value = content;
            console.log(content);

            document.getElementById("attendanceForm").submit();

            scanner.stop();

            alert("QR Code scanned and form submitted.");
        });

        Instascan.Camera.getCameras()
            .then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                    alert('No cameras found.');
                }
            })
            .catch(function (err) {
                console.error('Camera access error:', err);
                alert('Camera access error: ' + err);
            });
    }

    function printTable() {
        const tableHtml = document.getElementById('attendanceTable').outerHTML;

        const printWindow = window.open('', '', 'height=600,width=800');


        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">');
        printWindow.document.write('</head><body >');
        printWindow.document.write('<h4>List of Present Students</h4>');
        printWindow.document.write(tableHtml);
        printWindow.document.write('</body></html>');


        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }

    document.addEventListener('DOMContentLoaded', startScanner);
    </script>

</body>
</html>
