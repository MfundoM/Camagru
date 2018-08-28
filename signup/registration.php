<?php
function sign_up($email, $username, $password, $host)
{
    include_once '../config/database.php';
    include_once '../emails/email.php';

    //converting a string to lowercase
    $email = strtolower($email);

    try {
        $db = new PDO($DB_DSN_NAME, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //preparing an SQL statement for execution
        $query = $db->prepare("SELECT id FROM users WHERE username=:username OR email=:email");
        $query->execute(array(':username' => $username, ':email' => $email));

        //fetching all result rows as an associative array, a numeric array, or both
        if ($val = $query->fetch())
        {
            $_SESSION['error'] = "User already exist";
            //freeing up the connection to the server so that other SQL statements may be issued
            $query->closeCursor();
            return (-1);
        }
        //frees up the connection to the server so that other SQL statements may be issued
        $query->closeCursor();

        //encrypting password
        $password = hash("whirlpool", $password);

        //preparing an SQL statement for execution
        $query = $db->prepare("INSERT INTO users (username, email, password, token) VALUES (:username, :email, :password, :token)");

        //generating a unique ID based on the microtime (current time in microseconds)
        //rand() generates a random integer
        $token = uniqid(rand(), true);
        $query->execute(array(':username' => $username, ':email' => $email, ':password' => $password, ':token' => $token));
        verify_email($email, $username, $token, $host);
        $_SESSION['signup_success'] = true;
        return (0);
    }
    //retrieving the exception and creating an object ($e) containing the exception information
    catch (PDOException $e) {
        $_SESSION['error'] = "ERROR: ".$e->getMessage();
    }
}
?>