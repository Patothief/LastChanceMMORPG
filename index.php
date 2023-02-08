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
		include 'login.php';
		include_once 'language.php';		
		?>

		<div class="topbar">
			<a href="?lang=en"><img src="images/en.png" title="English"/></a>
			<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
		</div>
		
		<br/>
		
		<div class="vertical-center">

			<form action="" method="post">
				<div class="form-group">
					<label><?php echo $lang['PLAYERNAME']; ?></label>
					<input type="playername" class="form-control" name="playername_signin" />
					<?php echo $playernameErr; ?>
				</div>

				<div class="form-group">
					<label><?php echo $lang['PASSWORD']; ?></label>
					<input type="password" class="form-control" name="password_signin" />
					<?php echo $passwordErr; ?>					
				</div>

				<button type="submit" name="login" class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['LOGIN/REGISTER']; ?></button>
			</form>

		</div>

	</body>

</html>