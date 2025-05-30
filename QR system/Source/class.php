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
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>List of Class</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addclass">Add Class</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                <table class="table text-center table-sm" id="scheduleTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Schedule</th>
                            <th scope="col">Teacher</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            include ('./conn/conn.php');

                            // Check if 'id' exists in the GET request
                            if (isset($_SESSION['id'])) {
                                try {
                                    // Updated SQL query with aliases to avoid conflicts
                                    $stmt = $conn->prepare("
                                        SELECT 
                                            tbl_class.id, 
                                            tbl_class.schedule, 
                                            tbl_subject.subject, 
                                            t1.fname, 
                                            t1.middle, 
                                            t1.lname 
                                        FROM 
                                            tbl_class 
                                        LEFT JOIN 
                                            tbl_user t1 ON tbl_class.user_id = t1.tb_user 
                                        LEFT JOIN 
                                            tbl_subject ON tbl_subject.id = tbl_class.subject_id 
                                        LEFT JOIN 
                                            tbl_user t2 ON t2.tb_user = tbl_subject.teacher_id 
                                        WHERE 
                                            t2.tb_user = :id
                                    ");
                                    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
                                    $stmt->execute();

                                    $result = $stmt->fetchAll();

                                    // Check if result is not empty before iterating
                                    if ($result) {
                                        foreach ($result as $row) {
                                            $id = $row["id"];
                                            $subject = $row["subject"];
                                            $schedule = $row["schedule"];
                                            $fname = $row["fname"];
                        ?>
                        <tr>
                            <th scope="row" id="id-<?= $id ?>"><?= $id ?></th>
                            <td id="subjectName-<?= $id ?>"><?= $subject ?></td>
                            <td id="class-<?= $id ?>"><?= $schedule ?></td>
                            <td id="teacher-<?= $id ?>"><?= $fname ?></td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="goToAttendance(<?= $id ?>)">&#128270;</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteClass(<?= $id ?>)">&#10006;</button>
                            </td>
                        </tr>
                        <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No records found.</td></tr>";
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

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addclass" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addclass" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addclass">Add Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-class.php" method="POST">
                    <?php 
                        include ('./conn/conn.php');

                        function fetchSubjects($conn, $teacherId) {
                            try {
                                $stmt = $conn->prepare("
                                    SELECT tbl_subject.id, tbl_subject.subject 
                                    FROM tbl_subject 
                                    LEFT JOIN tbl_user 
                                    ON tbl_subject.teacher_id = tbl_user.tb_user
                                    WHERE tbl_user.tb_user = :id
                                ");
                                $stmt->bindParam(':id', $teacherId, PDO::PARAM_INT);
                                $stmt->execute();

                                return $stmt->fetchAll();
                            } catch (PDOException $e) {
                                // Log error to a file or error monitoring system
                                error_log("Database error: " . $e->getMessage());
                                return false;
                            }
                        }

                        if (isset($_SESSION['id'])) {
                            $subjects = fetchSubjects($conn, $_SESSION['id']);
                        }
                        ?>

                        <div class="form-group">
                            <label for="subjectName">Subject</label>
                            <?php if ($subjects): ?>
                                <select class="form-control" id="subjectName" name="subject_name">
                                    <?php foreach ($subjects as $row): ?>
                                        <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <?php echo htmlspecialchars($row['subject']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>No subjects available.</p>
                            <?php endif; ?>
                            
                            <label for="schedule">Schedule</label>
                            <input type="datetime-local" class="form-control" id="schedule" name="schedule">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">Add Schedule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function () {
            $('#scheduleTable').DataTable();
        });

        function deleteClass(id) {
            if (confirm("Do you want to delete this class?")) {
                window.location = "./endpoint/delete-class.php?class=" + id;
            }
        }

        function updateClass(id) {
            // Logic for updating the class can be implemented here.
            alert('Update function for class ID ' + id + ' not implemented yet.');
        }
    </script>
                                    <script>
                                    function goToAttendance(id) {
                                        window.location.href = `attendance.php?id=${id}`;
                                    }
                                </script>
</body>
</html>
