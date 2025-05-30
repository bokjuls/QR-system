<?php
include("../conn/conn.php");
session_start(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['f_name'], $_POST['m_name'], $_POST['l_name'], $_POST['course_section'])) {
        $studentId = $_POST['tbl_student_id'];
        $fName = $_POST['f_name'];
        $mName = $_POST['m_name'];
        $lName = $_POST['l_name'];
        $studentCourse = $_POST['course_section'];
        $teacherId = $_SESSION['id'];

        try {
            $stmt = $conn->prepare("UPDATE tbl_student SET f_name = :f_name, m_name = :m_name, l_name = :l_name, course_section = :course_section, teacher_id = :teacher_id WHERE tbl_student_id = :tbl_student_id");
            
            $stmt->bindParam(":tbl_student_id", $studentId, PDO::PARAM_STR); 
            $stmt->bindParam(":f_name", $lName, PDO::PARAM_STR); 
            $stmt->bindParam(":m_name", $mName, PDO::PARAM_STR); 
            $stmt->bindParam(":l_name", $lName, PDO::PARAM_STR); 
            $stmt->bindParam(":teacher_id", $teacherId, PDO::PARAM_STR);
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);

            $stmt->execute();

            header("Location: http://localhost/AAMS/masterlist.php");

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/AAMS/masterlist.php';
            </script>
        ";
    }
}
?>
