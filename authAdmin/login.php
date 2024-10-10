<?php
require_once('../storage/db.php');
require_once("../storage/user_db.php");
require_once("../storage/admin_db.php");

$admin_name = $password = "";
$admin_name_err = $password_err =$success=$invalid= "";
$status = true;

if (isset($_POST['submit'])) {
    $admin_name = htmlspecialchars($_POST["admin_name"]);
    $password = htmlspecialchars($_POST["password"]);

    if ($admin_name === "") {
        $status = false;
        $admin_name_err = "Email must not be blank!";
    }

    if ($password === "") {
        $status = false;
        $password_err = "Password must not be blank!";
    }

    if ($status) {
        $admin=get_admin_by_name($mysqli,$admin_name);
        if($admin){
            $match = password_verify($password, $admin['password']);
            setcookie("user", json_encode($admin), time() + 3600 * 24 * 7 * 2, '/');
            if ($match) {
                header("location:../auth/register.php");
            }else{
                $invalid = "Your password is incorrect!";
            }
        }else{
            $invalid="Your name is incorrect!";
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

    <title>Admin Login</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <style>
        body {
			font-family: 'Nunito';
			background-image: linear-gradient(to right, #0f6af2, #0fd0f2);

		}
        .navbar {
			padding: 25px;
		}

		.logo {
			font-weight: 700;
			color: white;
			font-size: 23px;
		}
        .homebutton {
			background-image: linear-gradient(to bottom right, #0fd0f2, #ababff);
            font-weight: 600;
		}
        h4,h3{
            font-weight: 700;
        }
        .footer2{
			text-align: center;
			color: #c2c3c4;
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
        <!-- <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9"> -->
                    <div>
                        <?php
                        if ($invalid) echo "<div class='alert alert-danger'>$invalid!</div>";
                        ?>

                        <div class="row d-flex justify-content-center">
                            <!-- <div class="col-4">
                            <img src="../assets/img/profiles3.png" style="width: 200px;">
                            </div> -->
                            <div class="col-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h3 class="mb-4 text-white">Welcome Admin!</h3>
                                        <h4 class="mb-4 text-white">Login</h4>
                                    </div>
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="admin_name" placeholder="Enter your name" value="<?php echo $admin_name?>">
                                            <small class="text-danger"><?php echo $admin_name_err?></small>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password" value="<?php echo $password?>">
                                            <small class="text-danger"><?php echo $password_err?></small>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button class="homebutton btn btn-outline-primary border" style="border-radius: 20px;padding:8px 20px 8px  20px;" name="submit" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- </div> -->

            </div>

        </div>

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