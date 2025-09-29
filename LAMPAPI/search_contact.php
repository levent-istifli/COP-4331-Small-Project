<?php

    require __DIR__ . "/../config.php";
    header("Content-Type: application/json");

    // connect to my sql database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // checks if there is any connection issue. Performs the "Add User" operation
    if($conn->connect_error) {
        returnWithError($conn->connect_error);
    }

	// Grab values from GET params
	$in_data = [
    	"firstname"   => isset($_GET["FirstName"]) ? $_GET["FirstName"] : "",
    	"lastname"    => isset($_GET["LastName"]) ? $_GET["LastName"] : "",
    	"email"       => isset($_GET["Email"]) ? $_GET["Email"] : "",
    	"phonenumber" => isset($_GET["PhoneNumber"]) ? $_GET["PhoneNumber"] : "",
    	"userid"      => isset($_GET["userid"]) ? (int)$_GET["userid"] : 0
	];	

	// Build query dynamically based on filled fields
	$conditions = array();
	$params = array();
	$types = "";

	// Check FirstName
	if (!empty($in_data["firstname"]) && trim($in_data["firstname"]) !== "") {
    		$conditions[] = "FirstName LIKE ?";
    		$params[] = "%" . trim($in_data["firstname"]) . "%";
    		$types .= "s";
	}

	// Check LastName
	if (!empty($in_data["lastname"]) && trim($in_data["lastname"]) !== "") {
    		$conditions[] = "LastName LIKE ?";
    		$params[] = "%" . trim($in_data["lastname"]) . "%";
    		$types .= "s";
	}

	// Check Email
	if (!empty($in_data["email"]) && trim($in_data["email"]) !== "") {
    		$conditions[] = "Email LIKE ?";
    		$params[] = "%" . trim($in_data["email"]) . "%";
    		$types .= "s";
	}

	// Check PhoneNumber
	if (!empty($in_data["phonenumber"]) && trim($in_data["phonenumber"]) !== "") {
    		$conditions[] = "PhoneNumber LIKE ?";
    		$params[] = "%" . trim($in_data["phonenumber"]) . "%";
    		$types .= "s";
	}

	if (count($conditions) === 0) {
    		// No search criteria - return all contacts for this user
    		$stmt = $conn->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Email FROM Contacts WHERE UserID = ?");
    		$stmt->bind_param("i", $in_data["userid"]);
	} 	
	else {
    		// Build WHERE clause with OR instead of AND
    		$whereClause = "(" . implode(" OR ", $conditions) . ")";
    
    		// Add UserID with AND
    		$sql = "SELECT ID, FirstName, LastName, PhoneNumber, Email FROM Contacts WHERE " . $whereClause . " AND UserID = ?";
    
    		// Add UserID to params
    		$params[] = $in_data["userid"];
    		$types .= "i";
    
    		$stmt = $conn->prepare($sql);
    		$stmt->bind_param($types, ...$params);
		error_log("WHERE clause: " . $sql);
	}

        try {
            $stmt->execute();
            $result = $stmt->get_result();
            $contacts = [];
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }

            sendResultInfoAsJson(json_encode($contacts));
            //returnWithError("");

        } catch(mysqli_sql_exception $exception) {
            returnWithError($exception->getMessage());
        }

		$stmt->close();
		$conn->close();

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $ret = '{"Error":"'. $err . '"}';
        sendResultInfoAsJson($ret);
    }
?>
