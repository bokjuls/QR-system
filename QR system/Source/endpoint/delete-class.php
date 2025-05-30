<?php
include ('../conn/conn.php');

if (isset($_GET['class'])) {
    $class = $_GET['class'];

    try {

        $query = "DELETE FROM tbl_class WHERE id = '$class'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('class deleted successfully!');
                    window.location.href = 'http://localhost/AAMS/class.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Failed to delete class!');
                    window.location.href = 'http://localhost/AAMS/class.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>