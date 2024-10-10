<?php
require_once("../storage/db.php");
require_once("../storage/user_db.php");

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$user_id = 1;

$users = get_user_by_id($mysqli, $user_id);
$user = $users->fetch_assoc();

$user_name = $user['user_name'];
$email = $user['email'];
$date_of_birth=$user['date_of_birth'];
$phoneno=$user['phoneno'];
$sex=$user['sex'];
$class=$user['class'];

$success = $invalid = "";
if (isset($_GET["success"])) $success = $_GET["success"];
if (isset($_GET["invalid"])) $invalid = $_GET["invalid"];

if(isset($_GET["delete_id"])){
    $user_id = $_GET["delete_id"];
    $status = delete_user($mysqli,$user_id);
    if($status){
        $success = "User is Deleted!";
        header("Location:../administrator/registeredusers.php?success=$success");
    }else{
        $invalid = "Error Deleting!";
        header("Location:../administrator/registeredusers.php?invalid=$invalid");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    body {
			font-family: 'Nunito';
			background-image: linear-gradient(to right, #0f6af2, #0fd0f2);
		}
    th{
        background-color: #0f6af2;
    }
    .card-header{
        background-color: #b1b1b3;
        font-weight: 700;
    }
    table,tr,td,th{
        border: 1px solid;
    }
    th,td{
        text-align: center;
    }
</style>
<body>
    <div class="p-4" style="width: 300px;">
    <?php if ($success !== "") { ?>
        <div class="alert alert-success"><?php echo $success ?></div>
    <?php } ?>
    <?php if ($invalid !== "") { ?>
        <div class="alert alert-danger"><?php echo $invalid ?></div>
    <?php } ?>
    </div>
    <div class="container p-4 mb-4 d-flex justify-content-center">
        <div class="card" style="width:100%;">
        <div class="card-header text-white text-center fs-5">Users List</div>
        <div class="p-2">
        <div class="table">
            <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Date of Birth</th>
                                    <th>Phone No.</th>
                                    <th>Sex</th>
                                    <th>Class</th>
                                    <th>Edit/Delete</th>

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
                                    <td><?php echo $user['date_of_birth'] ?></td>
                                    <td><?php echo $user['phoneno'] ?></td>
                                    <td><?php echo $user['sex'] ?></td>
                                    <td><?php echo $user['class'] ?></td>
                                    <td>
                                        <a href="../administrator/edituser.php?update_id=<?php echo $user['user_id']?>" class="btn border border-black" style="background-color: #b1b1b3;">
                                        <i class="fa-solid fa-pen"></i>
                                        Edit</a>&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-danger" href="registeredusers.php?delete_id=<?php echo $user['user_id']?>">
                                        <i class="fa-solid fa-trash-can"></i>    
                                        Delete</a>
                                    </td>

                                </tr>
                                <?php
                        $i++;
                    } ?>
                            </tbody>
            </table>
        </div>
        </div>
        </div>
    </div>
</body>
</html>