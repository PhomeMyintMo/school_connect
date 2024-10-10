<?php
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

$search = isset($_GET['search']) ? $_GET['search'] : null;

$success = $invalid = "";
$status = true;

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];


$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$user_id = $cookie_user['user_id'];

$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();

$user_name = $user['user_name'];
$email = $user['email'];
$image = $user['image'];

require_once("../layout/header.php");
require_once("../layout/sidebar2.php");
require_once("../layout/navbar2.php");

?>

<div class="container-fluid">

    <?php
    // $posts = get_all_post($mysqli);
    // if (isset($_GET['user_id'])) {
    //     $posts = get_all_post_by_user_id($mysqli, $_GET['user_id']);
    // } elseif ($search) {
    //     $posts = get_all_post_by_search($mysqli, $search);
    // }
    ?>

    <div class="container mb-4 d-flex justify-content-center">
        <div class="card" style="width:500px;">
        <div class="card-header bg-primary text-white">Users List</div>
            <form method="post" enctype="multipart/form-data">
                <div class="p-3">
                    <div class="table">
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Profile</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $users =get_all_user($mysqli);
                                 $i = 1;
                                while ($user = $users->fetch_assoc()) {
                                ?>
                                <tr>
                                    <th><?php echo $user['user_id']?></th>
                                    <td><?php echo  $user['user_name'];
                                    if ($user['is_admin']) {
                                        echo '&nbsp; <small class="p-1 text-white bg-danger rounded"><small>Admin</small></small>';
                                        }
                                    ?>
                                    </td>
                                    <td><?php echo $user['email']?></td>
                                    <td>
                                        <?php 
                                        if($user['is_admin']){?>
                                            <a href="../admin/profile.php?user_id=<?php echo $user['user_id'] ?>" class=""> <?php echo $user['user_name']?></a>
                                        <?php }else{?>
                                            <a href="../user/profile.php?user_id=<?php echo $user['user_id'] ?>" class=""> <?php echo $user['user_name']?></a>
                                        <?php } ?>
                                
                                    </td>
                                </tr>
                                <?php
                        $i++;
                    } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </form>
        </div>


    </div>



    <?php require_once("../layout/footer.php") ?>