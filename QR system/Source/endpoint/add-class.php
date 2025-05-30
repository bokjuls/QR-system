<?php
include("../conn/conn.php"); 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subject_name']) && isset($_SESSION['id']) && isset($_POST['schedule'])) {
        $subjectName = $_POST['subject_name'];
        $schedule = $_POST['schedule'];
        $teacherId = $_SESSION['id'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_class (schedule, subject_id, user_id) VALUES (:schedule, :subject_id, :user_id)");
            $stmt->bindParam(":subject_id", $subjectName, PDO::PARAM_INT);
            $stmt->bindParam(":schedule", $schedule, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $teacherId, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: http://localhost/AAMS/class.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in the subject name and schedule, and ensure you are logged in!');
                window.location.href = 'http://localhost/AAMS/class.php';
            </script>
        ";
    }
}
?>
