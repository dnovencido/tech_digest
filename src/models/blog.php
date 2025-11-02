<?php
require_once "config/db.php";

function validate_form_blog($title, $body, $category_id) {
    $validation_errors = [];

    if(!$title) {
        $validation_errors[] = "Title is required.";
    }

    if(!$body) {
        $validation_errors[] = "The body of the blog is required.";
    }

    if(!empty($title) && strlen($title) < 20) {
        $validation_errors[] = "The title of the blog must have at least 20 characters.";
    }

    if(!empty($body) && str_word_count($body) < 20) {
        $validation_errors[] = "The body of the blog must have at least 20 words.";
    }

    if(empty($category_id)) {
        $validation_errors[] = "Category is required.";
    }

    return $validation_errors;
}

function save_blog($title, $body, $user_id, $category_id, $status, $thumbnail, $id=null) {
    global $conn;
    $flag = false;

    if($id == null) {
        $query = "INSERT INTO blogs (user_id, category_id, title, body, status, thumbnail, date_created) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $date_created = date("Y-m-d H:i:s");
        $stmt->bind_param("iisssss", $user_id, $category_id, $title, $body, $status, $thumbnail, $date_created);
    } else {
        $query = "UPDATE blogs 
                  SET title = ?, body = ?, thumbnail = ?, category_id = ?, status = ?, last_updated = ? 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $last_updated = date("Y-m-d H:i:s");
        $stmt->bind_param("sssissi", $title, $body, $thumbnail, $category_id, $status, $last_updated, $id);
    }

    if($stmt && $stmt->execute()) {
        $flag = true;
    }

    $stmt->close();
    return $flag;
}

function get_all_blogs($filter = [], $pagination = []) {
    global $conn;
    $blogs = [];

    $query = "SELECT c.category_name AS category, b.id, b.title, b.body, b.thumbnail, 
                     u.name AS author, b.status, b.date_created, b.last_updated
              FROM blogs AS b
              INNER JOIN categories AS c ON c.id = b.category_id
              INNER JOIN users AS u ON u.id = b.user_id";
    
    $conditions = [];
    $params = [];
    $types = '';

    if(!empty($filter)) {
        $query .= " WHERE ";
        foreach ($filter as $column => $value) {
            if ($column === "search" && is_array($value)) {
                $sub = [];
                foreach ($value[0] as $column_search => $value_search) {
                    $sub[] = "$value_search LIKE ?";
                    $params[] = "%{$value[1]}%";
                    $types .= 's';
                }
                $conditions[] = '(' . implode(' OR ', $sub) . ')';
            } else {
                $conditions[] = "$column = ?";
                $params[] = $value;
                $types .= is_int($value) ? 'i' : 's';
            }
        }
        $query .= implode(' AND ', $conditions);
    }

    $query .= " ORDER BY b.id DESC";

    if (!empty($pagination)) {
        $query .= " LIMIT ?, ?";
        $params[] = $pagination['offset'];
        $params[] = $pagination['total_records_per_page'];
        $types .= 'ii';
    }

    $stmt = $conn->prepare($query);
    if(!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if($stmt->execute()) {
        $result = $stmt->get_result();
        $blogs = $result->fetch_all(MYSQLI_ASSOC);
    }

    $stmt->close();
    return $blogs;
}

function view_blog($id) {
    global $conn;
    $blog = [];

    $query = "SELECT c.category_name AS category, c.id AS category_id, b.id, b.title, b.body, b.status, 
                    b.thumbnail, b.date_created, u.name AS author
            FROM blogs AS b
            INNER JOIN categories AS c ON c.id = b.category_id
            INNER JOIN users AS u ON u.id = b.user_id
            WHERE b.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        $result = $stmt->get_result();
        $blog = $result->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
    return $blog;
}

function delete_blog($current_user, $id) {
    global $conn;
    $flag = false;

    $query = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $blog = $result->fetch_array(MYSQLI_ASSOC);
        if($current_user == $blog['user_id']) {
            $delete_query = "DELETE FROM blogs WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $blog['id']);
            if($delete_stmt->execute()) {
                $flag = true;
            }
            $delete_stmt->close();
        }
    }

    $stmt->close();
    return $flag;
}

function get_total_number_records() {
    global $conn;
    $total = 0;

    $query = "SELECT COUNT(*) AS total FROM blogs";
    $stmt = $conn->prepare($query);
    if($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total = $row['total'];
    }

    $stmt->close();
    return $total;
}
?>
