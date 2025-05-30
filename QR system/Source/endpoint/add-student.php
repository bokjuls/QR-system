<?php
include("../conn/conn.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['f_name'], $_POST['m_name'], $_POST['l_name'], $_POST['course_section'])) {
        $fName = $_POST['f_name'];
        $mName = $_POST['m_name'];
        $lName = $_POST['l_name'];
        $studentCourse = $_POST['course_section'];
        $generatedCode = $_POST['generated_code'];
        $teacherId = $_SESSION['id'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_student (f_name, m_name, l_name, course_section, generated_code, teacher_id) VALUES (:f_name, :m_name, :l_name, :course_section, :generated_code, :user_id)");
            
            $stmt->bindParam(":f_name", $fName, PDO::PARAM_STR); 
            $stmt->bindParam(":m_name", $mName, PDO::PARAM_STR); 
            $stmt->bindParam(":l_name", $lName, PDO::PARAM_STR); 
            $stmt->bindParam(":user_id", $teacherId, PDO::PARAM_STR); 
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

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
