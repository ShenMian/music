<?php
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}

include "database.php";

assert(isset($_GET['album_id']));
$album_id = $_GET['album_id'];

$album = get_album_by_id($album_id)->fetch(PDO::FETCH_ASSOC);

// Remove cover image file
$cover_path = $album['img_path'];
if (!unlink($cover_path)) {
    die();
}

// Remove audio files
$album_name = $album['name'];
$folder_path = 'music/' . $album_name;
array_map('unlink', glob("$folder_path/*.*"));
if (!rmdir($folder_path)) {
    die();
}

$db = get_database();
$result = $db->prepare('DELETE FROM tb_album WHERE id = ?');
if (!$result->execute([$album_id])) {
    die();
}

header('Location: albums.php');
exit();
