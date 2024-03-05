<?php

session_start();
if (!isset($_SESSION["email"])) {

    //$_SESSION["login"] = false;
    $_SESSION["email"] = "";
    $_SESSION["profilename"] = "";

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home PHP webpage</title>
    <meta charset="utf-8">
    <meta name="description" content="Web development">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="your name">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
    </header>
    <div class="content">

        <?php
        // check if email exist in the table.
        function check_if_email_valid($email, $num_rows)
        {

            if (!empty($email)) {

                if ($num_rows > 0) {
                    return true;


                } else {


                    echo "<p>" . "Email doesn't exist. Please " . "<a href = 'signup.php'>Sign up</a>" . "</p>";
                    return false;
                }


            } else {
                echo "<p> Enter your email </p>";
                return false;
            }

        }


        ?>


        <?php

        require("./functions/settings.php");


        if (isset($_POST["email"]) && isset($_POST["password"])) {

            $email = $_POST["email"];
            $password = $_POST["password"];

            $_SESSION["email"] = $email;

            $dbconn = @mysqli_connect($host, $user, $pswd);

            if (!$dbconn) {
                die("<p>The database server is not available.</p>");
            } else {
                $dbSelect = @mysqli_select_db($dbconn, $dbnm);



                $qry = "SELECT friend_email, profile_name, password FROM friends WHERE friend_email = '$email'";
                $currUserQryResult = mysqli_query($dbconn, $qry);

                if (check_if_email_valid($email, mysqli_num_rows($currUserQryResult))) {
                    $rowUser = mysqli_fetch_array($currUserQryResult);

                    // check if the password of the user found by the email entered matched to this user.
                    if ($password == $rowUser[2]) {
                        $_SESSION["profilename"] = $rowUser[1];
                        header("Location: " . "friendlist.php");


                    } else {

                        echo "<p>Wrong Password</p>";

                    }




                }

            }

        }


        ?>










        <h2> My Friend System </h2>

        <h2> Log in page </h2>

        <form action="login.php" method="POST">


            <p>Email </p>
            <input type="text" name="email" value='<?php echo $_SESSION["email"]; ?>' />

            <br></br>

            <p>Password</p>
            <input type="text" name="password" />

            <br></br>
            <input type="submit" value="Log in" />
            <input type="reset" value="Clear" />

        </form>





    </div>
</body>

</html>