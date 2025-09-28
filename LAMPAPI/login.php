<?php
    // This file is required to access the database. In it are sensitive database information, including 
    // the host's ip address, the user, the database password, and database name
    require __DIR__ . "/../config.php";

    $in_data = json_decode(file_get_contents('php://input'), true);
    $id = 0;    $first_name = "";   $last_name  = "";

    // connects to db
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // checks if conection is successful. returns error message or xisting user account
    if($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {

        // input validation on the server side
        $required = ["login", "password"];
        
        foreach($required as $field) {
            if(!isset($in_data[$field]) || trim($in_data[$field]) === "") {
                returnWithError("Missing or Empty Field: $field");
                $conn->close();
                exit();
            }
        } 

        // returns user info if user is in db, otherwise return error.
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE Login = ? AND Password = ?");
        $stmt->bind_param("ss", $in_data["login"], $in_data["password"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()) {
            returnWithInfo($row['FirstName'], $row['LastName'], $row['ID']);
        } else {
            returnWithError("No records found");
        }

        $stmt->close();
        $conn->close();
    }   
    
    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $ret = '{"ID": 0, "FirstName": "", "LastName": "", "Error": "' . $err .'"}';
        sendResultInfoAsJson($ret);
    }

    function returnWithInfo($first_name, $last_name, $id) {
        $ret = '{"ID":' . $id . ',"FirstName":"' . $first_name . '","LastName":"' . $last_name . '", "Error":""}';
		sendResultInfoAsJson($ret);
    }
?>