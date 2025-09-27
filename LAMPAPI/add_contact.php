<?php
    require __DIR__ . "/../config.php";

    // API KEY CHECK
    $api_key = getallheaders()['x-api-key'] ?? '';
    if($api_key !== API_KEY) {
        http_response_code(401);
        returnWithError("Unauthorized access to API");
        exit;
    }

    // gets JSON request info
    $in_data = json_decode(file_get_contents('php://input'), true);

    // connect to my sql database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // checks if there is any connection issue. Performs the "Add User" operation
    if($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {

        // input validation on server side
        $required = ["userid", "firstname", "lastname", "phonenumber", "email"];
        
        foreach($required as $field) {
            if(!isset($in_data[$field]) || trim($in_data[$field]) === "") {
                returnWithError("Missing or Empty Field: $field");
                $conn->close();
                exit();
            }
        } 

        // prepares statements to add user. checks if user already exists. add user to db if user does
        // not exists, otherwise returns error message
        $stmt = $conn->prepare("INSERT INTO Contacts (FirstName, LastName, PhoneNumber, Email, UserID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $in_data["firstname"], $in_data["lastname"], $in_data["phonenumber"], $in_data["email"], $in_data["userid"]);

        try {
            $stmt->execute();
            returnWithError("");

        } catch(mysqli_sql_exception $exception) {
            if($exception->getCode() == 1062) { returnWithError("Contact already exists"); }
            else { returnWithError($exception->getMessage()); }
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