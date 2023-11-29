<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
include "csrf.php";

if (isset($_GET['error'])) {
    $error = $_GET['error'];
} else if (isset($_POST['username']) && isset($_POST['password'])) {
    $csrf_token = htmlspecialchars($_POST['csrf_token']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    if (empty($csrf_token) || empty($username) || empty($password)) {
        // Invalid form data
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        exit();
    }

    if (!check_csrf_token($csrf_token)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        die('Forbidden');
    }

    include "database.php";
    $db = get_database();

    $result = $db->prepare('SELECT password FROM tb_admin WHERE name = ?');
    $result->execute([$username]);
    if ($result->rowCount() == 1 && password_verify($password, $result->fetch(PDO::FETCH_ASSOC)['password'])) {
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION["is_admin"] = True;

        header("Location: index.php");
        exit();
    }

    $result = $db->prepare('SELECT password FROM tb_user WHERE name = ?');
    $result->execute([$username]);
    if ($result->rowCount() != 1 || !password_verify($password, $result->fetch(PDO::FETCH_ASSOC)['password'])) {
        header('Location: signin.php?error=Incorrect username or password');
        die('Forbidden');
    }

    session_start();
    $_SESSION["username"] = $username;
    $_SESSION["is_admin"] = False;

    header("Location: index.php");
    exit();
}

generate_csrf_token();
?>

<main style="width: 25rem; margin: 0 auto; float: none; margin-top: 3rem;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h2>Sign in</h2>

            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php
            }
            ?>

            <form action="/signin.php" method="post" class="needs-validation" novalidate>
                <?= generate_csrf_field() ?>
                <div class="mb-3">
                    <label for="id_username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="id_username" pattern="[0-9a-zA-Z_]{3,20}" maxlength="20" required>
                    <div class="invalid-feedback">
                        Please provide a valid username.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="id_password" maxlength="128" required>
                    <div class="invalid-feedback">
                        Please provide a valid password.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign in</button>

                <hr class="my-4">

                <p class="text-center">
                    <small class="text-body-secondary">New to Piccolo Music? <a href="/signup.php">Create an account</a></small>
                </p>
            </form>
        </div>
    </div>

    <script>
        $(function() {
            $('.needs-validation').on('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });
    </script>
</main>

<?php include "footer.php"; ?>