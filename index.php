<?php  
// This PHP script demonstrates a simple user registration and login system  

// Start session  
session_start();  

// Database connection setup  
$servername = "localhost";  
$username = "root";  
$password = "password";  
$dbname = "user_database";  

$conn = new mysqli($servername, $username, $password, $dbname);  

// Check connection  
if ($conn->connect_error) {  
    die("Connection failed: " . $conn->connect_error);  
}  

// Function for user registration  
function registerUser($conn, $username, $password) {  
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  
    $sql = "INSERT INTO users (username, password) VALUES ('".$username."', '".$hashed_password."')";  
    
    if ($conn->query($sql) === TRUE) {  
        echo "New record created successfully";  
    } else {  
        echo "Error: " . $sql . "<br>" . $conn->error;  
    }  
}  

// Function for user login  
function loginUser($conn, $username, $password) {  
    $sql = "SELECT password FROM users WHERE username='".$username."'";  
    $result = $conn->query($sql);  
    
    if ($result->num_rows > 0) {  
        // fetch associative array  
        $row = $result->fetch_assoc();  
        if (password_verify($password, $row['password'])) {  
            $_SESSION['username'] = $username;  
            echo "Login successful!";  
        } else {  
            echo "Invalid password.";  
        }  
    } else {  
        echo "No user found with that username.";  
    }  
}  

// Example usage  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    if (isset($_POST['register'])) {  
        registerUser($conn, $_POST['username'], $_POST['password']);  
    } elseif (isset($_POST['login'])) {  
        loginUser($conn, $_POST['username'], $_POST['password']);  
    }  
}  

// Close connection  
$conn->close();  
?>  

<!DOCTYPE html>  
<html>  
<head>  
    <title>User Registration and Login</title>  
</head>  
<body>  
    <h2>Register</h2>  
    <form method="post" action="">  
        Username: <input type="text" name="username" required><br>  
        Password: <input type="password" name="password" required><br>  
        <input type="submit" name="register" value="Register">  
    </form>  

    <h2>Login</h2>  
    <form method="post" action="">  
        Username: <input type="text" name="username" required><br>  
        Password: <input type="password" name="password" required><br>  
        <input type="submit" name="login" value="Login">  
    </form>  
</body>  
</html>