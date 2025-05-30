<?php
include("../conn/conn.php");
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subject_name']) && isset($_SESSION['id'])) {
        $subjectName = $_POST['subject_name'];
        $teacherId = $_SESSION['id'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_subject (subject, teacher_id) VALUES (:subject_name, :teacher_id)");
            $stmt->bindParam(":subject_name", $subjectName, PDO::PARAM_STR);
            $stmt->bindParam(":teacher_id", $teacherId, PDO::PARAM_STR); 
            $stmt->execute();

            header("Location: http://localhost/AAMS/subject.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in the subject name and ensure you are logged in!');
                window.location.href = 'http://localhost/AAMS/subject.php';
            </script>
        ";
    }
}
?>
