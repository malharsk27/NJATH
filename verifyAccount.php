<?php 

if(isset($_GET['id']) && !empty ($_GET['id']) && isset($_GET['token']) && !empty($_GET['token'])){
	if (!filter_var($_GET["id"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[\w]{1,15}$/')))) {
        die("Inappropriate username " . $_GET['id']);
        // $error["component"] = "username";
    }
    if (!filter_var($_GET["token"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[0-9A-Za-z]{40}$/')))) {
        die("Inappropriate token " . $_GET["token"]);
        // $error["component"] = "username";
    }

    $user = $_GET['id'];
    $token = $_GET['token'];
    require'./support/dbcon.php';
    $query = "UPDATE `Contestants` SET `verified`= NULL WHERE `Username` = '$user' and `verified` = '$token'";
    // die("id = ". $user ." token = " . $token . " query = " . $query);
    mysqli_query($db_connection, $query);
    mysqli_close($db_connection);

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
	header('Location: '. $home_url);	
}

?>