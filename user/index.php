<?php
require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/user_db.php");
require_once("../storage/post_db.php");
require_once("../storage/favorite_db.php");
$del_status = false;

if ($user['is_admin']) {
    header("Location:../layout/error.php");
}

if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 3600, '/');
    header('Location: ../auth/login.php');
}

$search = isset($_GET['search']) ? $_GET['search'] : null;

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}

$success = $invalid = "";
$status = true;

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];
if (isset($_GET["delete_id"])) {
    $post_id = $_GET["delete_id"];
    $favourites = get_all_favorite_by_post_id($mysqli, $post_id);
    if($favourites->num_rows > 0) {
        $del_status = delete_favorite_by_post_id($mysqli, $post_id);
        if($del_status) {
            $status = delete_post($mysqli, $post_id);
            if ($status) {
                $success = "Post Deleted!";
                header("Location:../user/index.php?success=$success");
            } else {
                $invalid = "Error Deleting!";
                header("Location:../user/index.php?invalid=$invalid");
            }
        }
    } else {
        $status = delete_post($mysqli, $post_id);
        if ($status) {
            $success = "Post Deleted!";
            header("Location:../user/index.php?success=$success");
        } else {
            $invalid = "Error Deleting!";
            header("Location:../user/index.php?invalid=$invalid");
        }
    }
}


$user_id = $cookie_user['user_id'];
$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();
$user_name = $user['user_name'];
$image = $user['image'];

require_once("../layout/header.php");
require_once("../layout/sidebar.php");
require_once("../layout/navbar.php");

?>

<div class="container-fluid">
    <?php if ($success !== "") { ?>
        <div class="alert alert-success"><?php echo $success ?></div>
    <?php } ?>
    <?php if ($invalid !== "") { ?>
        <div class="alert alert-danger"><?php echo $invalid ?></div>
    <?php } ?>
    <div class="container mb-4 d-flex justify-content-center">
        <div class="card" style="width: 500px;">
            <div class="card-header bg-primary text-white">Add Post</div>
            <div class="p-4">
                <a href="../user/profile.php" class="text-decoration-none">
                    <img class="img-profile rounded-circle" src="<?php
                                                                    if ($image === null) {
                                                                        echo '../assets/img/profilepic.webp';
                                                                    } else {
                                                                        echo $image;
                                                                    }
                                                                    ?>" style="width:2rem;height:2rem;">&nbsp;
                </a>&nbsp;&nbsp;
                <a href="../user/addpost.php" class="btn btn-primary">
                    <i class="fa-regular fa-pen-to-square" style="color: #ffffff;"></i>&nbsp;&nbsp;Make a Post</a>
            </div>
        </div>
    </div>

    <?php
    $posts = get_all_post_from_post_table($mysqli);
    $posts1 = get_all_post_from_post_table($mysqli);
    $posts2 = get_all_post_from_post_table($mysqli);

    if (isset($_POST['search_submit'])) {
        $search = $_POST['search'];
        $posts = get_all_post_by_search($mysqli, $search);
        $posts1 = get_all_post_by_search($mysqli, $search);
    }

    if (count($posts2->fetch_all()) == 0) {
        ?>
    
            <div class="container mb-4 d-flex justify-content-center">
                <p>No Post Yet.......
                </p>
            </div>
    
        <?php
        }

    elseif (count($posts1->fetch_all()) == 0) {
    ?>

        <div class="container mb-4 d-flex justify-content-center">
            <p>No Result Found.......
            <i class="fa-regular fa-face-sad-tear" style="color: grey;"></i>
            </p>
        </div>

    <?php
    }

    while ($post = $posts->fetch_assoc()) {
    ?>
        <div class="container mb-4 d-flex justify-content-center">

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
                                        <?php if ($post['is_admin']) { ?>
                                            <a href="../admin/profile.php?user_id=<?= $post['user_id'] ?>" class="text-decoration-none">
                                                <img class="img-profile rounded-circle" src="<?php
                                                                                                if ($post['image'] == null) {
                                                                                                    echo '../assets/img/profilepic.webp';
                                                                                                } else {
                                                                                                    echo $post['image'];
                                                                                                }
                                                                                                ?>" style="width:2rem;height:2rem;">&nbsp;
                                            </a>
                                        <?php } else { ?>
                                            <a href="../user/profile.php?user_id=<?= $post['user_id'] ?>" class="text-decoration-none">
                                                <img class="img-profile rounded-circle" src="<?php
                                                                                                if ($post['image'] == null) {
                                                                                                    echo '../assets/img/profilepic.webp';
                                                                                                } else {
                                                                                                    echo $post['image'];
                                                                                                }
                                                                                                ?>" style="width:2rem;height:2rem;">&nbsp;
                                            </a>
                                        <?php } ?>

                                        <span class="mr-2 d-lg-inline text-gray-600 small">
                                            <?php echo $post['user_name'];
                                            if ($post['is_admin']) {
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



    <?php require_once("../layout/footer.php") ?>

    