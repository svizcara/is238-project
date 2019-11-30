<?php include('config.php');



session_start();
// connect to database
$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

//GLOBE API INTEGRATION STARTS HERE
if ( isset($_GET['access_token']) ) {
	get_subscriber();
}

if ( isset($_POST) ) {
	get_data();
}

function get_subscriber() {
    global $db, $query;
    
    $access_token = $_GET['access_token'];
    $subscriber_no = $_GET['subscriber_number'];
//    
    $query = "SELECT * FROM subscribers WHERE subscriber_no='$subscriber_no' LIMIT 1";
    $retval = mysqli_query($db, $query); 
//    
    if (mysqli_num_rows($retval) == 1) {
        $query = "UPDATE subscribers SET access_token='$access_token' WHERE subscriber_no='$subscriber_no'";
    } else {
        $query = "INSERT INTO subscribers (subscriber_no, access_token) VALUES ('$subscriber_no', '$access_token')";
    }
//
    mysqli_query($db, $query);
}

function get_data() {
    global $db, $query;
    
    // Takes raw data from the request
    $json = file_get_contents('php://input');


    // Converts it into a PHP object
    $data = json_decode($json,true);
    
    try{
        if ( $value=$data['unsubscribed'] ) {
            $timestamp = date("Y-m-d H:i:s",strtotime($value['timestamp']));
            $subscriber_no = substr($value['subscriber_number'],-10);
            
            $query = "UPDATE subscribers SET subscribed=0, date_unsubscribed='$timestamp' WHERE subscriber_no='$subscriber_no'";
            mysqli_query($db, $query);
            
        } else if ( $value=$data['inboundSMSMessageList'] ) {
            $max = $value['numberOfMessagesInThisBatch'];

            for ( $i = 0; $i < $max; $i++ ) {
                $timestamp = date("Y-m-d H:i:s",strtotime($value['inboundSMSMessage'][$i]['dateTime']));
                $sender = substr($value['inboundSMSMessage'][$i]['senderAddress'],-10);
                $message = $value['inboundSMSMessage'][$i]['message'];

                $query = "INSERT INTO tickets (subscriber_no, message, date_sent) VALUES ('$sender', '$message', '$timestamp')";
                mysqli_query($db, $query);
            }

        } else if ($value=$data['outboundSMSMessageRequest']){

        }
    } catch (exception $e){
        //pass
    }
}
mysqli_close($db);
session_destroy();
?>
