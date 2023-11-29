<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}
?>

<main>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Cover</th>
                <th scope="col">Name</th>
                <th scope="col">Author</th>
                <th scope="col">Released</th>
                <th scope="col">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "database.php";
            $result = get_albums();
            foreach ($result as $row) {
            ?>
                <tr>
                    <th scope="row"><?= $row["id"] ?></th>
                    <td>
                        <img src="<?= $row["img_path"] ?>" class="img-thumbnail" alt="" width="64" height="64">
                    </td>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["author"] ?></td>
                    <td><?= $row["released"] ?></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" onclick="window.location='/music.php?album_id=<?= $row["id"] ?>';" class="btn btn-success">Music</button>
                            <button type="button" onclick="window.location='/edit_album.php?album_id=<?= $row["id"] ?>';" class="btn btn-warning">Edit</button>
                            <button type="button" onclick="window.location='/remove_album.php?album_id=<?= $row["id"] ?>';" class="btn btn-danger">Remove</button>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="6" class="text-center">
                    <button type="button" onclick="window.location='new_album.php';" class="btn btn-success">Add new album</button>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<?php include "footer.php"; ?>