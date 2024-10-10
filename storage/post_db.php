<?php
require_once("db.php");

function save_post($mysqli, $user_id, $post_title, $category, $post_content, $file)
{
    $sql = "INSERT INTO `post`(`user_id`,`post_title`,`category`,`post_content`,`file`) 
    VALUES ('$user_id','$post_title','$category','$post_content','$file')";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function get_all_post_from_post_table($mysqli)
{
    $sql = "SELECT * FROM `post` INNER JOIN `user` WHERE `post`.`user_id` = `user`.`user_id`";
    $result = $mysqli->query($sql);
    return $result;
}


function get_all_announcement_with_users($mysqli)
{
    $sql = "SELECT * FROM `post` INNER JOIN `user` WHERE `post`.`category`='announcement' AND `post`.`user_id` = `user`.`user_id`";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_post_with_users($mysqli)
{
    $sql = "SELECT * FROM `post` INNER JOIN `user` WHERE `post`.`category`='post' AND `post`.`user_id` = `user`.`user_id`";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_event_with_users($mysqli)
{
    $sql = "SELECT * FROM `post` INNER JOIN `user` WHERE `post`.`category`='event' AND `post`.`user_id` = `user`.`user_id`";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_event($mysqli)
{
    $sql = "SELECT * FROM `post` WHERE category='event'";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_post($mysqli)
{
    $sql = "SELECT * FROM `post` WHERE category='post'";
    $result = $mysqli->query($sql);
    return $result;
}

function get_all_announcement($mysqli)
{
    $sql = "SELECT * FROM `post` WHERE category='announcement'";
    $result = $mysqli->query($sql);
    return $result;
}

function get_post_by_id($mysqli, $post_id)
{
    $sql = "SELECT * FROM `post` WHERE `post_id`=$post_id";
    $result = $mysqli->query($sql);
    return $result->fetch_assoc();
}

function get_all_post_by_user_id($mysqli, $user_id)
{
    $sql = "SELECT post.post_id,post.user_id,post.post_title,post.category,post.post_content,post.file,post.created_at,user.user_name
    FROM `post` INNER JOIN `user` ON post.user_id=user.user_id WHERE post.user_id=$user_id";
    $result = $mysqli->query($sql);
    return $result;
}

function update_post($mysqli, $post_id, $user_id, $post_title, $category, $post_content, $file, $updated_at)
{
    $sql = "UPDATE `post` SET `user_id`='$user_id',`post_title`='$post_title',
    `category`='$category',`post_content`='$post_content',`file`='$file',
    `updated_at`='$updated_at' WHERE `post_id`=$post_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function update_post_without_img($mysqli, $post_id, $user_id, $post_title, $post_content, $updated_at)
{
    $sql = "UPDATE `post` SET `user_id`='$user_id',`post_title`='$post_title',
    `post_content`='$post_content',
    `updated_at`='$updated_at' WHERE `post_id`=$post_id";

    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function delete_post($mysqli, $post_id)
{
    $sql = "DELETE FROM `post` WHERE `post_id`=$post_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function get_all_post_by_search($mysqli, $search)
{
    $sql = "SELECT * FROM `post`
    LEFT JOIN `user`
    ON `user`.`user_id` = `post`.`user_id`
    WHERE `post`.`post_title` LIKE '%$search%' 
    OR `post`.`post_content` LIKE '%$search%' 
    OR `post`.`category` LIKE '%$search%' 
    OR `user`.`user_name` LIKE '%$search%'";

    $result = $mysqli->query($sql);

    return $result;
}
