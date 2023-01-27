<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<title>Last Chance MMORPG</title>
		<!-- jQuery + Bootstrap JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	</head>

	<body>
		<?php 
		include('./controllers/login.php');
		include_once './controllers/language.php';		
		?>

		<div class="languagebar">
			<a href="?lang=en"><img src="images/en.png" title="English"/></a>
			<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
		</div>
		
		<div class="vertical-center">

			<form action="" method="post">
				<?php echo $accountNotExistErr; ?>
				<?php echo $emailPwdErr; ?>
				<?php echo $verificationRequiredErr; ?>
				<?php echo $email_empty_err; ?>
				<?php echo $pass_empty_err; ?>

				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control" name="email_signin" id="email_signin" />
				</div>

				<div class="form-group">
					<label><?php echo $lang['PASSWORD']; ?></label>
					<input type="password" class="form-control" name="password_signin" id="password_signin" />
				</div>

				<button type="submit" name="login" id="sign_in" class="btn btn-outline-primary btn-lg btn-block">Login</button>
			</form>
			<br/>
			<a class="btn btn-outline-primary btn-lg btn-block" href="signup.php"><?php echo $lang['REGISTER']; ?></a>

		</div>

	</body>

</html>