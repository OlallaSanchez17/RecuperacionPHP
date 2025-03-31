<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    if (isset($_POST["login"])) {
        echo "<p>Login button is clicked.</p>";
        // $user->login();
    }
    // if (isset($_POST["logout"])) {
    //     echo "<p>Logout button is clicked.</p>";
    //     $user->logout();
    // }
    // if (isset($_POST["register"])) {
    //     echo "<p>Register button is clicked.</p>";
    //     $user->register();
    // }

}

class usercontroller
{

    private $conn;

    public function __construct()
    {

    }

    public function login(): void
    {

    }

    public function logout(): void
    {

    }

    public function register(): void
    {

    }



}


?>