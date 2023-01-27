<?php 
include('./controllers/register.php');
include_once './controllers/language.php';	
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Last Chance MMORPG</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="languagebar">
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>
   
	<div class="vertical-center">
		<form action="" method="post">
			<?php echo $success_msg; ?>
			<?php echo $email_exist; ?>

			<?php echo $email_verify_err; ?>
			<?php echo $email_verify_success; ?>

			<div class="form-group">
				<label><?php echo $lang['NAME']; ?></label>
				<input type="text" class="form-control" name="playername" id="playerName" />

				<?php echo $fNameEmptyErr; ?>
				<?php echo $f_NameErr; ?>
			</div>

			<div class="form-group">
				<label>Email</label>
				<input type="email" class="form-control" name="email" id="email" />

				<?php echo $_emailErr; ?>
				<?php echo $emailEmptyErr; ?>
			</div>

			<div class="form-group">
				<label><?php echo $lang['PASSWORD']; ?></label>
				<input type="password" class="form-control" name="password" id="password" />

				<?php echo $_passwordErr; ?>
				<?php echo $passwordEmptyErr; ?>
			</div>

			<button type="submit" name="submit" id="submit" class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['REGISTER']; ?></button>
		</form>
		<br/>
		<a class="btn btn-outline-primary btn-lg btn-block" href="index.php"><?php echo $lang['BACK_TO_LOGIN']; ?></a>
	</div>

</body>

</html>