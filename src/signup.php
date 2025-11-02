<?php
    include "helpers/session.php";
    include "models/registration.php";

    $errors = []; 

    if(isset($_SESSION['id'])) {
        header("Location: blogs");
    }

    if(isset($_POST['submit'])) {
        if(!$_POST['name']) {
            $errors[] = "Name is required.";
        }
        if(!$_POST['email']) {
            $errors[] = "Email is required.";
        }
        if(!$_POST['password']) {
            $errors[] = "Password is required.";
        }
        if(empty($errors)) {
            if(!check_existing_email($_POST['email'])) {
                $user = save_registration($_POST['name'], $_POST['email'], $_POST['password']);
                if(!empty($user)) {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['flash_message'] = "You have successfully created an account.";
                    header("Location: blogs.php");
                    exit;
                } else {
                    $errors[] = "There was an error logging in your account.";
                }
            } else {
                $errors[] = "Email address already exist.";
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
    <section id="signup" class="container">
        <div id="signup-form">
            <?php if (!empty($errors)) { ?>
                <?php include "layouts/_errors.php" ?>
            <?php } ?>
            <h1>Register an account</h1>
            <form method="post">
                <div class="input-control">
                    <label for="name">Name: </label>
                    <input type="text" name="name" class="input-field input-md" value="<?= $_POST['name'] ?>" />
                </div>
                <div class="input-control">
                    <label for="name">Email: </label>
                    <input type="email" name="email" class="input-field input-md" value="<?= $_POST['email'] ?>" />
                </div>
                <div class="input-control">
                    <label for="name">Password: </label>
                    <input type="password" name="password" class="input-field input-md" value="<?= $_POST['password'] ?>" />
                </div>
                <div class="input-control">
                    <label for="name">Confirm Password: </label>
                    <input type="password" name="confirm_password" class="input-field input-md" value="" />
                </div>
                <div class="input-control">
                    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Register" />
                </div>
            </form>
        </div>
    </section>
</main>
<?php include 'layouts/_footer.php';?>