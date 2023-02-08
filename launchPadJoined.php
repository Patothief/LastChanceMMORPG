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

	<label class="pageLabel"><?php echo $lang['LAUNCH_PAD']; ?></label>
	
	<div class="vertical-center">
		<label><?php echo $lang['CREW']?></label>
		<div "launchPadDiv">
			<table id = "launchPadParticipantsTable">
			</table>
			
			<br/>
			
			<div id = "launchPadDetailsDiv">
			</div>
		</div>
		
		<br/>
		
		<label id="labelMessage"></label>
		
		<br/><br/>
		<button onClick = "abandonRocket()" class='btn btn-outline-primary btn-lg btn-block'><?php echo $lang['ABANDON_LAUNCH_PAD']?></button>
		<br/>

	</div>
	
    <div id="chatbox" class="vertical-center">
		<div class="inner_div" id="chatlist">
		</div>
		<div class="input_area">
			<input id="message" placeholder="<?php echo $lang['TYPE_MESSAGE']; ?>"/>
			<button onClick = 'addChatLine($("#message").val())' class="btn btn-outline-primary btn-lg btn-block"><?php echo $lang['SEND']; ?></button> 
		</div>
	</div>
	
	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="shelter.php"><?php echo $lang['SHELTER']; ?></a>
	</div>
	
	<div class="vertical-center">
		<a class="btn btn-outline-primary btn-lg btn-block" href="wilderness.php"><?php echo $lang['WILDERNESS']; ?></a>
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

var request = $.ajax({
	url: 'launchPadJoinedBuilder.php',
	type: 'get',
	data: { 
		loadLaunchPadParticipantsTable: true
	}
});

request.done(function(data) {
	$("#launchPadParticipantsTable").html(data);
});

function buildDetailsDiv() {
	var request = $.ajax({
		url: 'launchPadJoinedBuilder.php',
		type: 'get',
		dataType: 'json',
		data: { 
			launchPadDetailsDiv: true
		}
	});

	request.done(function(data) {
		$("#launchPadDetailsDiv").html(data[0].content);
		$("#image2").css('clip-path', 'inset(' + data[0].insetPercentage + '% 0px 0px 0px)');
	});
}

buildDetailsDiv();

function abandonRocket() {
  	var request = $.ajax({
   		url: 'launchPadData.php',
   		type: 'get',
		data: { 
			abandonRocket: true
		}
	});
 	
 	request.done(function(data) {
		window.location.href = "launchPadController.php";
 	});
}

function buildRocket(buildPrice) {
	
	if (buildPrice > <?php echo $_SESSION['metal'] ?>) {
		$("#labelMessage").text('<?php echo $lang['NOT_ENOUGH_METALS'] ?>');
		return;
	}
	
	var request = $.ajax({
		url: 'launchPadJoinedBuilder.php',
   		type: 'get',
		data: { 
			buildRocket: true,
			buildPrice: buildPrice
		}
	});
 	
 	request.done( function ( data ) {
		buildDetailsDiv();
 	});
}

// initialize chat
var request = $.ajax({
	url: 'chat.php',
	type: 'get',
	data: { 
		rocketId: <?php echo $_SESSION['rocketId'] ?>
	}		
});

request.done( function ( data ) {
	$("#chatlist").html(data);
	var element = document.getElementById("chatlist");
	element.scrollTop = element.scrollHeight;	
});

function addChatLine(message) {
	$("#message").val("");
	
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: <?php echo $_SESSION['rocketId'] ?>,
			message: message
		}
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
		
		var element = document.getElementById("chatlist");
		element.scrollTop = element.scrollHeight;
 	});
}

function refreshChat() {
  	var request = $.ajax({
   		url: 'chat.php',
   		type: 'get',
		data: { 
			rocketId: <?php echo $_SESSION['rocketId'] ?>
		}		
	});
 	
 	request.done( function ( data ) {
		$("#chatlist").html(data);
 	});
}

setInterval(refreshChat, 5000);


</script>