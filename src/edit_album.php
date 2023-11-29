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
} else if (isset($_POST['album_id']) && isset($_POST['name']) && isset($_POST['author']) && isset($_POST['released'])) {
    $csrf_token = htmlspecialchars($_POST['csrf_token']);
    $album_id = htmlspecialchars($_POST['album_id']);
    $name = htmlspecialchars($_POST['name']);
    $author = htmlspecialchars($_POST['author']);
    $released = htmlspecialchars($_POST['released']);
    if (empty($csrf_token) || empty($name) || empty($author) || empty($released)) {
        // Invalid form data
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        exit();
    }

    if (!check_csrf_token($csrf_token)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        die('Forbidden');
    }

    $album = get_album_by_id($album_id)->fetch(PDO::FETCH_ASSOC);

    $old_folder_path = 'music/' . $album['name'];
    $new_folder_path = 'music/' . $name;
    if (!rename($old_folder_path, $new_folder_path)) {
        die();
    }

    if (file_exists($_FILES['cover_img']['tmp_name']) && is_uploaded_file($_FILES['myfile']['tmp_name'])) {
        $old_cover_path = $album['img_path'];
        if (!unlink($old_cover_path)) {
            die();
        }

        $file_extension = strtolower(pathinfo($_FILES["cover_img"]["name"], PATHINFO_EXTENSION));
        $target_path = "img/" . $name . "." . $file_extension;
        if (!move_uploaded_file($_FILES["cover_img"]["tmp_name"], $target_path)) {
            die("failed to move uploaded file");
        }
    }

    $db = get_database();
    $result = $db->prepare('UPDATE tb_album SET name = ?, author = ?, released = ? WHERE id = ?');
    if (!$result->execute([$name, $author, $released, $album_id])) {
        die();
    }

    header('Location: albums.php');
    exit();
}

generate_csrf_token();

assert(isset($_GET['album_id']));
$album = get_album_by_id($_GET['album_id'])->fetch();
?>

<main style="width: 25rem; margin: 0 auto; float: none; margin-top: 3rem;">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h2>Edit album</h2>

            <?php
            if (!empty($error)) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php
            }
            ?>

            <form action="/edit_album.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?= generate_csrf_field() ?>
                <input type="hidden" name="album_id" value="<?= $album['id'] ?>">
                <div class="mb-3">
                    <label for="id_name" class="form-label">Name</label>
                    <input type="text" name="name" value="<?= $album['name'] ?>" class="form-control" id="id_name" required>
                    <div class="invalid-feedback">
                        Please provide a valid name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_author" class="form-label">Author</label>
                    <input type="text" name="author" value="<?= $album['author'] ?>" class="form-control" id="id_author" required>
                    <div class="invalid-feedback">
                        Please provide a valid author name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_released" class="form-label">Released date</label>
                    <input type="date" name="released" value="<?= $album['released'] ?>" class="form-control" id="id_released" required>
                    <div class="invalid-feedback">
                        Please provide a valid released date.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_cover_img" class="form-label">Cover</label>
                    <input type="file" name="cover_img" accept="image/png, image/jpeg, image/webp" class="form-control" id="id_cover_img">
                    <div class="invalid-feedback">
                        Please provide a valid cover image file.
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