<?php 
include 'config/db.php';
include_once 'language.php';
?>

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
	<?php unset($_SESSION['message']); ?>
	
	<div class="topbar">
		<label><?php echo $_SESSION['playername']; ?></label>	
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

	<label class="pageLabel"><?php echo $lang['WILDERNESS']; ?></label>
	
    <div class="vertical-center">
		<p class="card-text"><?php echo $lang['ACTIONS']; ?>
			<label id="labelActions"><?php echo $_SESSION['actions'] ?></label>
		</p>
		
		<br/>

		<p class="card-text"><?php echo $lang['LABEL_HEALTH']; ?>
			<label id="labelHealth"><?php echo $_SESSION['health'] ?></label>
		</p>
		
		<br/>
		
		<p class="card-text"><?php echo $lang['FUEL']; ?>
			<label id="labelFuel"><?php echo $_SESSION['fuel'] ?></label>
		</p>

		<br/>
		
		<p class="card-text"><?php echo $lang['FOOD']; ?>
			<label id="labelFood"><?php echo $_SESSION['food'] ?></label>
		</p>

		<br/>
		
		<p class="card-text"><?php echo $lang['METALS']; ?>
			<label id="labelMetal"><?php echo $_SESSION['metal'] ?></label>
		</p>
		
		<br/><br/>

		<button onClick = 'search()' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEARCH']; ?></button> 

		<br/>

		<label id="labelMessage"><?php if (isset($_SESSION['message'])) echo $_SESSION['message']; ?></label>
	</div>

	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="shelter.php"><?php echo $lang['SHELTER']; ?></a>
	</div>
	
	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="launchPadController.php"><?php echo $lang['LAUNCH_PAD']?></a>
	</div>
	
	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>

    <div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="logout.php">Log out</a>
	</div>

</body>

</html>

<script>
var countDownDate;

function search() {

  	var request = $.ajax({
   		url: 'search.php',
   		type: 'get',
   		dataType: 'json'
 	});
 	
 	request.done( function ( data ) {
		//console.log(data);
		
		if (data.length > 0) {
			var actions = data[0].actions;
			var health = data[0].health;
			var fuel = data[0].fuel;
			var food = data[0].food;
			var metal = data[0].metal;
			var message = data[0].message;
			
			$('#labelActions').text(actions);
			$('#labelHealth').text(health);
			$('#labelFuel').text(fuel);
			$('#labelFood').text(food);
			$('#labelMetal').text(metal);
			$('#labelMessage').text(message);
		}
 	});
}

</script>