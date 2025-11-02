<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>The Tech Digest</title>
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <header>
            <nav>
                <div id="inner-nav" class="container">
                    <div id="logo" class="nav-item">
                        <a href="/"><img src="/assets/images/logo.png" alt="" width="200"></a>
                    </div>
                    <div id="links" class="nav-item">
                        <ul>
                            <?php if(!isset($_SESSION['id'])) { ?>
                                <li>
                                    <a href="/" class="nav-link">Home</a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">Browse</a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">About Us</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php if(!isset($_SESSION['id'])) { ?>
                        <div id="action-btn" class="nav-item">
                            <a href="signin" id="login" class="btn btn-primary btn-sm">Login</a>
                        </div>
                    <?php } else { ?>
                        <div class="dropdown" class="nav-item">
                            <button class="dropdown-btn"><?= $_SESSION['name'] ?></button>
                            <div id="drop-down-list" class="dropdown-content">
                                <a href="#">Settings</a>
                                <a href="#">Profile</a>
                                <a href="logout?logout=true">Logout</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </nav>
        </header> 