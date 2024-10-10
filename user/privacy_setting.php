<?php
require_once("../layout/headerplain.php");


$password = $new_password = $con_password = "";
$password_err = $new_password_err = $con_password_err = $invalid = $success = "";
$status = true;

if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];



if (isset($_POST['submit'])) {
    $password = htmlspecialchars($_POST["password"]);
    $new_password = htmlspecialchars($_POST["new_password"]);
    $con_password = htmlspecialchars($_POST["con_password"]);

    if ($password === "") {
        $status = false;
        $password_err = "Fill this blank!";
    }

    if ($new_password === "") {
        $status = false;
        $new_password_err = "Fill this blank!";
    }

    if ($con_password === "") {
        $status = false;
        $con_password_err = "Fill this blank!";
    }

    $cookie_user = null;
    if (isset($_COOKIE['user'])) {
        $cookie_user = json_decode($_COOKIE['user'], true);
    }
    $user_id = $cookie_user['user_id'];
    $users = get_user_by_id($mysqli, $cookie_user['user_id']);
    $user = $users->fetch_assoc();

    if ($status) {
        if ($user && password_verify($password, $user['password'])) {
            if ($new_password === $con_password) {
                $result = change_password_by_id($mysqli, $user_id, $new_password);
                var_dump($result);
                if ($result) {
                    $success = "Your password is changed!";
                    // header("Location:./privacy_setting.php");
                }
            } else {
                $invalid = "Password doesn't match!";
            }
        } else {
            $invalid = "Current password incorrect";
        }
    } 
}



?>
<?php if ($success !== "") { ?>
    <div class="alert alert-success"><?php echo $success ?></div>
<?php } ?>
<?php if ($invalid !== "") { ?>
    <div class="alert alert-danger"><?php echo $invalid ?></div>
<?php } ?>

<div class="container d-flex justify-content-center">

<!-- Outer Row -->
<!-- <div class="row justify-content-center">
    <div class="col-xl-7 col-lg-12"> -->
<div class="card my-5 p-4" style="width: 800px;">
    <!-- <div class="card-body p-4"> -->
    <nav class="navbar navbar-expand-lg">
        <ul class="navbar-nav ms-auto">
            <!-- <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="accountSetting.php">Account Setting</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="privacy_setting.php">Privacy Settings</a>
            </li>
        </ul>
    </nav>
<!-- Nested Row within Card Body -->
<div class="row">
    <div class="col-lg-2 d-lg-block"></div>
    <div class="col-lg-8">
        <div class="p-5">
            <form class="user" method="post">

                <div class="form-group">
                    <p class="labl">Enter your Password:</p>
                    <input type="password" class="form-control form-control-user" placeholder="Enter your current password" name="password">
                    <small class="text-danger"><?php echo $password_err ?></small>
                </div>

                <div class="form-group ">
                    <p class="labl">New Password:</p>
                    <input type="password" class="form-control form-control-user" placeholder="New password" name="new_password">
                    <small class="text-danger"><?php echo $new_password_err ?></small>
                </div>

                <div class="form-group ">
                    <p class="labl">Confirm Password:</p>
                    <input type="password" class="form-control form-control-user" placeholder="Confirm password" name="con_password">
                    <small class="text-danger"><?php echo $con_password_err ?></small>
                </div>
                <br>



                <div class="row justify-content-between">
                    <div class="col">
                        <a class="btn btn-outline-primary border-1" onclick="history.go(-1);">
                            Back</a>
                    </div>
                    <div class="col">
                        <button class="btn btn-outline-primary border-1" name="submit" type="submit">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

</div>

</div>

</div>


<?php require_once("../layout/footerplain.php"); ?>