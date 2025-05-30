<?php
include('../conn/conn.php');
session_start(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tbl_subject_id'], $_POST['subject_name'], $_SESSION['id'])) {
        $subjectId = $_POST['tbl_subject_id'];
        $subjectName = $_POST['subject_name'];
        $teacherId = $_SESSION['id'];

        try {
            $stmt = $conn->prepare("UPDATE tbl_subject SET subject = :subject_name, teacher_id = :teacher_id WHERE id = :tbl_subject_id");
            
            $stmt->bindParam(":tbl_subject_id", $subjectId, PDO::PARAM_INT); 
            $stmt->bindParam(":subject_name", $subjectName, PDO::PARAM_STR);
            $stmt->bindParam(":teacher_id", $teacherId, PDO::PARAM_STR);
            $stmt->execute();

            header("Location: ../subject.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = '../subject.php';
            </script>
        ";
    }
}
?>
