<?php
    include "helpers/session.php";
    include "models/login.php";

    $errors = [];

    if(isset($_SESSION['id'])) {
        header("Location: blogs");
    }

    if(isset($_POST['submit'])) {
        if(!$_POST['email']) {
            $errors[] = "Email is required.";
        }
        if(!$_POST['password']) {
            $errors[] = "Password is required.";
        }
        if(empty($errors)) {
            $user = login_account($_POST['email'], $_POST['password']);
            if(!empty($user)) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['flash_message'] = "You have successfully logged in."
                ;    
                header("Location: blogs");
                exit;
            } else {    
                $errors[] = "The email that you've entered does not match any account.";
            }       
        } 
    } else {
        $_POST = [
            'name' => '',
            'password' => '',
            'email' => ''
        ];
    }
?>
<?php include 'layouts/_header.php';?>
<main>
    <section id="signin" class="container">
        <div id="signin-form">
            <?php if (!empty($errors)) { ?>
                <?php include "layouts/_errors.php" ?>
            <?php } ?>
            <h1>Login your account.</h1>
            <form method="post">
                <div class="input-control">
                    <label for="name">Email: </label>
                    <input type="email" name="email" class="input-field input-md" value="<?= $_POST['email'] ?>" />
                </div>
                <div class="input-control">
                    <label for="name">Password: </label>
                    <input type="password" name="password" class="input-field input-md" value="<?= $_POST['password'] ?>" />
                </div>
                <div class="input-control">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Login" />
                </div>
                <div id="signup-account">
                    <p>Don't have an account? <a href="signup">Signup</a> </p>
                </div>
            </form>
        </div>
    </section>
</main>
<?php include 'layouts/_footer.php';?>