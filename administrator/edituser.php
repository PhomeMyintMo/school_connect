<?php
require_once("../storage/db.php");
require_once("../storage/user_db.php");

$user_name=$email=$date_of_birth=$phoneno=$sex=$class=$is_admin="";
$user_name_err=$email_err=$date_of_birth_err=$phoneno_err=$sex_err=$class_err=$is_admin_err="";
$invalid =false;
$success =false;
$status =true;

$cookie_user = null;
if (isset($_COOKIE['user'])) {
    $cookie_user = json_decode($_COOKIE['user'], true);
}
$update_id = null;

if (isset($_GET['update_id'])) {
    $update_id = $_GET['update_id'];
} else {
    header("Location: ./post.php");
}

$users = get_user_by_id($mysqli, $update_id);
$user = $users->fetch_assoc();

$user_name = $user['user_name'];
$email = $user['email'];
$date_of_birth=$user['date_of_birth'];
$phoneno=$user['phoneno'];
$sex=$user['sex'];
$class=$user['class'];
$is_admin = $user['is_admin'];


if(isset($_POST['submit'])){
    $user_name =htmlspecialchars($_POST["user_name"]);
    $email =htmlspecialchars($_POST["email"]);
    $date_of_birth =htmlspecialchars($_POST["date_of_birth"]);
    $phoneno =htmlspecialchars($_POST["phoneno"]);
    $sex=isset($_POST["sex"]) ? htmlspecialchars($_POST["sex"]) : "";
    $class=isset($_POST["class"]) ? htmlspecialchars($_POST["class"]) : "";
    $is_admin=isset($_POST["is_admin"]) ? htmlspecialchars($_POST["is_admin"]) : "";

    if($user_name ===""){
        $status =false;
        $user_name_err = "Fill this blank!";
        }
        
        if($email ===""){
            $status =false;
            $email_err = "Fill this blank!";
            }
        
        if($date_of_birth ===""){
        $status =false;
        $date_of_birth_err = "Fill this blank!";
        }
        
        if ($phoneno !== "" && preg_match('/^\d{8,11}$/', $phoneno)) {
            $status = true;
        }else{
            $status = false;
            $phoneno_err = "Phone Number must be between 8 to 11 digits";
        }
        if($sex ===""){
            $status =false;
            $sex_err = "Choose one of them!";
            }
        if($class ===""){
            $status =false;
            $class_err = "Select the class!";
            }
        if($is_admin ===""){
            $status=false;
            $role_err="Select the user role!";
        }
        
        if($status){
            $status = update_user_by_admin($mysqli, $user['user_id'],$user_name,$email, $date_of_birth, $phoneno,$sex,$class,$is_admin);
            if($status){
                $success=true;
                $invalid=false;
            }
            else{
                $invalid=true;
            }
        }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Edit</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
			font-family: 'Nunito';
			background-image: linear-gradient(to right, #0f6af2, #0fd0f2);
		}
        .popup {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 9; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 300px; 
        }
        .navbar {
			padding: 25px;
		}

		.logo {
			font-weight: 700;
			color: white;
			font-size: 23px;
		}
        .crd{
  border-style: solid;
  border-width: 3px;
  border-radius: 20px;
        }
       .crdhead{
        background-image: linear-gradient(to right, #0f6af2, #0fd0f2);

       }
       .bbtt1{
        background-image: linear-gradient(to bottom right, #0fd0f2, #ababff);
        font-weight: 600;
        color: white;
       }
       .bbtt2{
        background-image: linear-gradient(to bottom right, #b3b1b1, #b3b1b1);
        font-weight: 600;
        color: white;
       }
       .footer2{
			text-align: center;
			color: #c2c3c4;
		}
        label{
            font-weight: 700;
        }
        .bbtt3{
        background-image: linear-gradient(to bottom right, #ababff,#0fd0f2);
        font-weight: 600;
        color: white;
        border: #0f6af2;
        border: 2px solid;
       }
       
    </style>
</head>


<body>
<nav class="navbar navbar-expand-lg">
		<div class="container">
			<span class="navbar-brand logo">SchoolConnect.</span>
		</div>
</nav>
    <div class="container">
        <div class="mb-3" style="margin-left: 300px;">
    <img src="../assets/img/profiles1.png" style="width: 50px;" class="img1">&nbsp;
    <img src="../assets/img/profiles2.png" style="width: 60px;margin-top:20px;" class="img2">&nbsp;
    <img src="../assets/img/profiles3.png" style="width: 70px;" class="img3">&nbsp;
    <img src="../assets/img/profiles1.png" style="width: 90px;margin-top:20px;" class="img4">&nbsp;
    <img src="../assets/img/profiles4.png" style="width: 70px;" class="img5">&nbsp;
    <img src="../assets/img/profiles5.png" style="width: 60px;margin-top:20px;" class="img6">&nbsp;
    <img src="../assets/img/profiles6.png" style="width: 50px;" class="img7">
    </div>
<div style="width: 300px;margin-left:400px;">
                <?php
                if($success) echo '<div class="alert alert-success">User Information Updated!</div>';
                if($invalid) echo '<div class="alert alert-danger">Error Updating!</div>';
                ?>
</div>                
    <div class="row d-flex justify-content-center">
        <div class="card mb-3 crd" style="width: 700px;">
            <div class="card-body">
                    <div>
                        <div class="p-4">
                            <!-- <div class="card-header mb-3 text-white crdhead" style="width: 100%;height:50px;border-radius:50px;">
                                <h4 class="text-center fw-bold">Edit REGISTERATION</h4>
                            </div> -->
                            <form class="user" method="post">
 
                                    <div class="form-group">
                                        <label>Full Name:</label>
                                        <input type="text" class="form-control form-control-user" 
                                             name="user_name" value="<?php echo $user_name?>">
                                            <small class="text-danger"><?php echo $user_name_err?></small>
                                        </div>
                                    <div class="form-group ">
                                    <label>Email Address:</label>
                                    <input type="email" class="form-control form-control-user"
                                        name="email" value="<?php echo $email?>">
                                        <small class="text-danger"><?php echo $email_err?></small>
                                    </div>
                                    <div class="form-group ">
                                    <label>Date-of-Birth:</label>
                                        <input type="date" class="form-control form-control-user"
                                        name="date_of_birth"  value="<?php echo $date_of_birth?>">
                                        <small class="text-danger"><?php echo $date_of_birth_err?></small>
                                    </div>
                                    
                                    <div class="form-group ">
                                    <label>Phone Number:</label>
                                        <input type="tel" class="form-control form-control-user"
                                        placeholder="09xxxxxxxxx" name="phoneno"  value="<?php echo $phoneno?>">
                                        <small class="text-danger"><?php echo $phoneno_err?></small>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-2">Sex:</label>
                                        <input type="radio" class="" value="Male" class="form-control form-control-user" name="sex"  <?php if ($sex == 'Male') echo 'checked' ?>>Male
                                          &nbsp;&nbsp;
                                        <input type="radio" class="" value="Female" class="form-control form-control-user" name="sex"  <?php if ($sex == 'Female') echo 'checked' ?>>Female<br>
                                        <small class="text-danger"><?php echo $sex_err?></small>
                                    </div>
                                    <div class="form-group">
                                        <label>Choose the Class:</label>
                                    <select id="class" name="class" class="form-control" style="border-radius: 50px;">
                                    <option class="form-control" disabled selected>Select</option>
                                       <option class="form-control form-control-user" value="A-01" <?php if ($class == 'A-01') echo 'selected' ?>>A-01</option>
                                       <option class="form-control form-control-user" value="A-02" <?php if ($class == 'A-02') echo 'selected' ?>>A-02</option>
                                       <option class="form-control form-control-user" value="B-01" <?php if ($class == 'B-01') echo 'selected' ?>>B-01</option>
                                       <option class="form-control form-control-user" value="B-02" <?php if ($class == 'B-02') echo 'selected' ?>>B-02</option>
                                    </select>
                                    <small class="text-danger"><?php echo $class_err?></small>
                                    </div>
                                    <div class="form-group ">
                                    <label>Select User Role:</label>
                                    <select id="role" name="is_admin" class="form-control" style="border-radius: 50px;">
                                        <option class="form-control" disabled selected>Select</option>
                                        <option value=0 class="form-control form-control-user"  <?php if ($is_admin == 0) echo 'selected' ?>>User</option>
                                        <option value=1 class="form-control form-control-user"  <?php if ($is_admin == 1) echo 'selected' ?>>Admin</option>
                                    </select>
                                    <small class="text-danger"><?php echo $is_admin_err?></small>
                                    </div>
                                    <br>
                                    <div class="form-group row justify-content-between">

                                    <div class="col-3 ms-5">
                                    <a class="btn btn-user btn-block bbtt2" onclick="history.go(-1);" role="button">Back</a>
                                    </div>
                                    
                                    <div class="col-3 me-5">
                                    <button class="btn btn-user btn-block bbtt1" name='submit' type='submit'>
                                    Update
                                    </button>
                                    </div>
                                
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-3 text-center mt-3 d-flex justify-content-center">
        <a href="../administrator/registeredusers.php" class="btn  btn-user btn-block bbtt3" style="width: 200px;">Registered Users&nbsp;&nbsp;<i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

<footer class="footer2">
		<br><br><br><br>
    <p class="">Copyright &copy; SchoolConnect 2024
	</p>
	</footer>
</body>

</html>