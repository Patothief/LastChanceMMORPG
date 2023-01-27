<?php 
include('config/db.php');
include_once 'controllers/language.php';
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

<body onload="show_func()>
	<div class="languagebar">
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

    <div class="vertical-center">
		<p class="card-text">
			<label><?php echo $lang['STORY']; ?></label>
		</p>

		<label><?php echo $lang['WARNING']; ?></label>
	</div>

	<br/>

    <div class="vertical-center">
		<p class="card-text"><?php echo $lang['PLAYER']; ?>
			<label><?php echo $_SESSION['playername']; ?></label>
		</p>
		
		<p class="card-text"><?php echo $lang['ACTIONS']; ?>
			<label id="labelActions"><?php echo $_SESSION['actions'] ?></label>
		</p>
		
		<br/>
		
		<p class="card-text"><?php echo $lang['FUEL']; ?>
			<label id="labelFuel"><?php echo $_SESSION['fuel'] ?></label>
		</p>

		<br/>
		
		<p class="card-text"><?php echo $lang['FOOD']; ?>
			<label id="labelFood"><?php echo $_SESSION['food'] ?></label>
		</p>
		
		<br/><br/>

		<button onClick = 'search()' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEARCH']; ?></button> 

		<br/>

		<label id="labelMessage"><?php echo $_SESSION['message'] ?></label>
	</div>

	<br/>

    <div id="chatbox" class="vertical-center">
		<div class="inner_div" id="chatlist">
			<?php
			$query = "SELECT * FROM chat";
			$run = $connection->query($query);

			while ($row = $run->fetch_array()) :
				if ($row['player_id']!=$_SESSION['id']) {
					$css_class = "item_left";
				} else {
					$css_class = "item_right";
				}
				?>
				<div class="message_info <?php echo $css_class; ?>">
					<?php echo $row['time']; ?>
				</div>
				<div class="message <?php echo $css_class; ?>">
					<?php echo $row['player_id']; ?>: <?php echo $row['message']; ?>
				</div>
				<?php
			endwhile;
			?>
		</div>
		<div class="input_area">
			<input name="message" placeholder="Type your message"/>
			<button onClick = 'addChatLine()' class="btn btn-outline-primary btn-lg btn-block">Send</button> 
		</div>
	</div>
	
	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>
	<br/><br/><br/>

    <div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="logout.php">Log out</a>
	</div>

	<br/>

</body>

</html>

<script>
function search() {

  	var request = $.ajax({
   		url: 'search.php',
   		type: 'get',
   		dataType: 'json'
 	});
 	
 	request.done( function ( data ) {
		console.log(data);
		
		if (data.length > 0) {
			var actions = data[0].actions;
			var fuel = data[0].fuel;
			var food = data[0].food;
			var message = data[0].message;
			
			$('#labelActions').text(actions);
			$('#labelFuel').text(fuel);
			$('#labelFood').text(food);
			$('#labelMessage').text(message);
		}
 	});
}

function addChatLine() {
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
   		dataType: 'json'
 	});
 	
 	request.done( function ( data ) {
		console.log(data);
		
		if (data.length > 0) {
			var actions = data[0].actions;
			var fuel = data[0].fuel;
			var food = data[0].food;
			var message = data[0].message;
			
			$('#labelActions').text(actions);
			$('#labelFuel').text(fuel);
			$('#labelFood').text(food);
			$('#labelMessage').text(message);
		}
 	});
	
	var element = document.getElementById("chatlist");
	element.scrollTop = element.scrollHeight;

}
</script>