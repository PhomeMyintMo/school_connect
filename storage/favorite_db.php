<?php
require_once("db.php");

function save_favorite($mysqli, $user_id, $post_id)
{
    $sql = "INSERT INTO `favorite`(`user_id`,`post_id`) 
    VALUES ($user_id,$post_id)";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function get_all_favorite_by_post_id($mysqli, $post_id) {
    $sql = "SELECT * FROM `favorite` WHERE `post_id`=$post_id";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_favorite_by_user_id($mysqli, $user_id)
{
    $sql = "SELECT * FROM `favorite` WHERE `user_id`=$user_id";
    $result = $mysqli->query($sql);
    return $result;
}

function get_fav_for_count($mysqli, $post_id)
{
    $sql = "SELECT * FROM `favorite` WHERE `post_id`=$post_id";
    $result = $mysqli->query($sql);
    return $result;
}

function get_fav_for_red($mysqli, $user_id, $post_id)
{
    $sql = "SELECT * FROM `favorite` WHERE `post_id`=$post_id AND `user_id`=$user_id";
    $result = $mysqli->query($sql);
    $count = count($result->fetch_all());
    if ($count == 0) return false;
    return true;
}


function delete_favorite($mysqli, $user_id, $post_id)
{
    $sql = "DELETE FROM `favorite` WHERE `user_id`=$user_id AND `post_id`=$post_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function delete_favorite_by_post_id($mysqli, $post_id) {
    $sql = "DELETE FROM `favorite` WHERE `post_id`=$post_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}
