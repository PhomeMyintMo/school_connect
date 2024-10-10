<?php
require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/post_db.php");
require_once("../storage/user_db.php");

$post_title = $category = $post_content = $file = "";
$category_err = $post_content_err = "";
$success = $invalid = "";
$status = true;

if (!$user['is_admin']) {
    header("Location:../layout/error.php");
}

if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 3600, '/');
    header('Location: ../auth/login.php');
}

$update_id = null;

if (isset($_GET['update_id'])) {
    $update_id = $_GET['update_id'];
} else {
    header("Location: ./post.php");
}

$post = get_post_by_id($mysqli, $update_id);

if (!$post) {
    header("Location: ./post.php");
}

$post_title = $post['post_title'];
$category = $post['category'];
$post_content = $post['post_content'];
$oldFile = $post['file'];

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];

if (isset($_POST['submit'])) {
    $post_title = htmlspecialchars($_POST["post_title"]);
    $category = htmlspecialchars($_POST["category"]);
    $post_content = htmlspecialchars($_POST["post_content"]);
    $newFile = $_FILES['attach_file'];

    if ($category === "") {
        $status = false;
        $category_err = "Category is required!";
    }

    if ($post_content === "") {
        $status = false;
        $post_content_err = "Content is required!";
    }

    if($oldFile && !($_FILES['attach_file']['name'])) {
        $newFile = $_FILES['attach_file'];
        // $category = null;
        if ($newFile['name'] === '') {

            // delete old file
            if (file_exists($oldFile)) unlink($oldFile);
            $currentDateObj = new DateTime();
            $currentDateString = $currentDateObj->format('Y-m-d H:i:s');
            $status = update_post($mysqli, $update_id, $post['user_id'], $post_title, $category, $post_content, $newFile['name'], $currentDateString);

            if ($status) {
                $success = "Post Updated!";
                $post = get_post_by_id($mysqli,$update_id);
            } else {
                $invalid = "Post Update Fail!";
            }
        }
    }
    elseif (isset($_FILES['attach_file'])) {
        $newFile = $_FILES['attach_file'];
        if ($status) {
            // $category = null;
            if ($newFile['name'] !== '') {

                // delete old file
                if (file_exists($oldFile)) unlink($oldFile);

                $fileName = $newFile['name'];
                $imageExtensions = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($newFile['type'], $imageExtensions)) {

                    $fileDir = "../upload/image/" . $fileName;
                } else {
                    $fileDir = "../upload/file/" . $fileName;
                }
                $oldFile = $fileDir . uniqid()  . $fileName;

                $res = move_uploaded_file($newFile['tmp_name'], $oldFile);

                if (!$res) {
                    $invalid = "File upload failed!";
                }
            }

            $currentDateObj = new DateTime();
            $currentDateString = $currentDateObj->format('Y-m-d H:i:s');

            $status = update_post($mysqli, $update_id, $post['user_id'], $post_title, $category, $post_content, $oldFile, $currentDateString);

            if ($status) {
                $success = "Post Updated!";
                $post = get_post_by_id($mysqli,$update_id);
            } else {
                $invalid = "Post Update Fail!";
            }
        }
    }
     else {
        $currentDateObj = new DateTime();
        $currentDateString = $currentDateObj->format('Y-m-d H:i:s');
        $status = update_post_without_img($mysqli, $update_id, $post['user_id'], $post_title, $post_content, $currentDateString);
        if ($status) {
            $success = "Post Updated!";
            $post = get_post_by_id($mysqli,$update_id);
        } else {
            $invalid = "Post Update Fail!";
        }
    }
}
$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$user_id = $cookie_user['user_id'];

$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();

$user_name = $user['user_name'];
$image = $user['image'];

require_once("../layout/header.php");
require_once("../layout/sidebar2.php");
require_once("../layout/navbar2.php");
?>

<div class="container-fluid">
    <?php
    if ($success) echo '<div class="alert alert-primary">' . $success . '</div>';
    if ($invalid) echo '<div class="alert alert-danger">' . $invalid . '</div>';
    ?>
    <div class="container mb-4 d-flex justify-content-center">
        <div class="row">
            <div class="card" style="width:500px;">
                <div class="card-header bg-primary text-white"></div>
                <form method="post" enctype="multipart/form-data">
                    <div class="p-4">
                        <div class="form-group ">
                            <p class="text-primary">Post Title:</p>
                            <input type="text" name="post_title" class="form-control form-control-user" value="<?php echo $post_title ?>">
                        </div>


                        <div class="form-group">
                            <p class="text-primary">Category :</p>
                            <select id="category" name="category" class="form-control">
                                <option <?php if ($category == "event") echo "selected" ?> value="event" class="form-control form-control-user">Event</option>
                                <option <?php if ($category == "announcement") echo "selected" ?> value="announcement" class="form-control form-control-user">Announcement</option>
                                <option <?php if ($category == "post") echo "selected" ?> value="post" class="form-control form-control-user">Post</option>
                            </select>
                            <small class="text-danger"><?php echo $category_err ?></small>
                        </div>

                        <div class="form-group ">
                            <p class="text-primary">Content:</p>
                            <textarea name="post_content" id="" cols="20" rows="4" class="form-control form-control-user"><?php echo $post_content ?></textarea>
                            <small class="text-danger"><?php echo $post_content_err ?></small>
                        </div>

                        <div class="form-group mb-5">
                            <p class="text-primary">Attach File:</p>
                            <span id="imagefile"> </span>
                            <?php if (!$post['file']) { ?>
                                <input type="file" name="attach_file">
                            <?php } ?>
                            <?php if ($post['file'] != "") { ?>

                                <?php if (strpos($post['file'], 'image/')) : ?>
                                    <div class="alert alert-dark alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" onclick="fileAppear()">&times;</button>
                                        <img src="<?= $post['file'] ?>" style="width: 100%;" alt="" class="img">
                                    </div>
                                <?php else : ?>

                                    <?php
                                    $explodedArrays = explode('/', $post['file']);
                                    $lastString = $explodedArrays[count($explodedArrays) - 1];
                                    ?>
                                    <div class="alert alert-dark alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" onclick="fileAppear()">&times;</button>
                                        <a class="btn btn-secondary" href="<?= $post['file'] ?>" target="_blank"><?= $lastString ?>
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </div>

                                <?php endif; ?>
                            <?php } ?>

                        </div>

                        <div class="form-group">
                            <div class="row d-flex justify-content-between">
                                <div class="col-3">
                                    <a class="btn btn-secondary" onclick="history.go(-1);" role="button">Back</a>
                                </div>
                                <div class="col-2">
                                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function fileAppear() {
            var imageFile = document.getElementById('imagefile');
            imageFile.innerHTML = `<input type="file" name="attach_file">`;
        }
    </script>
    <?php require_once("../layout/footer.php") ?>