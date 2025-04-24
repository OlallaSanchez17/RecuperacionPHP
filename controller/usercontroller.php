<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new usercontroller();

    if (isset($_POST["login"])) {
        echo "<p>Login button is clicked.</p>";
        $user->login();
    }
    if (isset($_POST["logout"])) {
        echo "<p>Logout button is clicked.</p>";
        $user->logout();
    }
    if (isset($_POST["register"])) {
        echo "<p>Register button is clicked.</p>";
        $user->register();
    }
}

class usercontroller
{
    private $conn;
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "spmotors";
        $tbname = "users";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else {
            echo "Connected successfully";

            $sqldb = "CREATE DATABASE IF NOT EXISTS $dbname";

            if ($this->conn->query($sqldb) === TRUE) {

                echo "Database created successfully";
            } else {

                echo "Error creating database: " . $this->conn->error;
            }

            $sqltb = "CREATE TABLE IF NOT EXISTS $tbname (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(50),
                    password INT
                )";
            // firstname VARCHAR(30) NOT NULL,                    

            if ($this->conn->query($sqltb) === TRUE) {
                echo "Table MyGuests created successfully";
            } else {
                echo "Error creating table: " . $this->conn->error;
            }
        }
    }


    public function login(): void
    {

        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $this->conn->prepare(query: "SELECT email, password FROM users WHERE email=? AND password=?");

        $stmt->bind_param("ss",  $email,  $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION["login"] = true;
            $_SESSION["email"] = $row["email"];
            $_SESSION["password"] = $row["password"];

            $this->conn->close();

            header(header: "Location: ../view/profile.php");
            exit();
        } else {
            $_SESSION["login"] = false;
            echo "No logged";
        }
    }

    public function logout(): void
    {
        echo "<p>Logout button is clicked and called.</p>";


        session_start(); // Start the session to access session variables
        session_unset(); // Remove all session variables
        session_destroy(); // Destroy the session itself

        echo "<p>You have been logged out.</p>";

        // Optionally, redirect to a login page or home
        header("Location: login.php");
        exit;
    }

    public function register(): void
    {
        echo "<p>Register button is clicked and called.</p>";
    }
}
