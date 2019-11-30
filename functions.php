<?php session_start();
// Import PHPMailer classes into the global namespace
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//require '/home/bitnami/vendor/autoload.php';
//require '/Applications/MAMP/htdocs/vendor/autoload.php';

// connect to database
$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

//global variable dclaration
$page = htmlspecialchars($_SERVER['PHP_SELF']);
$errors   = array(); 

//---------------------- FUNCTIONS START HERE ----------------------

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}

// call the login() function if login_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// call the chpwd() function if chpwd_btn is clicked
if (isset($_POST['chpwd_btn'])) {
	chpwd();
}

// call the reset_password() function if resetpwd_btn is clicked
if (isset($_POST['resetpwd_btn'])) {
    reset_password();
}

// call the new_password() function if newpwd_btn is clicked
if ( isset($_POST['newpwd_btn']) ) {
    new_password();
}

if ( isset($_POST['send_btn']) ) {
    send_message($_POST['view']);
}

if ( isset($_POST['msgsend_btn']) ) {
    send_message($_POST['ticket_no']);
}

if ( isset($_POST['savecfg_btn']) ) {
    save_config();
}

if ( isset($_GET['activate']) ) {
    activate_user($_GET['id']);
}

if ( isset($_GET['deactivate']) ) {
    deactivate_user($_GET['id']);
}


// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $query, $errors, $username, $firstname, $lastname, $email,  $passwd1, $passwd2;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
    $firstname  =  e($_POST['firstname']);
    $lastname   =  e($_POST['lastname']);
    $username   =  e($_POST['username']);
	$email      =  e($_POST['email']);
    $passwd1    =  e($_POST['passwd1']);
    $passwd2    =  e($_POST['passwd2']);

     //prepare a select statement
    $sql = "SELECT id FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($db, $sql)){
        // bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // set parameters
        $param_username = $username;

        // attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                array_push($errors, "This username is already taken.");
            } else{
                //Close statement
                mysqli_stmt_close($stmt);
                
                //prepare a select statement
                $sql = "SELECT id FROM users WHERE email = ?";
        
                if($stmt = mysqli_prepare($db, $sql)){
                    // bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_email);

                    // set parameters
                    $param_email = $email;

                    // attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                    /* store result */
                        mysqli_stmt_store_result($stmt);

                        if(mysqli_stmt_num_rows($stmt) == 1){
                            array_push($errors, "Email address is already registered.");
                        } else{
                             if ( $passwd1 != $passwd2 ) { 
                                 array_push($errors, "Passwords do not match!"); 
                             }
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                } 
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    
        mysqli_stmt_close($stmt);
    }
    
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password_hash = md5($passwd1);//TO-DO make this more secure
        $user_type = "agent";
        $query = "INSERT INTO users (username, user_type, password, email, first_name, last_name) VALUES('$username', '$user_type', '$password_hash','$email', '$firstname', '$lastname')";
        
        if ( mysqli_query($db, $query) ){

            $_SESSION['success']  = '<div class="alert alert-success fixed-top"><strong>New user successfully created!</strong></div>';
            header('location: manage-agents.php');
            exit;
        } else {
            array_push($errors, "Something went wrong. :( <br/>".mysqli_error($db));
        }
    } else {
        array_push($errors, "Something went wrong. :( <br/>".mysqli_error($db));
    }
    mysqli_close($db);
}

