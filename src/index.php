<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<main style="margin: auto;">
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="row p-3">
            <?php
            include "database.php";
            $albums = get_albums();
            foreach ($albums as $row) {
                $album_id = $row["id"];
                $album_name = $row["name"];
                $album_released_date = $row["released"];
                $album_cover_img_path = $row["img_path"];
            ?>

                <div class="col">
                    <div class="card shadow" style="width: 13rem;">
                        <img src="<?= $album_cover_img_path ?>" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?= $album_name ?></h5>
                            <p class="card-text">Released: <?= $album_released_date ?></p>
                        </div>
                        <a href="?album_id=<?= $album_id ?>" class="stretched-link"></a>
                    </div>
                </div>

            <?php
            }
            ?>
        </div>
    </div>

    <?php
    if (isset($_GET['album_id'])) {
        $album_id = $_GET["album_id"];
        $album_name = get_album_by_id($album_id)->fetch(PDO::FETCH_ASSOC)['name'];
    ?>
        <div class="container">
            <h2><?= $album_name ?></h2>
            <div class="list-group p-3">
                <?php
                $music = get_music_by_album_id($album_id);
                foreach ($music as $row) {
                ?>
                    <button type="button" class="list-group-item list-group-item-action" data-src="<?= $row['path'] ?>"><?= $row['name'] ?></button>
                <?php
                }
                ?>
            </div>
            <audio controls src="" style="width: 100%;"></audio>

            <script>
                $(function() {
                    $('.needs-validation').on('submit', function(event) {
                        if (!this.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        $(this).addClass('was-validated');
                    });

                    $('.list-group-item').on('click', function(event) {
                        $('.list-group-item.active').removeClass('active');
                        let item = $(event.target);
                        item.addClass('active');

                        let audioPlayer = $('audio');
                        audioPlayer[0].pause();
                        audioPlayer.attr('src', item.data('src'));
                        audioPlayer[0].load();
                        audioPlayer[0].play();
                    })
                });
            </script>
        </div>
    <?php
    }
    ?>
</main>

<?php include "footer.php"; ?>