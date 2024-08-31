<?php
/*** 
Form used for my tests: https://www.jotform.com/242423323923955

When request is sent from a form, you won't be able to see any of the "echo" responses below.
You can manually send requests through something like Postman to your webhook server in order to troubleshoot and see these messages.
***/

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if there's any data sent as form-data (key-value pairs)
    // Iterate through the key-value pairs and append them to the string
    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $dataToSave .= "Key: $key; Value: $value" . PHP_EOL;
        }
    } else {
        // If no POST data, try to save this line to file instead
        $dataToSave .= "No POST data in request" . PHP_EOL;
    }

    // Specify the file path where the data will be saved
    $filePath = "data.txt";

    // Open the file for writing; creates the file if it doesn't exist
    $file = fopen($filePath, "a");

    // Check if the file is opened successfully
    if ($file) {
        // Write the received data to the file
        fwrite($file, $dataToSave);

        // Close the file
        fclose($file);

        // Send a response back to the sender
        echo "Data received and saved successfully!";
    } else {
        // File couldn't be opened, send an error response
        echo "Error: Could not open the file to save data.";
    }
} else {
    // Not a POST request, send an error response
    echo "Error: You must send a POST request.";
}
?>