<?php
$from = "registerpage";
require_once 'function.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();
    check();

    if (isset($error)) {
        require 'signupPage.html';
        die();
    }
    $db_username = "root";
    $db_password = "";
    $db_connection = mysqli_connect("localhost", $db_username, $db_password, "anwesha_njath");
    $user = $_POST["usernamesignup"];
    $anw = $_POST["anweshasignup"];
    $anw = intval(substr($anw, 3));
    $pass = $_POST["passwordsignup"];
    $hash = sha1($pass);
    $query = "SELECT COUNT(*) FROM `Contestants` WHERE `username` = '$user'";
    $res= mysqli_query($db_connection,$query);
    $result = mysqli_fetch_assoc($res);
    if ($result["COUNT(*)"] != 0) {
        $error["msg"] = "Username already taken! Please choose another!";
        $error["component"] = "username";
    }
    if (isset($error)) {
        require 'signupPage.html';
        die();
    }

    $cost = 10;

    $query = "UPDATE `Contestants` SET `username`='$user' WHERE pId = $anw;";
    mysqli_query($db_connection, $query);

    $query = "INSERT INTO `ContestantsData`(`Username`) VALUES ('{$user}')";
    mysqli_query($db_connection, $query);

    $query = "CREATE TABLE `Questions-{$user}` ("
            . "`Question Number` varchar(2) NOT NULL, "
            . "`Question ID` varchar(3) NOT NULL, "
            . "`Hinted` int(11) DEFAULT '0', "
            . "`Time Opened` int(11) DEFAULT '-1', "
            . "`Time Answered` int(11) DEFAULT '-1', "
            . "`Obtained Score` int(11) NOT NULL DEFAULT '0', "
            . "`Attempts` int(11) DEFAULT '0', "
            . "PRIMARY KEY (`Question Number`), "
            . "UNIQUE KEY `Question Number` (`Question Number`), "
            . "UNIQUE KEY `Question ID` (`Question ID`))";
    $val = mysqli_query($db_connection, $query);

    $query = "";
    $buffer_size = $CONST["buffer"];
    for ($l = 1; $l <= $CONST["levels"]; $l++) {
        for ($q = 1; $q <= $CONST["questions"]; $q++) {
            $random = rand(1, $buffer_size);
            $query = "INSERT INTO `Questions-{$user}` (`Question Number`, `Question ID`) "
                    . "VALUES ('{$l}{$q}', '{$l}{$q}{$random}');";
            mysqli_query($db_connection, $query);
        }
    }
    mysqli_close($db_connection);
    $success = true;
    require 'signupPage.html';
} else {
    require 'signupPage.html';
}
?>
