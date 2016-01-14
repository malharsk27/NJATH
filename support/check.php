<?php
require_once './support/dbcon.php';
session_start();
if (!isset($CONST)) {

    $CONST["advance"] = 6;

    $CONST["njath-home"] = "www.anwesha.info/njath/";
    global $db_connection;
    $query = "SELECT COUNT(DISTINCT SUBSTRING(`Question ID`,1,1)) AS `C` FROM `Questions`";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $CONST["levels"] = $query["C"];
    $query = "SELECT COUNT(DISTINCT SUBSTRING(`Question ID`,2,1)) AS `C` FROM `Questions`";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $CONST["questions"] = $query["C"];
    $query = "SELECT COUNT(DISTINCT SUBSTRING(`Question ID`,3,1)) AS `C` FROM `Questions`";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $CONST["buffer"] = $query["C"];

}
// var_dump($_SESSION);
// echo "<br>";
// var_dump($_POST);
// die();
if (!isset($_SESSION["username"], $_SESSION["level"], $_SESSION["question"], $_SESSION["level-score"], $_SESSION["total-score"], $_SESSION["salt"], $_SESSION["prev-salt"], $_SESSION["advance-level"])) {
    //Either just logged in or didnt log in.

    if (!isset($user)) {
        destroy_session();

        //Redirection for not logged in
        if (isset($from)) {
            if (checkFromVariable_Account($from)) {
                header("Location: ./index.php?msg=Please%20log%20in%20first...");
                die();
            } else if (checkFromVariable_Outside($from) || checkFromVariable_Common($from)) {
                //Nothing to do... 
            }
        } else {
            header("Location: ./index.php");
            die();
        }
        return;
    }

    //Just logged in   
    $query = "SELECT * FROM `ContestantsData` WHERE `username` = '{$user}'";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $_SESSION["username"] = $user;
    $_SESSION["level"] = $query["Level"];
    $_SESSION["question"] = "";
    $_SESSION["level-score"] = $query["Level Score"];
    $_SESSION["total-score"] = $query["Total Score"];
    $_SESSION["increase"] = array();
    $_SESSION["prev-salt"] = "";
    $_SESSION["salt"] = "";
    $query = "SELECT COUNT(*) FROM `Questions-{$_SESSION["username"]}` AS `Q-U` "
            . "WHERE `Q-U`.`Question Number` LIKE '{$_SESSION["level"]}_' "
            .       "AND `Q-U`.`Time Answered` != '-1'";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $_SESSION["advance-level"] = intval($query["COUNT(*)"]) >= $CONST["advance"];
    unset($user);
}

load_constants();

$_SESSION["prev-salt"] = $_SESSION["salt"];
$_SESSION["salt"] = sha1(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));

// $query = "SELECT `Disqualified` FROM `Contestants` WHERE `username` = '{$_SESSION["username"]}'";
// $query = mysqli_fetch_array(mysqli_query($db_connection, $query));

// if (!isset($query["Disqualified"]) || $query["Disqualified"] == 1) {
//     destroy_session();
//     mysqli_close($db_connection);
//     header("Location: ./index.php?msg=You%20have%20been%20disqualified...");
//     die();
// }
// unset($query);

if (isset($from)) {
    if (checkFromVariable_Outside($from)) {
        header("Location: ./profile.php");
        die();
    } else if (checkFromVariable_Account($from) || checkFromVariable_Common($from)) {
        //Nothing to do 
    }
} else {
    header("Location: ./profile.php");
    die();
}
?>
