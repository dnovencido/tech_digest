<?php 
    include "helpers/session.php"; 
    include "helpers/require_login.php";
    include "models/category.php";
    include "models/status.php";
    include "models/blog.php";

    $categories = get_categories();
    $status = get_status();

    if(isset($_POST['submit'])) {
        $errors = validate_form_blog($_POST['title'], $_POST['body'], $_POST['category_id']);
        if(empty($errors)) {
            $thumbnail = (!empty($_FILES['thumbnail']['tmp_name'])) ? file_get_contents($_FILES['thumbnail']['tmp_name']) : null;
            $save_blog = save_blog($_POST['title'], $_POST['body'], $_SESSION['id'], $_POST['category_id'],  $_POST['status_id'], $thumbnail);
            if($save_blog) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'text' => 'You have successfully created a blog.'
                ];
                header("Location: /blogs");
            } else {
                $errors[] = "Could not create a blog post. Please try again later.";
            }
        }
    } else {
        $_POST = [
            'title' => '',
            'category_id' => '',
            'body' => '',
            'thumbnail' => '',
            'status_id' => '' 
        ];
    }
?>

<?php include 'layouts/_header.php';?>
<main>
    <section id="new-blog" class="container account-main">
        <div id="inner-blog-account">
            <div id="blog-navigation">
                <?php include "layouts/_account-navigation.php" ?>
            </div>
            <div id="blog-new">
                <h3>New Blog</h3>
                <?php include "layouts/_form.php" ?>
            </div>
        </div>
    </section>
</main>
<?php include 'layouts/_footer.php';?>