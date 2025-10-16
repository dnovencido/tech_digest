<?php
    require_once "config/db.php";

    function login_account($email, $password) {
        global $conn;
        $user = [];

        // Select user by email
        $sql = "SELECT `id`, `name`, `email`, `password` FROM `users` WHERE `email` = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // Verify password if user exists
        if (!empty($row)) {
            $encrypted_password = md5(md5($row['id'] . $password));
            if ($encrypted_password === $row['password']) {
                $user = [
                    'id'   => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        return $user;
    }
?>
