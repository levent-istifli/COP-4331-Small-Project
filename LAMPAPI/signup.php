<?php

    require __DIR__ . "/../config.php";

    // Gets JSON request info
    $in_data = json_decode(file_get_contents('php://input'), true);
    
    // Saves data in variables
    $first_name = $in_data["firstname"];
    $last_name  = $in_data["lastname"];
    $login      = $in_data["login"];
    $password   = $in_data["password"];

    // Connect to my sql database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Checks if there is any connection issue. Performs the "Add User" operation
    if($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {

        // add code to see if user is already in database //

        $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $login, $password);
		$stmt->execute();
		$stmt->close();
		$conn->close();
        returnWithError("");
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $ret = '{"Error":"'. $err . '"}';
        sendResultInfoAsJson($ret);
    }
?>