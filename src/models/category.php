<?php
    require_once "config/db.php";

    function get_categories() {
        global $conn;
        $categories = [];

        $query = "SELECT * FROM categories";
        $stmt = $conn->prepare($query);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $categories = $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt->close();
        return $categories;
    }
?>
