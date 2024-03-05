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


<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
</header>



<body>



    <div class="content">

        <?php

        session_start();
        if (!isset($_SESSION["profilename"]) && !isset($_SESSION["email"])) {

            //$_SESSION["login"] = false;
            $_SESSION["email"] = "";
            $_SESSION["profilename"] = "";

        }

        require("./functions/settings.php");
        ?>


        <?php


        //check if an email is valid
        // email is considered valid if it meets this criteria
        // 1 - it is in the format example@email.com
        // 2 - it deosn't exist in the table
        function isEmailValid($dbconn, $email)
        {

            $qry = "SELECT friend_email FROM friends WHERE friend_email = '$email'";

            $result = mysqli_query($dbconn, $qry);

            //echo $result;
            $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';


            $numRows = mysqli_num_rows($result);
            //echo $numRows;

            if ($numRows == 1) {
                echo "<p>Email exists.</p>";
                return false;
            } else {
                if (preg_match($pattern, $email)) {
                    return true;
                } else {
                    echo "<p>Invalid email address</p>";

                }

            }


        }

        // check if password is valid 
        // password is considered valid if it contains only letters and numbers.
        // the length of the password must be at least 3.
        function isPasswordValid($password)
        {
            if (preg_match("/^[a-zA-Z0-9]+$/", $password) && strlen($password) >= 3) {
                return true;
            } else {
                echo "<p>Password must be at least 3 characters. Password must contain only letters and numbers.</p>";
                return false;
            }
        }

        // check if the 2 passwords match.
        function isPasswordMatched($password, $cpassword)
        {
            if ($password == $cpassword) {
                return true;
            } else {
                echo "<p> The 2 passwords are not equal</p>";
                return false;
            }
        }


        //check if the profile contain only letters and it is not empty.
        function isProfileNameValid($pName)
        {

            if (preg_match('/^[a-zA-Z ]+$/', $pName) && !empty($pName)) {
                return true;
            } else {
                echo "<p> Profile name must contain only letters and not blank</p>";
                return false;
            }

        }


        ?>


        <?php






        $email = "";
        $profileName = "";


        if (isset($_POST["email"]) && isset($_POST["pname"]) && isset($_POST["password"]) && isset($_POST["cpassword"])) {

            $email = trim($_POST["email"]);
            $profileName = trim($_POST["pname"]);
            $password = $_POST["password"];
            $cpassword = $_POST["cpassword"];
            $todayDate = date('Y-n-j');
            
        
            $_SESSION["email"] = $email;
            $_SESSION["profilename"] = $profileName;



            $dbconn = @mysqli_connect($host, $user, $pswd);

            if (!$dbconn) {
                die("<p>The database server is not available.</p>");
            } else {
                $dbSelect = @mysqli_select_db($dbconn, $dbnm);

                if (!$dbSelect) {
                    die("<p>The database is not available.</p>");
                } else {



                    if (isEmailValid($dbconn, $email) && isProfileNameValid($profileName) && isPasswordValid($password) && isPasswordMatched($password, $cpassword)) {
                        $insertQry = "INSERT INTO friends(friend_email, password, profile_name, date_started, num_of_friends) VALUES('$email', '$password', '$profileName', '$todayDate', 0)";
                        $currUserQryResult = mysqli_query($dbconn, $insertQry);


                     
        

                        // redirect to the friendadd.php page.
                        $target_url = "friendadd.php";
                        header("Location: " . $target_url);
                        
        





                    } else {

                        echo "<li> <a href = 'index.php'> Home </a></li> ";
                        //header("Location: " . "signup.php");
                    }


                }
            }


        } else {



        }



        ?>





        <h2> My Friend System </h2>

        <h2>Registration Page</h2>

        <form action="signup.php" method="POST">

            <p>Email</p>
            <input type="text" name="email" value='<?php echo $_SESSION["email"]; ?>' />
            <p>Profile Name</p>
            <input type="text" name="pname" value='<?php echo $_SESSION["profilename"]; ?>' />
            <p>Password</p>
            <input type="text" name="password" />
            <p>Confirm Password</p>
            <input type="text" name="cpassword" />

            <br></br>
            <input type="submit" value="Regiseter" />
            <input type="reset" value="Clear" />

        </form>


    </div>

</body>


</html>