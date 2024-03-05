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
            <li><a href='friendlist.php'>Friend Lists</a></li>
            <li><a href='logout.php'>Logout</a></li>
        </ul>
    </nav>
</header>

<body>

    <div class="content">
        <?php

        session_start();
        if (!isset($_SESSION["friendsArray"]) && !isset($_SESSION["page"])) {
            $_SESSION["friendsArray"] = [];
            $_SESSION["page"] = 0;

        }


        require("./functions/settings.php");




        //find the current friends of the current login in user.
        //it returns array of friends
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




        // find how many mutual friends the current login user has the availaible friends to add/
        function findMutualFriends($dbconn, $friendsArray, $friendEmail)
        {

            $friendsIDs = findFriends($dbconn, $friendEmail);
            $count = 0;

            foreach ($friendsIDs as $id) {

                if (in_array($id, $friendsArray)) {
                    $count++;
                }

            }
            return $count;



        }

        function sqlRecords($qryResult)
        {

            $row = mysqli_fetch_row($qryResult);

            $rows = [];
            //array_push($rows, $row);
            $i = 0;
            while ($row) {

                array_push($rows, $row);

                $i++;
                $row = mysqli_fetch_row($qryResult);

            }

            return $rows;

        }


        // this function find all the friends the current loged in user can add.
        function findNotFriends($dbconn, $friendsArray, $currUserId)
        {
            $allUsersQry = "SELECT friend_id, friend_email, profile_name, num_of_friends FROM friends ORDER BY profile_name";

            $qryResult = mysqli_query($dbconn, $allUsersQry);


            $users = [];

            $newUsersArray = [];

            $rows = sqlRecords($qryResult);


            $currUsers = 0;

            for ($i = 0; $i < count($rows); $i++) {

                if (!in_array($rows[$i][0], $friendsArray) && $rows[$i][0] != $currUserId) {

                    $countOfMutFriends = findMutualFriends($dbconn, $friendsArray, $rows[$i][1]);


                    array_push($newUsersArray, array($rows[$i][0], $rows[$i][1], $rows[$i][2], $rows[$i][3], $countOfMutFriends));
                    $users[$currUsers] = $newUsersArray;

                    if (count($users) != 0) {

                        if (count($users[$currUsers]) == 5) {

                            $currUsers++;
                            $newUsersArray = [];

                        }
                    }
                }

            }

            if (count($users) - 1 == $currUsers) {

                if (count($users[$currUsers]) == 0) {
                    unset($users[$currUsers]);
                }
            }


            return $users;

        }



        // create a table for the current loged in user to be able to add friends.
        function createFriendTable($notFriendsUsers)
        {

            echo "<table width='100%' border='2'>";

            for ($i = 0; $i < count($notFriendsUsers); $i++) {

                $emailParam = $notFriendsUsers[$i][1];
                $idParam = $notFriendsUsers[$i][0];

                echo "<tr><td>{$notFriendsUsers[$i][2]}</td>";
                echo "<td>" . $notFriendsUsers[$i][4] . " mutual friends" . "</td>";
                echo "<td><form method = 'POST' action = 'friendadd.php'>

        <input
        <input type = 'hidden' name = 'emailParam' value = '$emailParam'/>
        <input type = 'hidden' name = 'idParam' value = '$idParam'/>
        <input type = 'submit' value = 'Add Friend' name = 'addFriend'/>
        
        </form></td></tr>";

                // echo "<td>"."Mutual Friends ".$notFriendsUsers[$i][4]."</td><;
            }


            echo "</table>";

        }



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
                    $numFriends = $rowUser[1] + 1;

                    $deleteFriendQry = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES ('$currUserId', '$idParam')";

                    $currUserQryResult = mysqli_query($dbconn, $deleteFriendQry);


                    $updateCurrUserQry = "UPDATE friends SET num_of_friends = '$numFriends' WHERE friend_id = $currUserId";

                    $currUserQryResult = mysqli_query($dbconn, $updateCurrUserQry);





                }
            }

        }





        if (isset($_SESSION["email"]) && isset($_SESSION["profilename"])) {

            if (!empty($_SESSION["email"])) {

                $email = $_SESSION["email"];
                $profileName = $_SESSION["profilename"];

                // echo $email;
                // echo $profileName;
        
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
                        $notFriendsUsers = findNotFriends($dbconn, $friendsArray, $rowUser[0]);






                        if ($_SESSION["page"] > count($notFriendsUsers) - 1) {
                            $_SESSION["page"] = count($notFriendsUsers) - 1;
                        }




                        if (count($notFriendsUsers) == 0) {
                            $_SESSION["page"] = 0;
                        }


                        echo "<h2>My Friend System</h2>";
                        echo "<h2>" . $rowUser[2] . "'s" . " Add Friend Page" . "</h2>";
                        if (count($notFriendsUsers) != 0) {

                            $page_num = $_SESSION["page"];
                            echo "<h3>" . "Total number of friends is " . count($notFriendsUsers[$page_num]) . "</h3>";


                        } else {
                            echo "<h3>" . "Total number of friends is 0" . "</h3>";
                        }


                        if (count($notFriendsUsers) > 0) {
                            $_SESSION["friendsArray"] = $notFriendsUsers;
                            $page_num = $_SESSION["page"];

                            //echo (count($_SESSION["friendsArray"]));
                            createFriendTable($_SESSION["friendsArray"][$page_num]);
                        }




                        if ($_SESSION["page"] != 0) {

                            echo "<li><a href = 'previousfriends.php'>Previous</a></li> ";
                        }


                        if ($_SESSION["page"] < (count($_SESSION["friendsArray"]) - 1)) {

                            echo "<li><a href = 'nextfriends.php'>Next</a></li> ";

                        }









                    }
                }



            }else {

                echo "<p> Please sign up or login in</p>";
                echo "<li> <a href = 'login.php'>Log-In</a></li> ";
                echo "<li> <a href = 'signup.php'>Sign-Up</a></li> ";
            }


        }else {

            echo "<p> Please sign up or login in</p>";
            echo "<li> <a href = 'login.php'>Log-In</a></li> ";
            echo "<li> <a href = 'signup.php'>Sign-Up</a></li> ";
        }


        ?>

    </div>
</body>

</html>