// USER LOGIN
function login(){
    global $db, $errors, $username, $password;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
    $password    =  e($_POST['password']);

    
    if (empty($username)) { 
		array_push($errors, "Please enter a username."); 
	}
    if (empty($password)) {
        array_push($errors, "Please enter a password."); 
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password_hash = md5($password);
        
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password_hash' LIMIT 1";
        
        $retval = mysqli_query($db, $query);
        
        if (mysqli_num_rows($retval) == 1) { // user found
            $logged_in_user = mysqli_fetch_assoc($retval);
            // check if user deactivated or not
            
            if ( $logged_in_user['isDeactivated'] ){
                array_push($errors, "<strong>Account deactivated!</strong> Please contact site administrator.");
            } else {
                // check if user is admin or user
                if ($logged_in_user['user_type'] == 'admin') {
                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['success']  = '<div class="alert alert-success fixed-top"><strong>Login successful!</strong> You are now logged in. </div>';
                    exit(header('location: admin/index.php'));		

                } else{
                    $user_id = $logged_in_user['id'];
                    $query = "SELECT * FROM userinfo WHERE user_id='$user_id'";
                    $results = mysqli_query($db, $query);

                    $_SESSION['user'] = $logged_in_user;
                    $_SESSION['userinfo'] = mysqli_fetch_assoc($results);
                    $_SESSION['success']  = '<div class="alert alert-success fixed-top"><strong>Login successful!</strong> You are now logged in. </div>';
                    header('location: agent/index.php');
                    exit;
                }
            } 
        } else {
                array_push($errors, "Wrong username or password.");
        }
    }
    mysqli_close($db);
}

// CHANGE PASSWORD
function chpwd(){
    global $db, $errors, $username, $password, $newpwd1, $newpwd2;
    
    $currpwd   =  e($_POST['currentpwd']);
    $newpwd1    =  e($_POST['newpwd']);
    $newpwd2    =  e($_POST['confirmpwd']);
    $username    =  $_SESSION['user']['username'];
    $password    =  $_SESSION['user']['password'];
    $password_hash = md5($currpwd);
    
    
    if ($password_hash != $password) { 
        array_push($errors, "Please enter current password.");
    } else {
        if ($newpwd1 != $newpwd2) {
            array_push($errors, "New password values do not match.");
        }    
    } 
    $newpwd_hash = md5($newpwd1);
    if (count($errors) == 0) {
        $query = "UPDATE users SET password='$newpwd_hash' WHERE username='$username'";
        mysqli_query($db, $query);
        
        $_SESSION['user']['password'] = $newpwd_hash;
        $_SESSION['success']  = '<div class="alert alert-success fixed-top" role="alert"><strong>Password successfully changed!</strong></div>';
        exit(header('location: index.php'));
    }
    mysqli_close($db);
}

// ACTIVATE USER
function activate_user($id) {
    global $db;
    
    $query = "UPDATE users SET isDeactivated=0 WHERE id=$id";
    mysqli_query($db, $query);
}

// ACTIVATE USER
function deactivate_user($id) {
    global $db, $errors;
    
    $query = "UPDATE users SET isDeactivated=1 WHERE id=$id";
    mysqli_query($db, $query);
}

// FORGOT PASSWORD
function reset_password() {
    global $db, $errors, $admin_email, $admin_pwd, $mail_host, $site_url;
    
    $email = e($_POST['email']);
    
    $query = "SELECT id FROM users WHERE email='$email'";
    $retval = mysqli_query($db,$query);
    
    
    if ( mysqli_num_rows($retval) == 1 ) {
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        
        $query = "SELECT id FROM password_resets WHERE email='$email'";
        $retval = mysqli_query($db,$query);
        if ( mysqli_num_rows($retval) == 1 ) {
            $query = "UPDATE password_resets SET reset_token = '$token' WHERE email = '$email'";
        } else {
            $query = "INSERT INTO password_resets(email, reset_token) VALUES ('$email', '$token')";
        }
        
        if ($retval = mysqli_query($db, $query)) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();                        // Set mailer to use SMTP
                $mail->Host       = $mail_host;         // Specify main and backup SMTP servers
                $mail->SMTPAuth   = true;               // Enable SMTP authentication
                $mail->Username   = $admin_email;       // SMTP username
                $mail->Password   = $admin_pwd;         // SMTP password
                $mail->SMTPSecure = 'tls';              // Enable TLS encryption, `ssl` also accepted
                $mail->Port       = 587;                // TCP port to connect to

                //Recipient
                $mail->setFrom($admin_email, 'SUBs Website');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Reset your password.';
                $mail->Body    = 'Hi there, click on this <a href='.$site_url.'new-password.php?token='.$token.'>link</a> to reset your password on our site.<br/>If the link above did not work, please copy-paste the following to your web browser: '.$site_url.'new-password.php?token='.$token;
                $mail->AltBody = 'Hi there, please visit the following link to reset your password: '.$site_url.'new-password.php?token='.$token;

                $mail->send();
                $_SESSION['success'] = '<div class="alert alert-success fixed-top"> Password reset link sent to your email.</div>';
            } catch (Exception $e) {
                array_push($errors,'Email not sent. Please contact site administrator.');
            }
        } else {
            array_push($errors, "Sorry, something went wrong. Please contact site administrator.");
        }
    } else {
        array_push($errors, "Sorry, email is not yet registered.");
    }
    
    mysqli_close($db);
}

