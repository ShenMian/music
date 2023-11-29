<?php
function get_database(): PDO
{
    try {
        static $db = new PDO('mysql:host=localhost;dbname=db_music', 'root', '');
        return $db;
    } catch (PDOException $e) {
        die('failed to connect to database: ' . $e->getMessage());
    }
}

function get_albums(): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT * FROM tb_album');
    if (!$stmt->execute())
        die('failed to query from database');
    return $stmt;
}

function get_album_by_id($album_id): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT * FROM tb_album WHERE id = ?');
    $stmt->execute([$album_id]);
    assert($stmt->rowCount() == 0 || $stmt->rowCount() == 1);
    return $stmt;
}

function get_music_by_id($music_id): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT * FROM tb_music WHERE id = ?');
    $stmt->execute([$music_id]);
    assert($stmt->rowCount() == 0 || $stmt->rowCount() == 1);
    return $stmt;
}

function get_music_by_album_id($album_id): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT tb_music.* FROM tb_music JOIN tb_album ON album_id = tb_album.id WHERE tb_album.id = ?');
    $stmt->execute([$album_id]);
    return $stmt;
}

function get_music_by_album_name($album_name): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT tb_music.* FROM tb_music JOIN tb_album ON album_id = tb_album.id WHERE tb_album.name = ?');
    $stmt->execute([$album_name]);
    return $stmt;
}

function get_author_by_music_name($music_name): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT tb_album.author FROM tb_album JOIN tb_music ON album_id = tb_album.id WHERE tb_music.name = ?');
    $stmt->execute([$music_name]);
    return $stmt;
}

function search_albums($keyword): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT * FROM tb_album WHERE LOWER(name) LIKE LOWER(?)');
    $stmt->execute(['%' . $keyword . '%']);
    return $stmt;
}

function search_music($keyword): PDOStatement
{
    $db = get_database();
    $stmt = $db->prepare('SELECT * FROM tb_music WHERE LOWER(name) LIKE LOWER(?)');
    $stmt->execute(['%' . $keyword . '%']);
    return $stmt;
}
