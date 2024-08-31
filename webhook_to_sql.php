<?php
// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture input data (you might want to validate and sanitize)
    $input = file_get_contents('php://input');




    $db_host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "us_east_meeting";
    $db_table = "submissions";

    /***
    Connect to database.
    ***/
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    /***
    ## Data to Save

    Prepare the data to prevent possible SQL injection vulnerabilities to the database.

    NOTE: Add the POST data to save in your database.
    To view the submission as POST data, see: https://www.jotform.com/help/?p=607527
    ***/
    $submission_data = json_decode($_POST['rawRequest'], true);
    $first_name = $mysqli->real_escape_string($submission_data['q3_name']['first']);
    $last_name = $mysqli->real_escape_string($submission_data['q3_name']['last']);
    $email = $mysqli->real_escape_string($submission_data['q4_email']);
    $message = $mysqli->real_escape_string($submission_data['q7_message']);
    $date = $mysqli->real_escape_string(implode("-", $submission_data['q8_date']));
    $formID = $mysqli->real_escape_string($_POST['formID']);


    /***
    Prepare the test to check if the submission already exists in your database.
    ***/
    $sid = $mysqli->real_escape_string($_POST['submissionID']);
    $result = $mysqli->query("SELECT * FROM $db_table WHERE submission_id = '$sid'");


    if ($result->num_rows > 0) {
        /* UPDATE query */
        $result = $mysqli->query("UPDATE $db_table 
            SET first_name = '$first_name',
                last_name = '$last_name',
                email = '$email', 
                message = '$message' 
            
            WHERE submission_id = '$sid'
        ");
    } else {
        /* INSERT query */
        $result = $mysqli->query("INSERT IGNORE INTO $db_table (
            form_id,
            submission_id, 
            first_name,
            last_name, 
            email, 
            message,
            date
        ) VALUES (
            '$formID',
            '$sid', 
            '$first_name', 
            '$last_name', 
            '$email',
            '$message',
            '$date')
        ");
    }

} else {
    // Not a POST request, send an error response
    echo "Error: You must send a POST request.";
}
?>