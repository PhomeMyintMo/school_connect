<?php

function save_admin($mysqli){
    $password =password_hash("admin123",PASSWORD_DEFAULT);
    $sql = "INSERT INTO `admin`(`admin_name`,`password`) 
    VALUES ('admin','$password')";
    if($mysqli->query($sql)){
        return true;
    }
    return false;
    }

function get_admin_by_name($mysqli,$admin_name){
        $sql = "SELECT * FROM `admin` WHERE `admin_name`='$admin_name'";
        $result = $mysqli->query($sql);
        return $result->fetch_assoc();
    }