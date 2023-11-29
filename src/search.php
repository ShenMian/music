<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<?php
session_start();

var_dump($_SESSION["username"]);

if (!isset($_GET['keyword']) || !isset($_SESSION["username"])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}

$keyword = $_GET['keyword'];
include "database.php";
?>

<main class="container">
    <div class="my-3 p-3 bg-body rounded shadow">
        <h6 class="border-bottom pb-2 mb-0">Music</h6>
        <?php
        $music = search_music($keyword);
        foreach ($music as $row) {
            $album_id = $row["album_id"];
            $music_name = preg_replace("/\w*?$keyword\w*/i", '<span class="text-primary">$0</span>', $row["name"]);
            $music_author = $row["author"];
            $music_duration = $row["duration"];
            $album_cover_img_path = get_album_by_id($album_id)->fetch(PDO::FETCH_ASSOC)['img_path'];
        ?>
            <div class="d-flex text-body-secondary pt-3" style="position: relative">
                <img class="bd-placeholder-img flex-shrink-0 me-2 rounded" src="<?= $album_cover_img_path ?>" alt="" width="32" height="32">
                <p class="pb-3 mb-0 small lh-sm border-bottom">
                    <strong class="d-block text-gray-dark"><?= $music_name ?></strong>
                    <span class="badge text-bg-success">Author: <?= $music_author ?></span>
                    <span class="badge text-bg-secondary">Duration: <?= $music_duration ?></span>
                </p>
                <a href="/index.php?album_id=<?= $album_id ?>" class="stretched-link"></a>
            </div>
        <?php
        }
        if ($music->rowCount() == 0) {
        ?>
            <small>Not found</small>
        <?php
        }
        ?>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow">
        <h6 class="border-bottom pb-2 mb-0">Albums</h6>
        <?php
        $albums = search_albums($keyword);
        foreach ($albums as $row) {
            $album_id = $row["id"];
            $album_name = preg_replace("/\w*?$keyword\w*/i", '<span class="text-primary">$0</span>', $row["name"]);
            $album_released_date = $row["released"];
            $album_cover_img_path = $row["img_path"];
        ?>
            <div class="d-flex text-body-secondary pt-3" style="position: relative">
                <img class="bd-placeholder-img flex-shrink-0 me-2 rounded" src="<?= $album_cover_img_path ?>" alt="" width="32" height="32">
                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                    <div class="d-flex justify-content-between">
                        <strong class="text-gray-dark"><?= $album_name ?></strong>
                    </div>
                    <span class="d-block">Released: <?= $album_released_date ?></span>
                </div>
                <a href="/index.php?album_id=<?= $album_id ?>" class="stretched-link"></a>
            </div>
        <?php
        }
        if ($albums->rowCount() == 0) {
        ?>
            <small>Not found</small>
        <?php
        }
        ?>
</main>

<?php include "footer.php"; ?>