// NEW PASSWORD
function new_password() {
    global $db, $errors;
    
    if ( $_POST['token'] != '') {
        $token      =  e($_POST['token']);
        $newpwd1    =  e($_POST['newpwd']);
        $newpwd2    =  e($_POST['confirmpwd']);
        
        $query = "SELECT email FROM password_resets WHERE reset_token='$token'";
        $retval = mysqli_query($db, $query);
        
        if ( mysqli_num_rows($retval) == 1 ){
            if ( $newpwd1 == $newpwd2 ){
                $passwordhash = md5($newpwd1);
                $row = mysqli_fetch_assoc($retval);
                $email = $row['email'];
                
                $query = "UPDATE users SET password = '$passwordhash' WHERE email = '$email'";
                if ( mysqli_query($db, $query) ) {
                    $_SESSION['success'] = '<div class="alert alert-success fixed-top"> <strong> Password changed!</strong> You may now <a href="login.php">login to your account using the new password</a>.</div>';
                    header('location: login.php');
                    exit();
                } else {
                    array_push($errors, "Error updating password. Please inform site administrator. ERROR DETAILS: ".mysqli_error($db));        
                }
            } else {
                array_push($errors, "Passwords don't match.");    
            }
        } else {
            array_push($errors, "Token is missing or invalid.");
        }
    } else {
        array_push($errors, "Token is missing or invalid.");
    }
    
    mysqli_close($db);
}

// SEND MESSAGE
function send_message($ticket_no=0){
    global $db, $errors, $query;
    $shortcode = substr($_SESSION['siteinfo']['shortcode'], -4);
    $sent_by = $_SESSION['user']['username'];
    $message = e($_POST['message']);
    
    $query = "SELECT * FROM tickets WHERE ticket_no=".$ticket_no;
    $retval = mysqli_query($db, $query); 
    $ticket = mysqli_fetch_assoc($retval);
//    
    $address = $ticket['subscriber_no']; 
//    
    $query = "SELECT * FROM subscribers WHERE subscriber_no=".$address;
    $retval = mysqli_query($db, $query); 
    $subscriber = mysqli_fetch_assoc($retval);
//    
    $access_token = $subscriber['access_token'];
//    

    if ( count($errors) == 0) {
            $clientCorrelator = "264801";
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/".$shortcode."/requests?access_token=".$access_token ,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"outboundSMSMessageRequest\": { \"clientCorrelator\": \"".$clientCorrelator."\", \"senderAddress\": \"".$shortcode."\", \"outboundSMSTextMessage\": {\"message\": \"".$message."\"}, \"address\": \"".$address."\" } }",
              CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
              ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }    
        $timestamp=date("Y-m-d H:i:s",time());
        $query = "UPDATE tickets SET agent_assigned='$sent_by', response='$message', date_responded='$timestamp' WHERE ticket_no='$ticket_no'"; 
        
        
        if ( mysqli_query($db, $query) ) {
            exit(header('location:index.php'));
        } else {
            array_push($errors, "Something went wrong. :( <br/>".mysqli_error($db));
        }
    }
    mysqli_close($db);
}

