<?php
    include "helpers/session.php";
    include "models/blog.php";
    
    $result = [];

    if(array_key_exists("id", $_GET)) {
        $result['deleted'] = false;
        $is_deleted = delete_blog($_SESSION['id'], $_GET['id']);

        if($is_deleted) {
            $result['deleted'] = true;
        } 
    }

    echo json_encode($result);
?>