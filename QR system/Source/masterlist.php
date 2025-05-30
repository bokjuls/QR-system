<?php session_start(); ?>
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
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.50) 0%, rgba(240, 240, 240, 0.50) 100%), 
                        radial-gradient(at top center, rgba(255, 255, 255, 0.60) 0%, rgba(220, 220, 220, 0.60) 120%) #f0f0f0;
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

        .student-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .student-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
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
                <li class="nav-item active">
                    <a class="nav-link" href="masterlist.php">List of Students<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
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
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>List of Students</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Add Student</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                <table class="table text-center table-sm" id="studentTable">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Middle Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Course & Section</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        include('./conn/conn.php');

        // Check if 'id' exists in the GET request
        if (isset($_SESSION['id'])) {
            try {
                // Updated SQL query with aliases to avoid conflicts
                $stmt = $conn->prepare("
                    SELECT tbl_student.tbl_student_id, tbl_student.f_name, tbl_student.m_name, tbl_student.l_name, tbl_student.course_section, tbl_student.generated_code 
                    FROM tbl_student
                    LEFT JOIN tbl_user ON tbl_student.teacher_id = tbl_user.tb_user 
                    WHERE tbl_user.tb_user = :id
                ");
                $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll();

                // Check if result is not empty before iterating
                if ($result) {
                    foreach ($result as $row) {
                        $studentID = htmlspecialchars($row["tbl_student_id"]);
                        $fName = htmlspecialchars($row["f_name"]);
                        $mName = htmlspecialchars($row["m_name"]);
                        $lName = htmlspecialchars($row["l_name"]);
                        $studentCourse = htmlspecialchars($row["course_section"]);
                        $qrCode = htmlspecialchars($row["generated_code"]);
        ?>
        <tr>
            <th id="studentID-<?= $studentID ?>"><?= $studentID ?></th>
            <td id="fName-<?= $studentID ?>"><?= $fName ?></td>
            <td id="mName-<?= $studentID ?>"><?= $mName ?></td>
            <td id="lName-<?= $studentID ?>"><?= $lName ?></td>
            <td id="studentCourse-<?= $studentID ?>"><?= $studentCourse ?></td>
            <td>
                <div class="action-button">
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= $studentID ?>">
                        <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="16">
                    </button>

                    <!-- QR Modal -->
                    <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= $fName ?>'s QR Code</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrCode ?>" alt="" width="300">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-secondary btn-sm" onclick="updateStudent(<?= $studentID ?>)">&#128393;</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?= $studentID ?>)">&#10006;</button>
                </div>
            </td>
        </tr>
        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found.</td></tr>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudent">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="insertStudent.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fName">First Name</label>
                            <input type="text" class="form-control" name="fName" id="fName" required>
                        </div>
                        <div class="form-group">
                            <label for="mName">Middle Name</label>
                            <input type="text" class="form-control" name="mName" id="mName" required>
                        </div>
                        <div class="form-group">
                            <label for="lName">Last Name</label>
                            <input type="text" class="form-control" name="lName" id="lName" required>
                        </div>
                        <div class="form-group">
                            <label for="studentCourse">Course & Section</label>
                            <input type="text" class="form-control" name="studentCourse" id="studentCourse" required>
                        </div>
                        <div class="form-group">
                            <label for="qrCode">Generated Code</label>
                            <input type="text" class="form-control" name="qrCode" id="qrCode" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- DataTable -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function () {
            $('#studentTable').DataTable({
                lengthChange: false,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search",
                    paginate: {
                        next: '&raquo;',
                        previous: '&laquo;'
                    }
                },
                ordering: true,  // Enable ordering
                columnDefs: [
                    { targets: [0, 5], orderable: false }  // Disable ordering on specific columns
                ]
            });
        });

        function updateStudent(studentID) {
            let studentIDValue = $("#studentID-" + studentID).text();
            let fNameValue = $("#fName-" + studentID).text();
            let mNameValue = $("#mName-" + studentID).text();
            let lNameValue = $("#lName-" + studentID).text();
            let studentCourseValue = $("#studentCourse-" + studentID).text();
            
            // Implement your update logic here
            alert(`Update Student ID: ${studentIDValue}`);
        }

        function deleteStudent(studentID) {
            if (confirm("Are you sure you want to delete this student?")) {
                // Implement your delete logic here
                alert(`Delete Student ID: ${studentID}`);
            }
        }
    </script>
</body>
</html>
