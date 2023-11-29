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
} else if (isset($_POST['album_id']) && isset($_POST['name']) && isset($_POST['author']) && isset($_POST['duration']) && isset($_FILES["audio"])) {
    $csrf_token = htmlspecialchars($_POST['csrf_token']);
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

    $album_id = $_POST['album_id'];
    $album_name = get_album_by_id($album_id)->fetch(PDO::FETCH_ASSOC)['name'];
    $file_extension = strtolower(pathinfo($_FILES["audio"]["name"], PATHINFO_EXTENSION));
    $target_path = "music/" . $album_name . "/" . $name . "." . $file_extension;
    if (!move_uploaded_file($_FILES["audio"]["tmp_name"], $target_path)) {
        die("failed to move uploaded file");
    }

    $db = get_database();
    $result = $db->prepare('INSERT INTO tb_music (name, author, duration, album_id, path) VALUES (?, ?, ?, ?, ?)');
    if (!$result->execute([$name, $author, $duration, $album_id, $target_path])) {
        die();
    }

    header('Location: music.php?album_id=' . $album_id);
    exit();
}

generate_csrf_token();

?>

<main style="width: 25rem; margin: 0 auto; float: none; margin-top: 3rem;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h2>Add music</h2>

            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php
            }
            ?>

            <form action="/new_music.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= generate_csrf_field() ?>
                <div class="mb-3">
                    <label for="id_album_id" class="form-label">Album</label>
                    <select name="album_id" class="form-select" id="id_album_id">
                        <?php
                        $result = get_albums();
                        foreach ($result as $row) {
                        ?>
                            <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="invalid-feedback">
                        Please provide a valid album.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="id_name" required>
                    <div class="invalid-feedback">
                        Please provide a valid name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_author" class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" id="id_author" required>
                    <div class="invalid-feedback">
                        Please provide a valid author name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_duration" class="form-label">Duration</label>
                    <input type="time" name="duration" class="form-control" id="id_duration" step="1" required>
                    <div class="invalid-feedback">
                        Please provide a valid duration.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_audio" class="form-label">Audio</label>
                    <input type="file" name="audio" accept="audio/*" class="form-control" id="id_audio" required>
                    <div class="invalid-feedback">
                        Please provide a valid audio file.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Create new album</button>
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