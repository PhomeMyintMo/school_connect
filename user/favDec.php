<?php
require_once("../storage/db.php");
require_once("../storage/favorite_db.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['post_id'])) {
    $id = $_GET['id'];
    $post_id = $_GET['post_id'];
    $message = 'Hello! ID: ' . $id . ', Post ID: ' . $post_id;
    $status = delete_favorite($mysqli, $id, $post_id);
    echo json_encode(['message' => $status]);
} else {
    $error = array('error' => 'Missing parameters: id and/or post_id');
    echo json_encode($error);
}
