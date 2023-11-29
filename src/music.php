<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}

include "database.php";
$albums = get_albums();
if ($albums->rowCount() == 0) {
    die();
}
if (isset($_GET['album_id'])) {
    $album_id = $_GET['album_id'];
} else {
    $album_id = $albums->fetch(PDO::FETCH_ASSOC)['id'];
}
?>

<main>
    <form action="/music.php" method="get" class="p-4">
        <label class="form-label">Album</label>
        <select name="album_id" class="form-select" onchange="this.form.submit()">
            <?php
            $result = get_albums();
            foreach ($result as $row) {
            ?>
                <option value="<?= $row["id"] ?>" <?php if ($album_id == $row["id"]) echo 'selected'; ?>><?= $row["name"] ?></option>
            <?php
            }
            ?>
        </select>
    </form>

    <hr class="my-4">

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Author</th>
                <th scope="col">Duration</th>
                <th scope="col">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = get_music_by_album_id($album_id);
            foreach ($result as $row) {
            ?>
                <tr>
                    <th scope="row"><?= $row["id"] ?></th>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["author"] ?></td>
                    <td><?= $row["duration"] ?></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" onclick="window.location='/edit_music.php?music_id=<?= $row["id"] ?>';" class="btn btn-warning">Edit</button>
                            <button type="button" onclick="window.location='/remove_music.php?music_id=<?= $row["id"] ?>';" class="btn btn-danger">Remove</button>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="6" class="text-center">
                    <button type="button" onclick="window.location='new_music.php';" class="btn btn-success">Add new music</button>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<?php include "footer.php"; ?>