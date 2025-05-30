<?php
include("../conn/conn.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['qr_code'])) {
        $qrCode = $_POST['qr_code'];
        $class_id = $_POST['class_id'];
        $teacherId = $_SESSION['id'];
        $selectStmt = $conn->prepare("SELECT tbl_student_id FROM tbl_student WHERE generated_code = :generated_code");
        $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);

        if ($selectStmt->execute()) {
            $result = $selectStmt->fetch();
            if ($result !== false) {
                $studentID = $result["tbl_student_id"];
                
                $attendanceStmt = $conn->prepare("SELECT * FROM tbl_attendance WHERE tbl_student_id = :tbl_student_id ORDER BY time_in DESC LIMIT 1");
                $attendanceStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                $attendanceStmt->execute();
                $attendance = $attendanceStmt->fetch();

                $timeNow = date("Y-m-d H:i:s");

                if ($attendance) {
                    if ($attendance["time_out"] === null) {
                        $updateStmt = $conn->prepare("UPDATE tbl_attendance SET time_out = :time_out WHERE tbl_attendance_id = :tbl_attendance_id");
                        $updateStmt->bindParam(":time_out", $timeNow, PDO::PARAM_STR);
                        $updateStmt->bindParam(":tbl_attendance_id", $attendance["tbl_attendance_id"], PDO::PARAM_INT);

                        if ($updateStmt->execute()) {
                            echo "Attendance updated with time out.";
                        } else {
                            echo "Failed to update time out.";
                        }
                    } else {
                        echo "Attendance already recorded.";
                    }
                } else {
                    $insertStmt = $conn->prepare("INSERT INTO tbl_attendance (teacher_id, class_id, tbl_student_id, time_in) VALUES (:teacher_id, :class_id, :tbl_student_id, :time_in)");
                    $insertStmt->bindParam(":teacher_id", $teacherId, PDO::PARAM_STR); 
                    $insertStmt->bindParam(":class_id", $class_id, PDO::PARAM_STR); 
                    $insertStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR); 
                    $insertStmt->bindParam(":time_in", $timeNow, PDO::PARAM_STR); 

                    if ($insertStmt->execute()) {
                        echo "Attendance recorded with time in.";
                    } else {
                        echo "Failed to record attendance.";
                    }
                }
            } else {
                echo "No student found with the provided QR Code.";
            }
        } else {
            echo "Failed to execute the query.";
        }

        header("Location: http://localhost/AAMS/class.php");
        exit();
    } else {
        echo "
            <script>
                alert('QR code is missing!');
                window.location.href = 'http://localhost/AAMS/class.php';
            </script>
        ";
    }
}
?>
