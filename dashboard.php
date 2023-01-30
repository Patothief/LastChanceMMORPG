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

<body onload="addChatLine()">
	<div class="languagebar">
		<a href="?lang=en"><img src="images/en.png" title="English"/></a>
		<a href="?lang=hr"><img src="images/hr.png" title="Hrvatski"/></a>
	</div>

    <div class="vertical-center">
		<p class="card-text">
			<label><?php echo $lang['STORY']; ?></label>
		</p>

		<label><?php echo $lang['WARNING']; ?></label> <label id="countdownLabel"></label>
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

		<label id="labelMessage"><?php if (isset($_SESSION['message'])) echo $_SESSION['message']; ?></label>
	</div>

	<br/>

    <div id="chatbox" class="vertical-center">
		<div class="inner_div" id="chatlist">
		</div>
		<div class="input_area">
			<input id="message" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"/>
			<button onClick = 'addChatLine($("#message").val())' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEND']; ?></button> 
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

function addChatLine(message) {
	$("#message").val("");
	
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			message: message
		}
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
		
		var element = document.getElementById("chatlist");
		element.scrollTop = element.scrollHeight;
 	});

}

setInterval(function refreshChat() {
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get'
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
 	});
}, 5000);



// Set the date we're counting down to
var countDownDate = <?php echo $_SESSION['next_date'] ?>;//new Date("Jan 5, 2024 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="countdownLabel"
  document.getElementById("countdownLabel").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdownLabel").innerHTML = "EXPIRED";
  }
}, 1000);

</script>