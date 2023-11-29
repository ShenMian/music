<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}

include "csrf.php";
include "database.php";

if (isset($_GET['error'])) {
    $error = $_GET['error'];
} else if (isset($_POST['music_id']) && isset($_POST['name']) && isset($_POST['author']) && isset($_POST['duration'])) {
    $csrf_token = htmlspecialchars($_POST['csrf_token']);
    $music_id = htmlspecialchars($_POST['music_id']);
    $name = htmlspecialchars($_POST['name']);
    $author = htmlspecialchars($_POST['author']);
    $duration = htmlspecialchars($_POST['duration']);
    if (empty($csrf_token) || empty($name) || empty($author) || empty($duration)) {
        // Invalid form data
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        exit();
    }

    if (!check_csrf_token($csrf_token)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        die('Forbidden');
    }

    // TODO: change file

    $db = get_database();
    $result = $db->prepare('UPDATE tb_music SET name = ?, author = ?, duration = ? WHERE id = ?');
    if (!$result->execute([$name, $author, $duration, $music_id])) {
        die();
    }

    header("Location: music.php");
    exit();
}

generate_csrf_token();

assert(isset($_GET['music_id']));
$music = get_music_by_id($_GET['music_id'])->fetch();
?>

<main style="width: 25rem; margin: 0 auto; float: none; margin-top: 3rem;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h2>Edit music</h2>

            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php
            }
            ?>

            <form action="/edit_music.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= generate_csrf_field() ?>
                <input type="hidden" name="music_id" value="<?= $music['id'] ?>">
                <div class="mb-3">
                    <label for="id_name" class="form-label">Name</label>
                    <input type="text" name="name" value="<?= $music['name'] ?>" class="form-control" id="id_name" required>
                    <div class="invalid-feedback">
                        Please provide a valid name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_author" class="form-label">Author</label>
                    <input type="text" name="author" value="<?= $music['author'] ?>" class="form-control" id="id_author" required>
                    <div class="invalid-feedback">
                        Please provide a valid author name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_duration" class="form-label">Duration</label>
                    <input type="time" name="duration" value="<?= $music['duration'] ?>" class="form-control" id="id_duration" step="1" required>
                    <div class="invalid-feedback">
                        Please provide a valid duration.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_audio" class="form-label">Audio</label>
                    <input type="file" name="audio" accept="audio/*" class="form-control" id="id_audio">
                    <div class="invalid-feedback">
                        Please provide a valid audio file.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Save changes</button>
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