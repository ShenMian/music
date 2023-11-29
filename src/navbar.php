<?php
session_start();
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">
            <img src="img/icon.svg" alt="Logo" width="24" height="24" class="d-inline-block align-text-top">
            Piccolo Music
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                if (isset($_SESSION["username"])) {
                ?>
                    <span class="navbar-text">
                        Hello, <b><?= $_SESSION["username"] ?></b>
                    </span>
                    <li class="nav-item">
                        <a class="nav-link" href="/signout.php">Sign out</a>
                    </li>
                    <?php
                    if ($_SESSION["is_admin"]) {
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Manage
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/albums.php">Albums</a></li>
                                <li><a class="dropdown-item" href="/music.php">Music</a></li>
                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/signin.php">Sign in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/signup.php">Sign up</a>
                    </li>
                <?php
                }
                ?>
            </ul>
            <?php
            if (isset($_SESSION["username"])) {
            ?>
                <form action="/search.php" method="get" class="d-flex" role="search">
                    <input name="keyword" class="form-control me-2" type="search" placeholder="Search for music" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</nav>