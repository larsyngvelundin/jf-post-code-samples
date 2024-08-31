<?php
/*** 
Form used for my tests: https://www.jotform.com/242424133952957

When request is sent from a Jotform, you won't be able to see any of the "echo" responses below.
You can manually send requests through something like Postman to your webhook server in order to troubleshoot and see these messages.
***/

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /***
    Test the data if it's a valid submission by checking the submission ID.
    ***/
    //Note the different name compared to the Thank You POST data
    //submission_id -> submissionID
    if (!isset($_POST['submissionID'])) {
        die("Invalid submission data!");
    }


    /***
    ## Database Config

    NOTE: 
    Replace the values below with your MYSQL database environment variables 
    to create a valid connection.
    ***/
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
    To view the submission as POST data, see: https://www.jotform.com/help/51-how-to-post-submission-data-to-thank-you-page/
    ***/
    $formID = $mysqli->real_escape_string($_POST['formID']);
    //Note the difference in the formatting of the data compared to the Thank You POST data
    //Submission data itself is now within 'rawRequest'
    $submission_data = json_decode($_POST['rawRequest'], true);
    //The fields now use prefix, in the form of 'q1_' with the number being their Question ID
    $first_name = $mysqli->real_escape_string($submission_data['q3_name']['first']);
    $last_name = $mysqli->real_escape_string($submission_data['q3_name']['last']);
    $email = $mysqli->real_escape_string($submission_data['q4_email']);
    $message = $mysqli->real_escape_string($submission_data['q7_message']);


    /***
    Prepare the test to check if the submission already exists in your database.
    ***/
    $sid = $mysqli->real_escape_string($_POST['submissionID']);
    $result = $mysqli->query("SELECT * FROM $db_table WHERE submission_id = '$sid'");


    /***
    ## Queries to Run

    Perform the test and then UPDATE or INSERT the record
    depending if the submission is already in the database or not.

    NOTE:
    Edit the queries below according to your form and database table structure.
    For more information, see:
    - https://www.freecodecamp.org/news/the-sql-update-statement-explained/#how-do-you-use-an-update-statement
    - https://www.freecodecamp.org/news/sql-insert-and-insert-into-statements-with-example-syntax/#how-to-use-insert-into-in-sql
    ***/
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
            message
        ) VALUES (
            '$formID',
            '$sid', 
            '$first_name', 
            '$last_name', 
            '$email',
            '$message')
        ");
    }


    /***
    Echo the outcome.
    ***/
    if ($result === true) {
        echo "Success!";
    } else {
        echo "SQL error:" . $mysqli->error;
    }
} else {
    // Not a POST request, send an error response
    echo "Error: You must send a POST request.";
}
?>