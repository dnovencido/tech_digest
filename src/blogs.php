<?php
    include "helpers/session.php"; 
    include "helpers/require_login.php";
?>
<?php include 'layouts/_header.php';?>
<main>
    <section id="blogs">
        <div id="inner-blog" class="container">
            <h1>Welcome <?= $_SESSION['name'] ?>!</h1>
        </div>
    </section>
</main>
<?php include 'layouts/_footer.php';?>
