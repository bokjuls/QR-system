<?php
include ('../conn/conn.php');

if (isset($_GET['subject'])) {
    $subject = $_GET['subject'];

    try {

        $query = "DELETE FROM tbl_subject WHERE id = '$subject'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('Subject deleted successfully!');
                    window.location.href = 'http://localhost/AAMS/subject.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Failed to delete subject!');
                    window.location.href = 'http://localhost/AAMS/subject.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>