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

        // prepares statements to add user. checks if user already exists. add user to db if user does
        // not exists, otherwise returns error message
        $conn->prepare("SELECT ID, FirstName, LastName, PhoneNumber, Email FROM Users WHERE CONCAT(FirstName, \" \", LastName) LIKE ? AND UserID = ?");
        $stmt->bind_param("si", $in_data["search"], $in_data["userid"]);

        try {
            $stmt->execute();
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