// SHOW MESSAGES
function show_messages($ticket_no=0){
    global $db, $errors;
    
    $query = "SELECT * FROM tickets WHERE ticket_no=".$ticket_no;
    $retval_ticket = mysqli_query($db, $query);
    $ticket = mysqli_fetch_assoc($retval_ticket);
    

    $query = "SELECT * FROM mbox WHERE ticket_no=$ticket_no ORDER BY date_created ASC";
    $retval = mysqli_query($db, $query);
    
    
    if ( mysqli_num_rows($retval) > 0) {
        echo '<div class="message-header"></div><div class="message-body">';
        while ($row = mysqli_fetch_assoc($retval)){
            
            if ( $row['sent_by'] == $ticket['subscriber_no']) {
                echo '<div class="receiver-bubble">';
                echo '<p>'.$row['message'].'</p>';
                echo '<span class="timestamp">'.$row['date_created'].'</span>';
                echo '</div>';
            } else {
                echo '<div class="sender-bubble">';
                echo '<p>'.$row['message'].'</p>';
                echo '<span class="timestamp">'.$row['date_created'].'</span>';
                echo '</div>';
            }
        }
        echo '</div>';
        echo '<div class="message-response">';
        echo '<form method="post" action="messages.php">';
        echo '
        <input type="number" name="ticket_no" value='.$ticket_no.' hidden>
        <textarea class="form-control col col-lg-10 float-left" name="message" placeholder="Write your response here..."></textarea>
        <button type="submit" class="btn btn-dark col col-lg-2 float-right" name="msgsend_btn">Send</button>
        </div>';
        echo '</form>';
        
    } else {
        echo "No messages to show.";
    }
    mysqli_close($db);
}

// LIST NEW TICKETS
function list_new_tickets() {
    global $db, $errors;
    
    $query = "SELECT * FROM tickets WHERE response IS NULL";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<thead class='thead-dark'><tr><th>Ticket No.</th><th>Date Sent</th><th>Sender</th><th>Message</th><th>Action</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['ticket_no']."</td><td>";
            echo $row['date_sent']."</td><td>";
            echo $row['subscriber_no']."</td><td>";           
            echo $row['message']."</td><td><a class='btn btn-warning respond-modal-btn' data-toggle='modal' data-target='#respondModal' data-id=".$row['ticket_no'].">Respond</a>";
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No results. </a>";
    }
    
}

// LIST TICKETS
function list_tickets() {
    global $db, $errors;
    
    $query = "SELECT * FROM tickets WHERE response IS NOT NULL";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<thead class='thead-dark'><tr><th>Ticket No.</th><th>Date Sent</th><th>Sender</th><th>Message</th><th>Responder</th><th>Response</th><th>Date Responded</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr class='table-row' data-href='message.php?ticket_no=".$row['ticket_no']."'><td>".$row['ticket_no']."</td><td>";
            echo $row['date_sent']."</td><td>";
            echo $row['subscriber_no']."</td><td>";           
            echo $row['message']."</td><td>";
            echo $row['agent_assigned']."</td><td>";
            echo $row['response']."</td><td>";
            echo $row['date_responded'];
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No results. </a>";
    }
    
    mysqli_close($db);
}

// escape string function for processing input texts
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

