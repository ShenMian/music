<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
include "csrf.php";

if (isset($_GET['error'])) {
    $error = $_GET['error'];
} else if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirmation'])) {
    $csrf_token = htmlspecialchars($_POST['csrf_token']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    if ($password != htmlspecialchars($_POST['password_confirmation'])) {
        header("Location: signup.php?error=Passwords are inconsistent");
        exit();
    }
    if (empty($csrf_token) || empty($username) || empty($email) || empty($password)) {
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

    $result = $db->prepare('SELECT * FROM tb_user WHERE name = ?');
    $result->execute([$username]);
    if ($result->rowCount() >= 1) {
        header("Location: signup.php?error=Username is already taken");
        exit();
    }

    $result = $db->prepare('INSERT INTO tb_user (name, password) VALUES (?, ?)');
    $password = password_hash($password, PASSWORD_DEFAULT);
    if (!$result->execute([$username, $password])) {
        die("failed to insert user record");
    }

    header("Location: signin.php");
    exit();
}

generate_csrf_token();
?>

<main style="width: 25rem; margin: 0 auto; float: none; margin-top: 3rem;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h2>Sign up</h2>

            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php
            }
            ?>

            <form action="/signup.php" method="post" class="needs-validation" novalidate>
                <?= generate_csrf_field() ?>
                <div class="mb-3">
                    <label for="id_username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="username_prepend">@</span>
                        <input type="text" name="username" class="form-control" id="id_username" aria-describedby="username_prepend" pattern="[0-9a-zA-Z_]{3,20}" required>
                        <div class="invalid-feedback">
                            Please provide a valid username.
                            <ul>
                                <li>Only contain alphanumeric characters and/or underscores.</li>
                                <li>Length between 3-20</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="id_email" aria-describedby="emailHelp" pattern="[^@]*@[^@]*\.[^@]*" required>
                    <div id="emailHelp" class="form-text">Your email address will not be visible to others.</div>
                    <div class="invalid-feedback">
                        Please provide a valid email address.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="id_password" required>
                    <div class="invalid-feedback">
                        Please provide a valid password.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_password_confirmation" class="form-label">Password confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control" id="id_password_confirmation" required>
                    <div class="invalid-feedback">
                        Please provide a valid password confirmation.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Sign up</button>

                <hr class="my-4">

                <p class="text-center">
                    <small class="text-body-secondary">By clicking Sign up, you agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#TermsAndConditionsModal">terms and conditions</a>.</small>
                </p>
            </form>
        </div>
    </div>

    <div class="modal fade" id="TermsAndConditionsModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLabel">Terms and Conditions</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php include "terms_and_conditions.html"; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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