<?php
   
    // Database connection
    include('config/db.php');
	include_once 'controllers/language.php';
	
    global $accountNotExistErr, $emailPwdErr, $verificationRequiredErr, $email_empty_err, $pass_empty_err;

    if(isset($_POST['login'])) {
        $email_signin        = $_POST['email_signin'];
        $password_signin     = $_POST['password_signin'];

        // clean data 
        $user_email = filter_var($email_signin, FILTER_SANITIZE_EMAIL);
        $pswd = mysqli_real_escape_string($connection, $password_signin);

        // Query if email exists in db
        $sql = "SELECT * From users WHERE email = '{$email_signin}' ";
        $query = mysqli_query($connection, $sql);
        $rowCount = mysqli_num_rows($query);

        // If query fails, show the reason 
        if(!$query){
           die("SQL query failed: " . mysqli_error($connection));
        }

        if(!empty($email_signin) && !empty($password_signin)) {
            // Check if email exist
            if($rowCount <= 0) {
                $accountNotExistErr = '<div class="alert alert-danger">
                        User account does not exist.
                    </div>';
            } else {
                // Fetch user data and store in php session
                while($row = mysqli_fetch_array($query)) {
                    $id            = $row['id'];
                    $playername    = $row['playername'];
                    $email         = $row['email'];
                    $pass_word     = $row['password'];
                    $token         = $row['token'];
                    $is_active     = $row['is_active'];
					$actions       = $row['actions'];
					$fuel          = $row['fuel'];
					$food          = $row['food'];
                }

				$_SESSION['message'] = $lang['INITIAL_MESSAGE'];
				
                // Verify password
                $password = password_verify($password_signin, $pass_word);

                // Allow only verified user
                if($is_active == '1') {
                    if($email_signin == $email && $password_signin == $password) {
                       header("Location: ./dashboard.php");
                       
                       $_SESSION['id'] = $id;
                       $_SESSION['playername'] = $playername;
                       $_SESSION['email'] = $email;
                       $_SESSION['token'] = $token;
					   $_SESSION['actions'] = $actions;
					   $_SESSION['fuel'] = $fuel;
					   $_SESSION['food'] = $food;

                    } else {
                        $emailPwdErr = '<div class="alert alert-danger">
                                Either email or password is incorrect.
                            </div>';
                    }
                } else {
                    $verificationRequiredErr = '<div class="alert alert-danger">
                            Account verification is required for login.
                        </div>';
                }

            }

        } else {
            if(empty($email_signin)){
                $email_empty_err = "<div class='alert alert-danger email_alert'>Email not provided.</div>";
            }
            
            if(empty($password_signin)){
                $pass_empty_err = "<div class='alert alert-danger email_alert'>
                            Password not provided.
                        </div>";
            }            
        }

    }

?>    