<?php
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    die('Forbidden');
}

include "database.php";

assert(isset($_GET['music_id']));
$music_id = $_GET['music_id'];

// Remove audio file
$file_path = get_music_by_id($music_id)->fetch(PDO::FETCH_ASSOC)['path'];
if (!unlink($file_path)) {
    die();
}

$db = get_database();
$result = $db->prepare('DELETE FROM tb_music WHERE id = ?');
if (!$result->execute([$music_id])) {
    die();
}

header("Location: music.php");
exit();
