<?php
//session_start();
//$_SESSION = array();//unset all session variables
//session_destroy();

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



<?php

//    session_start();
//    $_SESSION = array();//unset all session variables
//    session_destroy();



?>


<?php

//require("settings.php");

function check_if_table_empty($dbconn, $table_name)
{
    $qry = "SELECT COUNT(*) as count FROM $table_name";

    $result = mysqli_query($dbconn, $qry);

    //$count = mysqli_fetch_lengths($result);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row["count"];
        if ($count == 0) {
            //echo "The table is empty.";
            return false;
        } else {

            //echo "The table is not empty. It contains $count rows.";
            return true;
        }
    }


}


?>

<body>



    <header>
        <nav>
            <ul>
                <li><a href="signup.php">Sign-Up</a></li>
                <li><a href="login.php">Login-In</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
        </nav>
    </header>




    <div class="content">


        <?php

        //echo "<h1>Job Vacany Posting System </h1>";
        require("./functions/settings.php");


        echo "<h1>My Friend System</h1>";
        echo "<h1>Assignement Home Page</h1>";

        echo "<p>Name: Karim Aly </p>";
        echo "<p>StudentID: 103600937 </p>";
        echo "<p>Email: 103600937@student.swin.edu.au</p>";


        echo "<p>I declare that this assignment is my individual work. I have not worked collaboratively, nor have I
        copied from any other student’s work or from any other source</p>";



        $dbconn = @mysqli_connect($host, $user, $pswd);


        if (!$dbconn) {
            die("<p>The database server is not available.</p>");
        } else {
            $dbSelect = @mysqli_select_db($dbconn, $dbnm);

            if (!$dbSelect) {
                die("<p>The database is not available.</p>");
            } else {





                $createFrTblSql = "CREATE TABLE IF NOT EXISTS friends (friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                friend_email VARCHAR(50) NOT NULL,
                password VARCHAR(20) NOT NULL,
                profile_name varchar(30) NOT NULL,
                date_started DATE NOT NULL,
                num_of_friends integer unsigned
                 )";

                $createFrTblSQLRslt = mysqli_query($dbconn, $createFrTblSql);

                if ($createFrTblSQLRslt != true) {
                    echo "<p> Creating friends table failed </p>";
                }


                $createMyFrTblSql = "CREATE TABLE IF NOT EXISTS myfriends (
                    friend_id1 INT NOT NULL,
                    friend_id2 INT NOT NULL
                 )";

                $createMyFrTblRslt = mysqli_query($dbconn, $createMyFrTblSql);

                if ($createMyFrTblRslt != true) {
                    echo "<p> Creating My friends table failed </p>";
                }

                check_if_table_empty($dbconn, "friends");


                if (!check_if_table_empty($dbconn, "friends")) {

                    $friend1SQL = "INSERT INTO friends (friend_id, friend_email, password, profile_name, date_started, num_of_friends) VALUES (1,'karimAly@gmail.com', 'kAly1', 'Karim Aly', '2012-9-22', 2)";
                    $friend2SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (2,'mohamedAly@gmail.com', 'mAly2', 'Mohamed Aly', '2012-11-22', 2)";
                    $friend3SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (3,'youssefAly@gmail.com', 'yAly3', 'Youssef Aly', '2016-11-22', 2)";
                    $friend4SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (4,'faridaAly@gmail.com', 'fAly4', 'Farida Aly', '2016-9-22', 1)";
                    $friend5SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (5,'osamaAly@gmail.com', 'oAly5', 'Osama Aly', '2016-9-22', 1)";
                    $friend6SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (6,'tom@gmail.com', 'tom2', 'Tom Nick', '2018-9-22', 2)";
                    $friend7SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (7,'nick@gmail.com', 'nick1', 'Nick Jamie', '2014-9-22', 1)";
                    $friend8SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (8,'ahmad@gmail.com', 'ahmad3', 'Ahmad Mohammed', '2014-9-12', 1)";
                    $friend9SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (9,'zen@gmail.com','zen14', 'ZEN ZEN', '2019-12-22', 2)";
                    $friend10SQL = "INSERT INTO friends (friend_id,friend_email, password, profile_name, date_started, num_of_friends) VALUES (10,'tim@gmail.com', 't167', 'Tim Zen', '2020-1-22', 6)";

                    $friendsSQL = array(
                        $friend1SQL,
                        $friend2SQL,
                        $friend3SQL,
                        $friend4SQL,
                        $friend5SQL,
                        $friend6SQL,
                        $friend7SQL,
                        $friend8SQL,
                        $friend9SQL,
                        $friend10SQL
                    );


                    for ($i = 0; $i < count($friendsSQL); $i++) {
                        $result = mysqli_query($dbconn, $friendsSQL[$i]);
                    }

                }



                if (!check_if_table_empty($dbconn, "myfriends")) {
                    $friend1 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (1, 2)";
                    $friend2 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (1, 3)";
                    $friend3 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (2, 1)";
                    $friend4 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (2, 3)";
                    $friend5 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (3, 2)";
                    $friend6 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (3, 1)";
                    $friend7 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (4, 3)";
                    $friend8 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (5, 4)";
                    $friend9 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (6, 5)";
                    $friend10 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (6, 7)";
                    $friend11 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (7, 6)";
                    $friend12 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (8, 9)";
                    $friend13 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (9, 10)";
                    $friend14 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (9, 3)";
                    $friend15 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 9)";
                    $friend16 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 1)";
                    $friend17 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 2)";
                    $friend18 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 3)";
                    $friend19 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 4)";
                    $friend20 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 5)";
                    //$friend20 = "INSERT INTO myfriends(friend_id1, friend_id2) VALUES (10, 6)";
        



                    $friendRelations = array(
                        $friend1,
                        $friend2,
                        $friend3,
                        $friend4,
                        $friend5,
                        $friend6,
                        $friend7,
                        $friend8,
                        $friend9,
                        $friend10,
                        $friend11,
                        $friend12,
                        $friend13,
                        $friend14,
                        $friend15,
                        $friend16,
                        $friend17,
                        $friend18,
                        $friend19,
                        $friend20
                    );



                    for ($i = 0; $i < count($friendRelations); $i++) {
                        $result = mysqli_query($dbconn, $friendRelations[$i]);
                    }


                }

                //mysqli_commit($dbconn);
        

                //check_if_table_empty($dbconn, "friends");
        












            }



            mysqli_close($dbconn);



        }


        ?>





    </div>


</body>




</html>