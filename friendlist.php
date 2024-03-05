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
                <li><a href='friendadd.php'>Add Friend</a></li>
                <li><a href='logout.php'>Logout</a></li>
            </ul>
        </nav>

    </header>



    <div class="content">

        <?php
        session_start();

        require("./functions/settings.php");


            ?>


        <?php


        //
        function findFriends($dbconn, $email)
        {

            $currUserIDQry = "SELECT friend_id FROM friends WHERE friend_email = '$email' ";
            $qryResult = mysqli_query($dbconn, $currUserIDQry);
            $row = mysqli_fetch_row($qryResult);

            $friendsArray = array();

            $currUserId = $row[0];

            //array_push($friendsArray, $currUserId);
        
            $notFriendsQry = "SELECT friend_id2 FROM myfriends WHERE friend_id1 = '$currUserId' ";

            $qryResult = mysqli_query($dbconn, $notFriendsQry);




            $row = mysqli_fetch_row($qryResult);

            while ($row) {

                array_push($friendsArray, $row[0]);
                $row = mysqli_fetch_row($qryResult);
            }


            return $friendsArray;

        }

        // this function find the friends of the current loged in user.
        function findFriendUsers($dbconn, $friendProfiles)
        {

            $allUsersQry = "SELECT friend_id, friend_email, profile_name, num_of_friends FROM friends ORDER BY profile_name";

            $qryResult = mysqli_query($dbconn, $allUsersQry);

            $row = mysqli_fetch_row($qryResult);

            $users = [];


            $i = 0;
            while ($row) {



                if (in_array($row[0], $friendProfiles)) {


                    $users[] = array($row[0], $row[1], $row[2], $row[3]);


                }

                $i++;
                $row = mysqli_fetch_row($qryResult);
            }

            return $users;
        }


        function createFriendTable($friendArrayUser)
        {

            echo "<table width='100%' border='2'>";

            for ($i = 0; $i < count($friendArrayUser); $i++) {

                $emailParam = $friendArrayUser[$i][1];
                $idParam = $friendArrayUser[$i][0];

                echo "<tr><td>{$friendArrayUser[$i][2]}</td>";
                echo "<td><form method = 'POST' action = 'friendlist.php'>

        <input
        <input type = 'hidden' name = 'emailParam' value = '$emailParam'/>
        <input type = 'hidden' name = 'idParam' value = '$idParam'/>
        <input type = 'submit' value = 'Unfriend' name = 'unfriend'/>
        
        </form></td></tr>";
            }


            echo "</table>";

        }

        ?>


        <?php

        if (isset($_POST["emailParam"]) && isset($_POST["idParam"])) {



            $emailParam = $_POST["emailParam"];
            $idParam = $_POST["idParam"];
            $email = $_SESSION["email"];

            //echo $idParam;
        
            $dbconn = @mysqli_connect($host, $user, $pswd);


            if (!$dbconn) {
                die("<p>The database server is not available.</p>");
            } else {
                $dbSelect = @mysqli_select_db($dbconn, $dbnm);

                if (!$dbSelect) {
                    die("<p>The database is not available.</p>");
                } else {

                    $currUserIDQry = "SELECT friend_id,num_of_friends FROM friends WHERE friend_email = '$email' ";
                    $currUserQryResult = mysqli_query($dbconn, $currUserIDQry);
                    $rowUser = mysqli_fetch_row($currUserQryResult);

                    $currUserId = $rowUser[0];
                    $numFriends = $rowUser[1] - 1;

                    $deleteFriendQry = "DELETE FROM myfriends WHERE friend_id1 = '$currUserId' AND friend_id2 = '$idParam' ";

                    $currUserQryResult = mysqli_query($dbconn, $deleteFriendQry);


                    $updateCurrUserQry = "UPDATE friends SET num_of_friends = '$numFriends' WHERE friend_id = $currUserId";

                    $currUserQryResult = mysqli_query($dbconn, $updateCurrUserQry);





                }
            }

        }



        ?>

        <?php

        if (isset($_SESSION["email"])) {

          
        
            if (!empty($_SESSION["email"])) {


                $email = $_SESSION["email"];

                $dbconn = @mysqli_connect($host, $user, $pswd);


                if (!$dbconn) {
                    die("<p>The database server is not available.</p>");
                } else {
                    $dbSelect = @mysqli_select_db($dbconn, $dbnm);

                    if (!$dbSelect) {
                        die("<p>The database is not available.</p>");
                    } else {

                        $currUserQry = "SELECT friend_id, friend_email, profile_name, num_of_friends FROM friends WHERE friend_email = '$email'";
                        $currUserQryResult = mysqli_query($dbconn, $currUserQry);
                        $rowUser = mysqli_fetch_row($currUserQryResult);



                        $friendsArray = findFriends($dbconn, $email);



                        //echo gettype($friendsArray);
        
                        $friendArrayUser = findFriendUsers($dbconn, $friendsArray);

                        echo "<h2>My Friend System</h2>";
                        echo "<h2>" . $rowUser[2] . "'s" . " Friend List Page" . "</h2>";
                        echo "<h3>" . "Total number of friends is " . count($friendArrayUser) . "</h3>";

                        createFriendTable($friendArrayUser);





                    }
                }

            } else {
                echo "<p> Please sign up or login in</p>";
                echo "<li> <a href = 'login.php'>Log-In</a></li> ";
                echo "<li> <a href = 'signup.php'>Sign-Up</a></li> ";

            }


        } else {

            echo "<p> Please sign up or login in</p>";
            echo "<li> <a href = 'login.php'>Log-In</a></li> ";
            echo "<li> <a href = 'signup.php'>Sign-Up</a></li> ";
        }
        ?>

    </div>

    <body>

</html>