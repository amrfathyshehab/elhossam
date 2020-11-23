<?php

include('include/config.php ');


session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {


	$_SESSION['error'] = '';

	$username = $_POST['username'];
	$password = $_POST['password'];
	$stage = $_POST['stage'];


	$stmt = $conn->prepare('SELECT * FROM `student` where username=? AND password=? AND stage=?');
	$stmt->execute(array($username, $password, $stage));
	$student = $stmt->fetch();

	$row = $stmt->rowCount();

	if ($row > 0) {
		// token feature 
		// Generate token
		function getToken($length){
			$token = "";
			$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
			$codeAlphabet.= "0123456789";
			$max = strlen($codeAlphabet); // edited
		
			for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[random_int(0, $max-1)];
			}
		
			return $token;
		}

		$token = getToken(10);
		// Update student token 
		$result_token = $conn->prepare('SELECT count(*) as allcount from student_token where student_name= ?');
		$result_token->execute(array($student['student_name']));
		$row_token = $result_token->fetch();
		
		if($row_token['allcount'] > 0){
			session_destroy();
			echo "<h1> a student with your credentials is already logged in </h1>" ; 
			header('Refresh:3; url=login.php');
			exit ; 

			//kick out the already logged in student approach
			// $stmt = $conn->prepare('UPDATE student_token set token= ? where student_name= ? ');
			// $stmt->execute(array($token , $student['student_name']));
		}else{
			

			

			$stmt = $conn->prepare('SET GLOBAL event_scheduler = ON;');
			$stmt->execute();

			$stmt = $conn->prepare('CREATE EVENT IF NOT EXISTS `login_expires` 
			ON SCHEDULE EVERY 3 MINUTE
			STARTS CURRENT_TIMESTAMP
			ON COMPLETION PRESERVE 
			
			DO 
			
			Delete from  student_token where student_name = ? 
			
			');
			$stmt->execute(array($student['student_name']));

			$stmt = $conn->prepare('INSERT into student_token(student_name,token) values (?,?)');
			$stmt->execute(array($student['student_name'], $token));
			
		}
	
		$_SESSION['token'] = $token;
		// setting the session 
		$_SESSION['student_name'] = $student['student_name'];
		$_SESSION['student_id']	   = $student['student_id'];
		$_SESSION['student_username']	  = $student['username'];
		$_SESSION['center'] = $student['center'];
		$_SESSION['is_online'] = 1;
		if (isset($_POST['center'])) {

			$is_online = 0;
			$_SESSION['is_online'] = 0;
		}



		if (isset($_POST['center'])) {
			$stmt = $conn->prepare('UPDATE `student` SET `is_online`=? WHERE `student_id`=? ');
			$stmt->execute(array($is_online, $student['student_id']));
		}


		header('Location:dashboard.php');
	} else {

		$_SESSION['error'] = 'username or password is incorrect ';
	}
}

?>
<html lang="en">

<head>
	<title>Student | login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-l-85 p-r-85 p-t-55 p-b-55">
				<form class="login100-form validate-form flex-sb flex-w" method="post">
					<span class="login100-form-title p-b-32">
						Student Login
					</span>

					<span class="txt1 p-b-11">
						Username
					</span>
					<div class="wrap-input100 validate-input m-b-36" data-validate="Username is required">
						<input class="input100" type="text" name="username">
						<span class="focus-input100"></span>
					</div>

					<span class="txt1 p-b-11">
						Password
					</span>
					<div class="wrap-input100 validate-input m-b-12" data-validate="Password is required">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
						<input class="input100" type="password" name="password">

						<span class="focus-input100"></span>
					</div>
					<span style="color:red"><?php if (isset($_SESSION['error'])) {
																		echo $_SESSION['error'];
																	} else {
																		echo $_SESSION['error'] = '';
																	} ?></span>
					<div class="flex-sb-m w-full p-b-48">
						<div class="contact100-form-checkbox">


						</div>

						<!-- <span class="txt1 p-b-11">
							Stage
						</span>
						<div class="wrap-input100  m-b-12" ">
						<span class=" btn-show-pass">
							<i class="fa fa-eye"></i>
							</span>
							<input class="input100" type="password" name="password">

							<span class="focus-input100"></span>
						</div> -->

						<div class="txt1 p-b-11">
							Stage
						</div>

						<!-- <div class="container"></div> -->
						<div class="container  m-b-12">
							<!-- <div class=" form-group col-md-3"> -->

							<select class="form-control " name="stage" required>
								<!-- <option value="" selected disabled> student stage </option> -->
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>

							</select>
							<span class="focus-input100"></span>
						</div>
						<!-- </div> -->
						<br>
						<br>
						<br>

						<!-- // -->


						<div style="position:relative ;top :20px;" class="wrap-input100  m-b-36">
							<!-- <span>CENTER</span> <input class="input100" type="radio" name="username">
							ONILNE <input class="input100" type="radio" name="username"> -->
							<div class="checkbox">
								<label style="padding: 15px;" class="txt1 p-b-11 active"><input type="checkbox" name="center" autocomplete="off"> Login as a CENTER student</label>
							</div>


						</div>


						<br>
						<br>
						<br>

						<div>
							<a style="position:relative ;left :20px;" href="#" class="txt3">
								Forgot Password?
							</a>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

	<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>

</html>














<?php
/*    }
       }else{
        
        ?>
        <div class="container"><center>
        <img src="images/exam.svg" style="width:400px"><br><br>
        <h5 style="color:gray">Study , because you still have a chance.</h5>
        </center>
        </div>
        
        <?php
      }
*/ ?>