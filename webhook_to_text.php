<?php
// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture input data (you might want to validate and sanitize)
    $input = file_get_contents('php://input');

    // Check if there's any data sent as form-data (key-value pairs)
    if (!empty($_POST)) {
        // Iterate through the key-value pairs and append them to the string
        foreach ($_POST as $key => $value) {
            $dataToSave .= "Key: $key; Value: $value" . PHP_EOL;
        }
    }
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
            $dataToSave .= "File Key: $key; File Name: " . $file['name'] .
                PHP_EOL; // Add more file details if needed
        }
    }
    // Specify the file path where the data will be saved
    $filePath = "data.txt";

    // Open the file for writing; creates the file if it doesn't exist
    $file = fopen($filePath, "a");

    // Check if the file is opened successfully
    if ($file) {
        // Write the received data to the file
        //fwrite($file, $input . PHP_EOL);
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