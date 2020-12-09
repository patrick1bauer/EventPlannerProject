<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: http://www.eventplannerproject.com/EventPlannerProject/events/home.php");
    exit;
}
 
// Include config file
require_once "http://www.eventplannerproject.com/EventPlannerProject/loginRegisterConfig.php";
 
// Define variables and initialize with empty values
$name = "";
$password = "";

$name_err = "";
$password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
    // Check if name is empty
    if(empty(trim($_POST["name"])))
    {
        $name_err = "Please enter a username.";
    }
    else
    {
        $name = trim($_POST["name"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter a password.";
    }
    else
    {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($name_err) && empty($password_err))
    {
        // Prepare a select statement
        $sql = "SELECT uid, name, password, super_admin FROM user WHERE name = ?";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameters
            $param_name = $name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if name exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1)
                {                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $uid, $name, $hashed_password, $super_admin);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["uid"] = $uid;
                            $_SESSION["name"] = $name;
                            $_SESSION["super_admin"] = $super_admin;
                            
                            // Create a User Cookie
                            $arr = array(
                                "uid" => $uid,
                                "name" => $name,
                                "super_admin" => $super_admin
                            );
                            setcookie("login", json_encode($arr), time() + 36000);

                            // Redirect user to welcome page
                            header("location: http://www.eventplannerproject.com/EventPlannerProject/events/home.php");
                        }
                        else
                        {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                }
                else
                {
                    // Display an error message if username doesn't exist
                    $name_err = "No account found with that username.";
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h2>Login</h2>
            <p>Please fill in your credentials to login.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                    <label>Username</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                    <span class="help-block"><?php echo $name_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
                <p>Don't have an account? <a href="http://www.eventplannerproject.com/EventPlannerProject/register.php">Sign up now</a>.</p>
            </form>
        </div>    
    </body>
</html>
