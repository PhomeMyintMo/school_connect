<?php
require_once("../storage/auth_user.php");
require_once("../storage/db.php");
require_once("../storage/user_db.php");
require_once("../storage/post_db.php");

$email = $date_of_birth = $phoneno = $sex = "";
$email_err = $date_of_birth_err = $phoneno_err = $sex_err = $success = $invalid = $success1 = $invalid1 = "";
$status = true;

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];
if (isset($_GET["success1"])) $success1 = $_GET["success1"];
if (isset($_GET["invalid1"])) $invalid1 = $_GET["invalid1"];

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$user_id = $cookie_user['user_id'];

$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();

$user_name = $user['user_name'];
$email = $user['email'];
$date_of_birth = $user['date_of_birth'];
$phoneno = $user['phoneno'];
$sex = $user['sex'];
$class = $user['class'];
$image = $user['image'];

require_once("../layout/headerplain.php");

if ($image === null) {
    $image = "../assets/img/profilepic.webp";
}

if (isset($_POST['upload'])) {
    $profile_img_name = $_FILES["profile"]["name"];
    $profile_img_file = $_FILES["profile"]["tmp_name"];
    if (move_uploaded_file($profile_img_file, "../upload/" . $profile_img_name)) {
        $status = update_profile($mysqli, "../upload/" . $profile_img_name, $user_id);
        if ($status) {
            $success1 = "Picture Uploaded";
            header("Location:../user/accountSetting.php?success1=$success1");
        } else {
            $invalid1 = "Picture Uploading Error";
        }
    }
}

if (isset($_GET["delete_id"])) {
    if (file_exists($image)) {
        update_user_img($mysqli,$user_id);
        $user['image'] = "../assets/img/profilepic.webp";
        setcookie("user", json_encode($user), time() + 3600 * 24 * 7 * 2, '/');
        header("Location:../user/accountSetting.php?success1=Image delete successful!");
    } 
}


if (isset($_GET["update_id"])) {
    $user_id = $_GET["update_id"];
    $user = get_user_by_id($mysqli, $user_id);

    if (isset($_POST['submit'])) {
        $email = htmlspecialchars($_POST["email"]);
        $date_of_birth = htmlspecialchars($_POST["date_of_birth"]);
        $phoneno = htmlspecialchars($_POST["phoneno"]);

        if ($email === "") {
            $status = false;
            $email_err = "** Fill this blank!";
        }
        if ($date_of_birth === "") {
            $status = false;
            $date_of_birth_err = "**Fill this blank!";
        }
        if ($phoneno !== "" && preg_match('/^\d{8,11}$/', $phoneno)) {
            $status = true;
        } else {
            $status = false;
            $phoneno_err = " ** Phone Number must be between 8 to 11 digits";
        }

        if ($status) {
            $result = update_user($mysqli, $user_id, $email, $date_of_birth, $phoneno);
            if ($result) {
                $success = "User Information Updated!";
                header("Location:../user/accountSetting.php?success=$success");
                exit();
            } else {
                $invalid = "Update Error!";
                header("Location:../user/accountSetting.php?invalid=$invalid");
                exit();
            }
        }
    }
}


?>
<div class="container d-flex justify-content-center">
    <div class="card my-5 p-4" style="width: 800px;">
        <nav class="navbar navbar-expand-lg">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="accountSetting.php">Account Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="privacy_setting.php">Privacy Settings</a>
                </li>
            </ul>
        </nav>

        <!-- Image profile -->
        <div class="container">

            <div class="p-4 mb-3">
                <?php if ($success1 !== "") { ?>
                    <div class="alert alert-success"><?php echo $success1 ?></div>
                <?php } ?>
                <?php if ($invalid1 !== "") { ?>
                    <div class="alert alert-danger"><?php echo $invalid1 ?></div>
                <?php } ?>
                <div class="card" style="width:300px; height: 320px;">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-header  bg-primary text-white">Profile Picture</div>

                        <input type="file" name="profile" accept="image/jpeg, image/png, image/jpg" id="input-file" class="d-none">

                        <div class="d-flex justify-content-center p-3" id="profile">
                            <img class="circular" style="width: 180px; height:180px;" src="<?php
                                                                                            if ($image === null) {
                                                                                                echo '../assets/img/profilepic.webp';
                                                                                            } else {
                                                                                                echo $image;
                                                                                            }
                                                                                            ?>" id="profile-pic">
                        </div>

                        <div class=" d-flex justify-content-between">
                            <div class="col" style="margin-left: 30px;">
                                <button type="submit" name="upload" class="btn btn-outline-primary">Upload</button>
                            </div>
                            <div class="col">
                                <a href="../user/accountSetting.php?delete_id=<?php echo $user_id ?>" class="btn btn-outline-primary text-danger">Remove</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <?php
            $disabled = null;
            if (!isset($_GET['update_id'])) {
                $disabled = 'disabled';
            } ?>
            <div class="container">
                <?php if ($success !== "") { ?>
                    <div class="alert alert-success"><?php echo $success ?></div>
                <?php } ?>
                <?php if ($invalid !== "") { ?>
                    <div class="alert alert-danger"><?php echo $invalid ?></div>
                <?php } ?>
                <form class="user" method="post">
                    <div class="row">
                        <div class="col">
                            <div class="form-group ">
                                <p class="labl">Full Name:</p>
                                <input type="text" class="form-control form-control-user" value="<?php echo $user_name ?>" disabled>
                            </div>
                            <div class="form-group ">
                                <p class="labl">Email Address:</p>
                                <input type="email" class="form-control form-control-user" value="<?php echo $email ?>" <?php echo $disabled ?> name="email">
                                <small class="text-danger"><?php echo $email_err ?></small>
                            </div>
                            <div class="form-group ">
                                <p class="labl">Date-of-Birth:</p>
                                <input type="date" class="form-control form-control-user" value="<?php echo $date_of_birth ?>" <?php echo $disabled ?> name="date_of_birth">
                                <small class="text-danger"><?php echo $date_of_birth_err ?></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group ">
                                <p class="labl">Phone Number:</p>
                                <input type="text" class="form-control form-control-user" value="<?php echo $phoneno ?>" <?php echo $disabled ?> name="phoneno">
                                <small class="text-danger"><?php echo $phoneno_err ?></small>
                            </div>
                            <div class="form-group ">
                                <p class="labl">Sex:</p>
                                <input type="text" class="form-control form-control-user" value="<?php echo $sex ?>" disabled>
                                <small class="text-danger"><?php echo $sex_err ?></small>
                            </div>
                            <div class="form-group ">
                                <p class="labl">Class:</p>
                                <input type="text" class="form-control form-control-user" value="<?php echo $class ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <a class="btn btn-outline-primary border-1" onclick="history.go(-1);">
                                Back</a>
                        </div>
                        <div class="col">
                            <?php if (isset($_GET['update_id'])) { ?>
                                <button class="btn btn-outline-primary border-1" name="submit" type="submit">Save
                                    Changes</button>
                                <a type="button" class="btn btn-outline-primary border-1" href="../user/accountSetting.php">Cancel</a>
                            <?php } else { ?>
                                <a class="btn btn-outline-primary border-1" href="../user/accountSetting.php?update_id=<?php echo $user_id ?>" role="button">Edit Profile</a>
                            <?php } ?>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <?php require_once("../layout/footerplain.php"); ?>