<?php
require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/user_db.php");
require_once("../storage/post_db.php");
require_once("../storage/favorite_db.php");

// if ($user['is_admin']) {
//     header("Location:../layout/error.php");
// }

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$user_id = $cookie_user['user_id'];

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();


$user_name = $user['user_name'];
$image = $user['image'];
$email = $user['email'];

if ($image === null) {
    $image = "../assets/img/profilepic.webp";
}

require_once("../layout/headerplain.php");

?>

<div class="container d-flex justify-content-center">

    <div class="card my-5 p-4" style="width: 800px;">

        <div class="container">
            <div class="row p-2 mb-4">
                <div class="col-2">
                    <img class="circular" style="width: 80px; height:80px;" src="<?php echo $image ?>">
                </div>
                <div class="col">
                    <p class="text-dark" style="font-weight:bold;"><?php echo $user_name ?></p>
                    <p><i class="fa-solid fa-envelope" style="color: grey;">&nbsp;&nbsp;</i><?php echo $email ?></p>
                    <?php
                            
                    if ($cookie_user && $user['user_id'] === $cookie_user['user_id']) {
                    ?>
                            <a class="btn btn-outline-primary border border-rounded border-primary" href="accountSetting.php">
                            <i class="fa-solid fa-gear"></i>
                            Settings</a>
                    <?php }?>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg mb-4" style="border-bottom: 1px solid #c5c6c7;">
                <ul class="navbar-nav ms-auto">
                <?php if($user['is_admin']){?>
                <li class="nav-item">
                <a class="nav-link" href="../admin/profile.php?user_id=<?= $user['user_id'] ?>">Post</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="../admin/favorite.php?user_id=<?= $user['user_id']?>">Favorite</a>
                </li>
                <?php }elseif(!$user['is_admin']){?>
                <li class="nav-item">
                <a class="nav-link" href="../user/profile.php?user_id=<?= $user['user_id'] ?>">Post</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="../user/favorite.php?user_id=<?= $user['user_id']?>">Favorite</a>
                </li>
                <?php } ?>

                </ul>

            </nav>

            <!-- #post container open -->
            <div class="container" id="post">
                <?php
                $posts = get_all_post_by_user_id($mysqli, $user_id);
                $posts1 = get_all_post_by_user_id($mysqli, $user_id);
                if (count($posts1->fetch_all()) == 0) {
                    ?>
                
                        <div class="container mb-4 d-flex justify-content-center">
                            <p>No Post Yet...
                            </p>
                        </div>
                
                    <?php
                    }

                while ($post = $posts->fetch_assoc()) {
                ?>
                    <div class="container mb-4">
                        <div class="row">
                            <div class="card" style="width:500px;">
                                <div class="card-header bg-primary text-white">
                                    <nav class="navbar justify-content-between">
                                        <div class="nav-item"><?php echo $post["category"] ?></div>
                                        <?php
                                        if ($cookie_user && $post['user_id'] === $cookie_user['user_id']) {
                                        ?>

                                            <div class="nav-item dropdown no-arrow">
                                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis" style="color: #ffffff;"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                                    <a class="dropdown-item" href="../user/postedit.php?update_id=<?php echo $post['post_id'] ?>" role="button">
                                                        <i class="fa-solid fa-pen" style="color: #3B71CA;"></i>
                                                        Edit
                                                    </a>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" href="../user/index.php?delete_id=<?php echo $post['post_id'] ?>">
                                                        <i class="fa-solid fa-trash-can" style="color: #d33636;"></i>
                                                        Delete
                                                    </a>

                                                </div>
                                            </div>
                                        <?php } ?>
                                    </nav>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="p-4">

                                        <div class="form-group">
                                            <div class="row d-flex justify-content-center">
                                                <div class="col">
                                                <?php if ($user['is_admin']) { ?>
                                            <a href="../admin/profile.php?user_id=<?= $user['user_id'] ?>" class="text-decoration-none">
                                                <img class="img-profile rounded-circle" src="<?php
                                                                                                if ($user['image'] == null) {
                                                                                                    echo '../assets/img/profilepic.webp';
                                                                                                } else {
                                                                                                    echo $user['image'];
                                                                                                }
                                                                                                ?>" style="width:2rem;height:2rem;">&nbsp;
                                            </a>
                                        <?php } else { ?>
                                            <a href="../user/profile.php?user_id=<?= $user['user_id'] ?>" class="text-decoration-none">
                                                <img class="img-profile rounded-circle" src="<?php
                                                                                                if ($user['image'] == null) {
                                                                                                    echo '../assets/img/profilepic.webp';
                                                                                                } else {
                                                                                                    echo $user['image'];
                                                                                                }
                                                                                                ?>" style="width:2rem;height:2rem;">&nbsp;
                                            </a>
                                        <?php } ?>

                                        <span class="mr-2 d-lg-inline text-gray-600 small">
                                            <?php echo $post['user_name'];
                                            if ($user['is_admin']) {
                                                echo '&nbsp; <small class="p-1 text-white bg-danger rounded"><small>Admin</small></small>';
                                            } ?>
                                        </span>
                                                </div>
                                                <div class="col">
                                                    <span class="mr-2 d-lg-inline text-gray-600 small" style="margin-left:70px;"><?php echo $post['created_at'] ?></span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group ">
                                            <p class="text-dark" style="font-weight:bold;"><?php echo $post["post_title"] ?></p>
                                        </div>

                                        <div class="form-group ">
                                            <p class="text-dark"><?php echo $post["post_content"] ?></p>
                                        </div>

                                        <div class="form-group">
                                            <?php if ($post['file'] != "") { ?>
                                                <?php if (strpos($post['file'], 'image/')) : ?>
                                                    <img src="<?= $post['file'] ?>" style="width: 400px;" alt="">
                                                <?php else : ?>
                                                    <?php
                                                    $explodedArrays = explode('/', $post['file']);
                                                    $lastString = $explodedArrays[count($explodedArrays) - 1];
                                                    ?>

                                                    <a class="btn btn-secondary" href="<?= $post['file'] ?>" target="_blank"><?= $lastString ?>
                                                        <i class="fa-solid fa-download"></i></a>

                                                <?php endif; ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-primary">
                            <div>
                                <button class="btn addFav" id="<?= $post['post_id'] ?>" type="button">
                                    <?php $isRed = get_fav_for_red($mysqli, $user['user_id'], $post['post_id']);
                                    // var_dump($isRed);
                                    if ($isRed) {
                                        echo '<i class="fa-solid fa-heart" id="1" style="color:red;"></i>';
                                    } else {
                                        echo '<i class="fa-solid fa-heart" id="0" ></i>';
                                    }
                                    ?>

                                    <?php
                                    $favcountres = get_fav_for_count($mysqli, $post['post_id']);
                                    $favCount = count($favcountres->fetch_all());
                                    ?>
                                    <span class="text-white"><?= $favCount ?></span>



                                </button>
                            </div>
                        </div>
                                </form>
                            </div>
                        </div>

                    </div>
                <?php } ?>
                <div>
                    <a class="btn btn-outline-primary border-1" onclick="history.go(-1)">Back</a>
                </div>
            </div>

            <?php require_once("../layout/footerplain.php"); ?>