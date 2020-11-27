<?
session_start();
require_once(__DIR__ . "/../config/config.php");

    //Declare session variable to store user id
    $_SESSION['user'];
    $response = [];
    //Grab posted data
    $email = htmlspecialchars(trim($_POST['email']));

    //Nip this in the bud immediately
    $customer = new Customer();

    //Check if the email already exists
    $checkEmail = $customer->checkEmail($email);
    //Control flow for checking email record
    if($checkEmail != 0)
    {
        $response['success'] = false;
        $response['message'] = "This email is already associated with an account. Please login.";
        echo json_encode($response);
        return;
    }

    //Store the sent PW and name information
    $pw = htmlspecialchars(trim($_POST['pass']));
    $first = htmlspecialchars(trim($_POST['first']));
    $last = htmlspecialchars(trim($_POST['last']));

    //Encrypt password
    $pw = md5($pw);

    $validate = $customer->addUser($first, $last, $email, $pw);

    if($validate['valid'] > 0)
    {
        $response['success'] = true;
        $response['message'] = "Account successfully created";
        $response['id'] = $validate['uID'];
        //Set the user session variable
        $_SESSION['user'] = $validate['uID'];
    }

    echo json_encode($response);

?>