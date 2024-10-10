<?php
require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/post_db.php");
require_once("../storage/user_db.php");

if ($user['is_admin']) {
    header("Location:../layout/error.php");
}

if (isset($_POST['logout'])) {
    setcookie('user', '', time() - 3600, '/');

    header('Location: ../auth/login.php');
}

require_once("../layout/header.php");
require_once("../layout/sidebar.php");
require_once("../layout/navbar.php");

$post_title = $post_content = $file = "";
$post_content_err = "";
$success = $invalid = "";
$status = true;

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];

if (isset($_POST['submit'])) {
    $post_title = htmlspecialchars($_POST["post_title"]);
    $post_content = htmlspecialchars($_POST["post_content"]);
    $file = $_FILES['attach_file'];

    if ($post_content === "") {
        $status = false;
        $post_content_err = "Content is required!";
    }

    if ($status) {
        $fileDir = null;
        $category=null;
        if($file['name'] !== '') {
            $fileName = $file['name'];
            $imageExtensions = ['image/jpeg', 'image/png', 'image/gif'];
            if(in_array($file['type'], $imageExtensions)) {
                
                $fileDir = "../upload/image/" . $fileName;
            }else {
                $fileDir = "../upload/file/" . $fileName;
            }

            $res = move_uploaded_file($file['tmp_name'], $fileDir);

            if(!$res) {
                var_dump("Some image upload error"); die();
            }
        }

        $status = save_post($mysqli, $user_id, $post_title, $category, $post_content, $fileDir);

        if ($status) {
            $success = true;
            $invalid = false;
        } else {
            $invalid = true;
        }
    }
}
?>

<div class="container-fluid">
<?php
        if ($success) echo '<div class="alert alert-primary">Post Created!</div>';
        if ($invalid) echo '<div class="alert alert-danger">Something went wrong!</div>';
        ?>
    <div class="container mb-4 d-flex justify-content-center">
        
        <div class="row">
            <div class="card" style="width:500px;">
                <div class="card-header bg-primary text-white">Add Post</div>
                <form method="post" enctype="multipart/form-data">
                    <div class="p-4">
                        <div class="form-group ">
                            <p class="text-primary">Post Title:</p>
                            <input type="text" name="post_title" class="form-control form-control-user">
                        </div>

                        <div class="form-group ">
                            <p class="text-primary">Content:</p>
                            <textarea name="post_content" id="" cols="20" rows="4" class="form-control form-control-user"></textarea>
                            <small class="text-danger"><?php echo $post_content_err?></small>
                        </div>

                        <div class="form-group">
                            <p class="text-primary">Attach File:</p>
                            <input type="file" name="attach_file" value="<?php echo $file?>">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-primary" style="margin-left: 87%;">Post</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php require_once("../layout/footer.php") ?>