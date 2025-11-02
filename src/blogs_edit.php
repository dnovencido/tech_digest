<?php 
    include "helpers/session.php"; 
    include "helpers/require_login.php";
    include "models/category.php";
    include "models/status.php";
    include "models/blog.php";

    $categories = get_categories();
    $status = get_status();

    if (array_key_exists("id", $_GET)) {
        $blog = view_blog($_GET['id']);
        $blog_id = $blog['id'];

        if(isset($_POST['submit'])) {
            $thumbnail = (!empty($_FILES['thumbnail']['tmp_name'])) ? file_get_contents($_FILES['thumbnail']['tmp_name']) : null;
            $errors = validate_form_blog($_POST['title'], $_POST['body'], $_POST['category_id'], $_POST['thumbnail'], $_FILES['thumbnail']['tmp_name']);
            if(empty($errors)) {
                $thumbnail = (empty($_FILES['thumbnail']['tmp_name'])) ? $blog['thumbnail'] : file_get_contents($_FILES['thumbnail']['tmp_name']);
                $save_blog = save_blog($_POST['title'], $_POST['body'], $_SESSION['id'], $_POST['category_id'], $_POST['status_id'], $thumbnail, $blog_id);
                if($save_blog) {
                    $_SESSION['flash_message'] = [
                        'type' => 'success',
                        'text' => 'You have successfully updated a blog.'
                    ];
                    header("Location: /blogs");
                } else {
                    $errors[] = "Could not create a blog post. Please try again later.";
                }
            } 
        } else {
            $_POST = [
                'title' => isset($_POST['title']) ? $_POST['title'] : $blog['title'],
                'category_id' => isset($_POST['category_id']) ? $_POST['category_id'] : $blog['category_id'],
                'body' =>  isset($_POST['body']) ? $_POST['body'] : $blog['body'],
                'thumbnail' => isset($_POST['thumbnail']) ? $_POST['thumbnail'] : base64_encode($blog['thumbnail']),
                'status_id' => isset($_POST['status_id']) ? $_POST['status_id'] : $blog['status'],
            ];
        }
    }
?>

<?php include 'layouts/_header.php';?>
<main>
    <section id="new-blog" class="container">
        <h3>New Blog</h3>
        <?php include "layouts/_form.php" ?>
    </section>
</main>
<?php include 'layouts/_footer.php';?>