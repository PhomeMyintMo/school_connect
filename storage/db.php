<?php
$mysqli = new mysqli("localhost","root","");

if($mysqli->connect_errno){
    echo "connection error";
}

function create_database($mysqli){
    $sql = "CREATE DATABASE IF NOT EXISTS school_connect";
    if($mysqli->query($sql)){
        return true;
    }
    return false;
    }
create_database($mysqli);

function select_database($mysqli){
    if($mysqli->select_db("school_connect")){
        return true;
    }
    return false;
    }
    select_database($mysqli);

function create_tables($mysqli){
    $sql = "CREATE TABLE IF NOT EXISTS `user`
    (`user_id` INT AUTO_INCREMENT,
    `user_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `date_of_birth` VARCHAR(50) NOT NULL,
    `phoneno` VARCHAR(11) NOT NULL,
    `sex` VARCHAR(30) NOT NULL,
    `class` VARCHAR(30) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `is_admin` BOOLEAN DEFAULT(false),
    `image` VARCHAR(100),
    PRIMARY KEY(`user_id`),UNIQUE(`email`))";
    if($mysqli->query($sql)=== false) return false;
    


    $sql = "CREATE TABLE IF NOT EXISTS `post`
    (`post_id` INT AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `post_title` VARCHAR(300) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `post_content` VARCHAR(500) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `file` VARCHAR(200),
    FOREIGN KEY(`user_id`) REFERENCES `user`(`user_id`),
    PRIMARY KEY(`post_id`))";
    if($mysqli->query($sql)=== false) return false;
    

    $sql = "CREATE TABLE IF NOT EXISTS `favorite`(
    `favorite_id` INT AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `post_id` INT NOT NULL,
    FOREIGN KEY(`user_id`) REFERENCES `user`(`user_id`),
    FOREIGN KEY(`post_id`) REFERENCES `post`(`post_id`),
    PRIMARY KEY(`favorite_id`))";
    if($mysqli->query($sql)=== false) return false;

    $sql = "CREATE TABLE IF NOT EXISTS `admin`(
        `admin_id` INT AUTO_INCREMENT,
        `admin_name` VARCHAR(50) NOT NULL,
        `password` VARCHAR(100) NOT NULL,
        PRIMARY KEY(`admin_id`))";
        if($mysqli->query($sql)=== false) return false;

    
    return true;
     
}   
create_tables($mysqli);
// var_dump(create_tables($mysqli));
