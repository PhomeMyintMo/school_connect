<?php
session_start();

require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/user_db.php");
require_once("../storage/post_db.php");

if (!$user['is_admin']) {
    header("Location:../layout/error.php");
}

if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 3600, '/');

    header('Location: ../auth/login.php');
}

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}

$user_count=$admin_count=$event_count=$post_count=$announcement_count=0;
$users = get_all_user($mysqli);
while($user=$users->fetch_assoc()){
    if($user['is_admin']){
        $admin_count++;
    }elseif(!$user['is_admin']){
        $user_count++;
    }
}

$posts=get_all_event_with_users($mysqli);
while($post = $posts->fetch_assoc()){
$event_count++;
}

$posts=get_all_post_with_users($mysqli);
while($post = $posts->fetch_assoc()){
$post_count++;
}
$posts=get_all_announcement_with_users($mysqli);
while($post = $posts->fetch_assoc()){
$announcement_count++;
}


require_once("../layout/header.php");
require_once("../layout/sidebar2.php");
require_once("../layout/navbar2.php");


?>
<!-- container-fluid start -->
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
        <div class="card" style="width:200px;">
            <div class="card-header bg-primary text-white text-center">Registered Admin</div>
                <div class="p-3 text-center">
                    <?php echo $admin_count;?>
                </div>
        </div>
        </div>
    
        <div class="col-3">
        <div class="card" style="width:200px;">
            <div class="card-header bg-primary text-white text-center">Registered User</div>
                <div class="p-3 text-center">
                    <?php echo $user_count;?>
                </div>
        </div>
        </div>
        <div class="col-">
        
                <div class="p-3 text-center mt-3">
                    <a href="registeredUsers.php" class="btn btn-primary">See All Registered Users&nbsp;<i class="fa-solid fa-arrow-right"></i></a>
                </div>
        
        </div>
    </div><br>
    <!-- one row end -->
    <!-- one row start -->
    <div class="row">
        <div class="col-3">
        <div class="card" style="width:200px;">
            <div class="card-header bg-info text-white text-center">Total Event</div>
                <div class="p-3 text-center">
                    <?php echo $event_count;?>
                </div>
        </div>
        </div>
        <div class="col-3">
        <div class="card" style="width:200px;">
            <div class="card-header bg-info text-white text-center">Total Post</div>
                <div class="p-3 text-center">
                    <?php echo $post_count;?>
                </div>
        </div>
        </div>
        <div class="col-3">
        <div class="card" style="width:200px;">
            <div class="card-header bg-info text-white text-center">Total Announcement</div>
                <div class="p-3 text-center">
                    <?php echo $announcement_count;?>
                </div>
        </div>
        </div>
        

    </div>
 <!-- one row end -->      
    
    
    

<?php require_once("../layout/footer.php") ?>