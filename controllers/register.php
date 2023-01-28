<?php
   
    // Database connection
    include('config/db.php');
	include_once 'controllers/language.php';

    // Swiftmailer lib
    require_once './lib/vendor/autoload.php';
    
    // Error & success messages
    global $success_msg, $email_exist, $f_NameErr, $_emailErr, $_passwordErr;
    global $fNameEmptyErr, $emailEmptyErr, $passwordEmptyErr, $email_verify_err, $email_verify_success;
    
    // Set empty form vars for validation mapping
    $_player_name = $_email = $_password = "";

	$playerNameRegexp = "/^[a-zA-Z0-9_.-]{5,15}$/";
	//$passwordRegexp = "/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"
	$passwordRegexp = "/^[a-zA-Z0-9_.*#$%&!-]{6,20}$/";
	
    if(isset($_POST["submit"])) {
        $playername    = $_POST["playername"];
        $email         = $_POST["email"];
        $password      = $_POST["password"];

        // check if email already exist
        $email_check_query = mysqli_query($connection, "SELECT * FROM users WHERE email = '{$email}' ");
        $rowCount = mysqli_num_rows($email_check_query);


        // PHP validation
        // Verify if form values are not empty
        if(!empty($playername) && !empty($email) && !empty($password)){
            
            // check if user email already exist
            if($rowCount > 0) {
                $email_exist = '
                    <div class="alert alert-danger" role="alert">
                        User with email already exist!
                    </div>
                ';
            } else {
                // clean the form data before sending to database
                $_player_name = mysqli_real_escape_string($connection, $playername);
                $_email = mysqli_real_escape_string($connection, $email);
                $_password = mysqli_real_escape_string($connection, $password);

                // perform validation
                if(!preg_match($playerNameRegexp, $_player_name)) {
                    $f_NameErr = '<div class="alert alert-danger">' . $lang['PLAYER_NAME_REGEXP_ERROR'] . '</div>';
                }
                if(!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
                    $_emailErr = '<div class="alert alert-danger">
                            Email format is invalid.
                        </div>';
                }
                //if(!preg_match($passwordRegexp, $_password)) {
                //    $_passwordErr = '<div class="alert alert-danger">
                //             Password should be between 6 to 20 charcters long, contains atleast one special chacter, lowercase, uppercase and a digit.
                //        </div>';
                //}
                if(!preg_match($passwordRegexp, $_password)) {
                    $_passwordErr = '<div class="alert alert-danger">' . $lang['PASSWORD_REGEXP_ERROR'] . '</div>';
                }
				
                // Store the data in db, if all the preg_match condition met
                if((preg_match($playerNameRegexp, $_player_name)) &&
                 (filter_var($_email, FILTER_VALIDATE_EMAIL)) &&
                 //(preg_match($passwordRegexp, $_password))){
                 (preg_match($passwordRegexp, $_password))){
                    // Generate random activation token
                    $token = md5(rand().time());

                    // Password hash
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);

                    // Query
                    $sql = "INSERT INTO users (playername, email, password, token, is_active, actions, fuel, food) 
					VALUES ('{$playername}', '{$email}', '{$password_hash}', '{$token}', '0', 30, 0, 0)";
                    
                    // Create mysql query
                    $sqlQuery = mysqli_query($connection, $sql);
                    
                    if(!$sqlQuery){
                        die("MySQL query failed!" . mysqli_error($connection));
                    } 

                    // Send verification email
                    if($sqlQuery) {
                        $msg = 'Hey ' . $playername . ',<br/>' .
								'You are almost ready to start enjoying Last Chance MMORPG. <br/>' .
								'Simply click the link below to verify your email address.<br/><br/>' .
								'<a href="http://' . $_SERVER['HTTP_HOST'] . '/LastChanceMMORPG/user_verificaiton.php?token=' . $token . '">Click here to verify email</a>';

                        // Create the Transport
                        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                        ->setUsername('last.chance.mmorpg@gmail.com')
                        ->setPassword('dudbzwehfwvwdwrs');

                        // Create the Mailer using your created Transport
                        $mailer = new Swift_Mailer($transport);

                        // Create a message
                        $message = (new Swift_Message('Last Chance MMORPG verification email'))
                        ->setFrom([$email => "Last Chance MMORPG"])
                        ->setTo($email)
                        ->addPart($msg, "text/html")
                        ->setBody('User');

                        // Send the message
                        $result = $mailer->send($message);
                          
                        if(!$result){
                            $email_verify_err = '<div class="alert alert-danger">
                                    Verification email coud not be sent!
                            </div>';
                        } else {
                            $email_verify_success = '<div class="alert alert-success">
                                Verification email has been sent!
                            </div>';
                        }
                    }
                }
            }
        } else {
            if(empty($playername)){
                $fNameEmptyErr = '<div class="alert alert-danger">
                    Player name can not be blank.
                </div>';
            }
            if(empty($email)){
                $emailEmptyErr = '<div class="alert alert-danger">
                    Email can not be blank.
                </div>';
            }
            if(empty($password)){
                $passwordEmptyErr = '<div class="alert alert-danger">
                    Password can not be blank.
                </div>';
            }            
        }
    }
?>