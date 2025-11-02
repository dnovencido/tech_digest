<?php
    include "helpers/session.php";
    include "helpers/require_login.php";
    include "models/blog.php";
    include "models/category.php";

    $categories = get_categories();
    
    if(array_key_exists("id", $_GET)) {
        $blog = view_blog($_GET['id']);
    }

?>
<?php include "layouts/_header.php";?>
<main>
    <section id="blog-view" class="container account-main">
        <div id="blog-view-container">
            <div id="blog-navigation">
                <?php include "layouts/_account-navigation.php" ?>
            </div>
            <div id="blog-view-content">
                <h2 class="title"><?= $blog['title'] ?></h2>
                <div class="blog-details">
                    <p class="author"><?= $blog['author'] ?></p>
                    <p class="date-published"><?= date('M d, Y', strtotime($blog['date_created'])) ?></p>
                </div>
                <div class="thumbnail">
                    <img src="data:image/jpeg;base64,<?= base64_encode($blog['thumbnail']) ?>" alt="" class="featured-image">
                </div>
                <div class="body">
                    <?= $blog['body'] ?>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include "layouts/_footer.php";?>