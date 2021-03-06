<?php
    require_once '../../init.php';
    require_once CLASSES_PATH . '/DBConnection.php';
    require_once CLASSES_PATH . '/KLogger.php';

    session_start();

    //get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $errors = array();

    $dbc = new DBConnection();
    $logger = new KLogger(LOG_PATH . '/log.txt', KLogger::DEBUG);

    ///check if password field is blank
    if(empty($password))
    {
        $errors[] = "Password field cannot be blank.";
    }

    //check if username field is blank
    if(empty($username))
    {
        $errors[] = "Email/Username field cannot be blank.";
    }

    //errors check
    if(count($errors) > 0)
    {
        $logger->LogWarn(print_r($errors,1));
        $_SESSION['messages'] = $errors;
        $_SESSION['class'] = "fail";
        $_SESSION['form_data'] = $_POST;
        header('Location:  ../../login.php');
        exit;
    }

    else
    {
        $user = $dbc->userExists($username, $password);
        $_SESSION['authenticated'] = isset($user['username']) ? true : null;

        if($_SESSION['authenticated'] == true)
        {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location:  ../../login.php');
            exit;
        }

        else
        {
            $_SESSION['messages'][] = "The email/password you've entered is incorrect.";
            $_SESSION['class'] = "fail";
            $_SESSION['form_data'] = $_POST;
            $logger->LogInfo("auth = false");
            header('Location:  ../../login.php');
            exit;
        }
    }
?>
