<?php
require_once("../layout/headerplain.php");

session_start();
$email = $date_of_birth = $phoneno = $sex = $password = "";
$email_err = $date_of_birth_err = $phoneno_err = $sex_err = $password_err= $success = $invalid= $invalid1 = "";
$status = true;
$success1=false;

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
$date_of_birth = $user['date_of_birth'];
$phoneno = $user['phoneno'];
$sex = $user['sex'];
$class = $user['class'];
$image = $user['image'];

if (isset($_GET["update_id"])) {
    $user_id = $_GET["update_id"];
    $user = get_user_by_id($mysqli, $user_id);

    if (isset($_POST["submit"])) {
        $email = htmlspecialchars($_POST["email"]);
        $date_of_birth = htmlspecialchars($_POST["date_of_birth"]);
        $phoneno = htmlspecialchars($_POST["phoneno"]);
        $password = htmlspecialchars($_POST["password"]);

        if ($password === "") {
            $status = false;
            $password_err = "Fill this blank!";
        }

        if($status){
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        if (password_verify($password, $cookie_user['password'])) {
            $result = update_user($mysqli, $user_id, $user_name, $email, $date_of_birth, $sex, $phoneno, $class, $hash_password);
            if ($result) {
                $success = "Updated!";
                header("Location:../user/accountSetting.php?success=$success");
                exit();
            } else {
                $invalid = "Update Error!";
                header("Location:../user/accountSetting.php?invalid=$invalid");
                exit();
            }
        } else{
            $invalid1="password incorrect!";
        }
    }
}
}
?>
<div class="container d-flex justify-content-center">
     <div class="card my-5 p-4" style="width: 500px;">
       

<div class="container">
        <form class="user" method="post">
            <div class="form-group">
                <p class="labl">Enter Your Password:</p>
                    <input type="password" class="form-control form-control-user" name="password" id="password">
                        <small class="text-danger" id="password_err" ><?php echo $password_err ?></small>
            </div>
            
            <div class="form-group d-flex justify-content-center">
                <button value="submit" name="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
        <a class="btn btn-outline-primary border-1" onclick="history.go(-1);">
                    Back
        </a>
</div>


<script>
</script>

<?php require_once("../layout/footerplain.php"); ?>