// display error
function display_error() {
	global $errors;
    
	if (count($errors) > 0){
		echo '<div class="error alert alert-warning d-flex justify-content-center fixed-top" role="alert">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

// get site information
function get_siteinfo() {
    global $db, $errors;
    
    $query = "SELECT * FROM site";
    if ($retval = mysqli_query($db, $query)){
        while($row = mysqli_fetch_assoc($retval)){
            $_SESSION['siteinfo'][$row['meta_key']] = $row['meta_value'];
        }
    } else {
        array_push($errors, mysqli_error($db));
    }        
}

//change site configuration
function save_config() {
    global $db, $errors;
    $site_url = e($_POST['site_url']);
    $site_title = e($_POST['site_title']);
    $site_name  = e($_POST['site_name']);
    $admin_email = e($_POST['admin_email']);
    $shortcode = e($_POST['shortcode']);
    $app_id = e($_POST['app_id']);
    $app_secret = e($_POST['app_secret']);
    

    $query .= "UPDATE site SET meta_value='$site_title' WHERE meta_key='site_title'; ";
    $query .= "UPDATE site SET meta_value='$site_name' WHERE meta_key='site_name'; ";
    $query .= "UPDATE site SET meta_value='$site_url' WHERE meta_key='site_url'; ";
    $query .= "UPDATE site SET meta_value='$admin_email' WHERE meta_key='admin_email'; ";
    $query .= "UPDATE site SET meta_value='$shortcode' WHERE meta_key='shortcode'; ";
    $query .= "UPDATE site SET meta_value='$app_id' WHERE meta_key='app_id'; ";
    $query .= "UPDATE site SET meta_value='$app_secret' WHERE meta_key='app_secret'; ";
    
    if ($retval = mysqli_multi_query($db, $query)){
        $_SESSION['success']  = '<div class="alert alert-success fixed-top"><strong>Site configuration saved!</strong></div>';
        exit(header('location:configure.php'));
    } else {
        array_push($errors, mysqli_error($db));
    }        
}

// LIST SUBSCRIBERS
function list_subscribers() {
    global $db, $errors;
    
    $query = "SELECT * FROM subscribers";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<thead class='thead-dark'><tr><th>Subscriber No.</th><th>Access Token</th><th>Date Subscribed</th><th>Status</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['subscriber_no']."</td><td>";
            echo $row['access_token']."</td><td>";
            echo $row['date_subscribed']."</td><td>";           
            echo ($row['subscribed']?"<span class='badge badge-success'>Subscribed</span>":"<span class='badge badge-secondary'>Unsubscribed</span>");
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No subscribers yet. </a>";
    }
    
}

// LIST AGENTS
function list_agents() {
    global $db, $errors;
    
    $query = "SELECT * FROM users where user_type='agent'";
    $retval = mysqli_query($db, $query); 
    
    if (mysqli_num_rows($retval) > 0) {
        echo "<thead class='thead-dark'><tr><th>User ID</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Date Registered</th><th>Actions</th></tr></thead><tbody>";
        
        while($row = mysqli_fetch_assoc($retval)) {
            echo "<tr><td>".$row['id']."</td><td>";
            echo $row['username']."</td><td>";
            echo $row['first_name']."</td><td>";           
            echo $row['last_name']."</td><td>";
            echo $row['email']."</td><td>";
            echo $row['date_registered']."</td><td>";
            if ( $row['isDeactivated'] ) {
                echo "<a class='btn btn-success' href='manage-agents.php?activate=1&id=".$row['id']."'>Activate</a>";
            } else {
                echo "<a class='btn btn-danger' href='manage-agents.php?deactivate=1&id=".$row['id']."'>Deactivate</a>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<a href='#'> No subscribers yet. </a>";
    }
    
}


//get total new messages
function get_total_new() {
    global $db, $query;
    $query = "SELECT COUNT(ticket_no) AS total FROM tickets WHERE response IS NULL";
    $retval = mysqli_query($db, $query);
    $value = mysqli_fetch_assoc($retval);
    echo $value['total'];
}

//get total addressed messages
function get_total_addressed() {
    global $db, $query;
    $query = "SELECT COUNT(ticket_no) AS total FROM tickets WHERE response IS NOT NULL";
    $retval = mysqli_query($db, $query);
    $value = mysqli_fetch_assoc($retval);
    echo $value['total'];
}

//get total subscribers
function get_total_subscribers() {
    global $db, $query;
    $query = "SELECT COUNT(subscriber_no) AS total FROM subscribers";
    $retval = mysqli_query($db, $query);
    $value = mysqli_fetch_assoc($retval);
    echo $value['total'];
}

?>
