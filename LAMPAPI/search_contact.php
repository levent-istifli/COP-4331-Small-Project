<?php
    require __DIR__ . "/../config.php";

    // gets JSON request info
    $in_data = json_decode(file_get_contents('php://input'), true);

    // connect to my sql database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // checks if there is any connection issue. Performs the "Add User" operation
    if($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {

        // input validation on server side
        $required = ["userid", "search"];
        
        foreach($required as $field) {
            if(!isset($in_data[$field]) || trim($in_data[$field]) === "") {
                returnWithError("Missing or Empty Field: $field");
                $conn->close();
                exit();
            }
        } 
        $search = "%".$in_data["search"]."%";
        // prepares statements to add user. checks if user already exists. add user to db if user does
        // not exists, otherwise returns error message
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Email FROM Contacts WHERE CONCAT(FirstName, \" \", LastName) LIKE ? AND UserID = ?");
        $stmt->bind_param("si", $search, $in_data["userid"]);
        
        try {
            $stmt->execute();
            $result = $stmt->get_result();
            $contacts = [];
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
            sendResultInfoAsJson(json_encode($contacts));
            returnWithError("");

        } catch(mysqli_sql_exception $exception) {
            returnWithError($exception->getMessage());
        }

		$stmt->close();
		$conn->close();
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