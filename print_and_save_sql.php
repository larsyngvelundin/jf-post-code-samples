<?php
/*** 
Form used for my tests: https://www.jotform.com/242422471288962/
***/

/***
# How to Send Submissions to Your MySQL Database Using PHP
MySQL PHP Save Example (v2.0)
Jotform Inc. 2022 - AP#0031

This script was built for the following sample form: https://www.jotform.com/222744188444461
For more information, see: https://www.jotform.com/help/126-how-to-insert-update-submissions-to-your-mysql-database-using-php/
***/


/***
Display the data keys and values for debugging purposes.
***/
echo '<pre>', print_r($_POST, 1) , '</pre>';


/***
Test the data if it's a valid submission by checking the submission ID.
***/
if (!isset($_POST['submission_id'])) {
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
$full_name = $mysqli->real_escape_string(implode(" ", $_POST['name']));
$first_name = $mysqli->real_escape_string($_POST['name']['first']);
$last_name = $mysqli->real_escape_string($_POST['name']['last']);
$email = $mysqli->real_escape_string($_POST['email']);
$message = $mysqli->real_escape_string($_POST['message']);
$formID = $mysqli->real_escape_string($_POST['formID']);


/***
Prepare the test to check if the submission already exists in your database.
***/
$sid = $mysqli->real_escape_string($_POST['submission_id']);
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
}
else {
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
Display the outcome.
***/
if ($result === true) {
	echo "Success!";
}
else {
	echo "SQL error:" . $mysqli->error;
}


/***
Close the connection.
***/
$mysqli->close();