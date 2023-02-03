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
	<div class="languagebar">
		<label><?php echo $_SESSION['playername']; ?></label>
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

	<label class="pageLabel">Rocket</label>
	
	<div class="vertical-center">
		<label>Rocket name:</label>
		<input id="rocketName" placeholder="Name your rocket" value="My rocket"/>
		<br/>
		<label>Rocket class:</label>
		<br/>
		
		<?php
			$sql = "SELECT * FROM rocket_type";
			$run = $connection->query($sql);

			while ($row = $run->fetch_array()) :
				echo "<div class='rocket-type' id='" . $row['id'] . "'>";
				echo "<table>";
				echo "<tr>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>Price (metals): " . $row['metals_required'] . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='2'>Image</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Fuel: " . $row['max_fuel'] . "</td>";
				echo "<td>Cargo: " . $row['max_cargo'] . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td>Crew: " . $row['max_crew'] . "</td>";
				echo "<td></td>";
				echo "</tr>";
				echo "</table>";
				echo "</div>";
				echo "<br/>";
			endwhile;	
		?>
		<br/>
		<button onClick = 'createRocket()' class='btn btn-outline-primary btn-lg btn-block'>Create rocket</button>
		<label>You have <?php echo $_SESSION['metal'] ?> metals.</label>
		<br/>

		<a class="btn btn-outline-primary btn-lg btn-block" href="rocketController.php">Back</a>
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

function createRocket() {
	$rocketTypeId = $('.rocket-type.selectedDiv').attr('id');
	
  	var request = $.ajax({
   		url: 'rocketData.php',
   		type: 'get',
		data: { 
			createRocket: true,
			rocketName: $("#rocketName").val(),
			rocketTypeId: $rocketTypeId
		}
	});
 	
 	request.done( function ( data ) {
		window.location.href = "rocketController.php";
 	});
}

$('.rocket-type').click(function() {
  $('.rocket-type').removeClass('selectedDiv');
  $(this).addClass('selectedDiv');
});

$('.rocket-type:first').addClass('selectedDiv');

</script>