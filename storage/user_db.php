<?php
require_once("db.php");

function save_user($mysqli, $user_name, $email, $date_of_birth, $phoneno, $sex, $class, $hash_password, $role)
{
    $is_admin = ($role === 'admin') ? 1 : 0;
    $sql = "INSERT INTO `user`(`user_name`,`email`,`date_of_birth`,`phoneno`,`sex`,`class`,`password`,`is_admin`) 
    VALUES ('$user_name','$email','$date_of_birth','$phoneno','$sex','$class','$hash_password','$is_admin')";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function get_all_user($mysqli)
{
    $sql = "SELECT * FROM `user`";
    $result = $mysqli->query($sql);
    return $result;
}

function get_user_by_id($mysqli, $user_id)
{
    $sql = "SELECT * FROM `user` WHERE `user_id`=$user_id";
    $result = $mysqli->query($sql);
    return $result;
}

function get_user_by_class($mysqli, $class)
{
    $sql = "SELECT * FROM `user` WHERE `class`='$class'";
    $result = $mysqli->query($sql);
    return $result->fetch_assoc();
}

function get_user_by_name($mysqli, $user_name)
{
    $sql = "SELECT * FROM `user` WHERE `user_name`='$user_name'";
    $result = $mysqli->query($sql);
    return $result->fetch_assoc();
}

function get_user_by_email($mysqli, $email)
{
    $sql = "SELECT * FROM `user` WHERE `email`='$email'";
    $result = $mysqli->query($sql);
    return $result->fetch_assoc();
}


function update_user($mysqli, $user_id, $email, $date_of_birth, $phoneno)
{
    $sql = "UPDATE `user` SET 
    `email`='$email',`date_of_birth`='$date_of_birth',
    `phoneno`='$phoneno' WHERE `user_id`=$user_id ";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function update_user_by_admin($mysqli, $user_id,$user_name,$email, $date_of_birth, $phoneno,$sex,$class,$is_admin)
{
    $sql = "UPDATE `user` SET 
    `user_name`='$user_name',`email`='$email',`date_of_birth`='$date_of_birth',
    `phoneno`='$phoneno',`sex`='$sex',`class`='$class',`is_admin`='$is_admin' WHERE `user_id`=$user_id ";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function update_user_img($mysqli, $user_id)
{
    $sql = "UPDATE `user` SET 
    `image`=null WHERE `user_id`=$user_id ";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function update_profile($mysqli, $image, $user_id)
{
    $sql = "UPDATE `user` SET `image`='$image' WHERE `user_id`=$user_id ";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function delete_profile($mysqli, $image, $user_id)
{
    $sql = "DELETE FROM `user` SET `image`='$image' WHERE `user_id`=$user_id ";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function change_password_by_id($mysqli, $user_id, $password)
{
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE `user` SET `password` = '$password' where `user_id` = $user_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}

function delete_user($mysqli, $user_id)
{
    $sql = "DELETE FROM `user` WHERE `user_id`=$user_id";
    if ($mysqli->query($sql)) {
        return true;
    }
    return false;
}
