<?php
function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

//Verify Anwesha Id
function verify_anw_id($id,$pass) {
    require_once "./support/dbcon.php";
    global $db_connection;
    
    //anwesha_id and password already verified in database through anwesha website
    $query = "SELECT COUNT(*) FROM `Contestants` WHERE `pId` = '$id' AND `password` = '$pass'; ";
    // var_dump($query);   
    $res2 = mysqli_fetch_assoc(mysqli_query($db_connection,$query));
    mysqli_close($db_connection);
    return intval($res2["COUNT(*)"]) == 1;
}

function check() {
    global $error;
    global $CONST;
    $user = $_POST["usernamesignup"];
    $anw = $_POST["anweshasignup"];
    // $anw = intval(substr($anw, 3));
    $pass = $_POST["passwordsignup"];
    $hash = sha1($pass);

    if (!filter_var($user, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^(\w){1,15}$/')))) {
        $error["msg"] = "Inappropriate username";
        $error["component"] = "username";
        return;
    }
    if (preg_match('/ANW[0-9]{4}/', $anw)) {
        $anw = intval(substr($anw, 3));
    } else {
        $error["msg"] = "Inappropriate id";
        $error["component"] = "anwesha";
        return;
    }
    if (!verify_anw_id($anw, $hash)) {
        $error["msg"] = "Inappropriate Anwesha ID and password.";
        $error["component"] = "anwesha";
        return;
    }

    if(empty($_SESSION['6_letters_code'] ) || $_SESSION['6_letters_code'] != $_POST['6_letters_code']){
    //Note: the captcha code is compared case insensitively.
    //if you want case sensitive match, update the check above to

        $error["msg"] = "The captcha code does not match!";
        $error["component"] = "captcha";
        return;
    }

}
if (!function_exists("destroy_session")) {

    function destroy_session() {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
        session_destroy();
        unset($_SESSION);
        session_write_close();
    }

}

if (!function_exists("push_increase")) {

    function push_increase($text, $value, $both = true) {
        global $_SESSION;
        $n = count($_SESSION["increase"]);
        $_SESSION["increase"][$n]["text"] = $text;
        $_SESSION["increase"][$n]["value"] = $value;
	
	if ($both) {
            $_SESSION["level-score"] += $value;
        }
        $_SESSION["total-score"] += $value;
    }

}

if (!function_exists("sync_scores")) {

    function sync_scores() {
        global $_SESSION;
        require_once './support/dbcon.php';
        global $db_connection;
        $query = "UPDATE `ContestantsData` "
                . "SET `Level Score` = '{$_SESSION["level-score"]}', `Total Score` = '{$_SESSION["total-score"]}' "
                . "WHERE `Username` = '{$_SESSION["username"]}';";
        mysqli_query($db_connection, $query);
    }

}

    
if (!function_exists("load_constants")) {
 function load_constants() {
    global $CONST;
    global $_SESSION;
    
    $l = $_SESSION["level"];
        $CONST["advance-bonus"] = 40 * $l;      
        $CONST["question-cost"] = 20 * $l;
    $CONST["question-score"] = 30 * $l;
    //$CONST["question-hinted-score"] = 20 * $l;
       // $CONST["tchest-bonus"] = 10 * $l;
        $CONST["question-penalty"] = 30 * $l;
        $CONST["bonus-quest"] = 50 * $l;
 }
}
function checkFromVariable_Account($from) {
    return ($from === "questionpage") || $from === "profilepage" || /*$from === "tchestpage" ||*/ $from === "logoutpage";
}

function checkFromVariable_Outside($from) {
    return $from === "homepage" || $from === "registerpage";
}

function checkFromVariable_Common($from) {
    return $from === "rulespage" || $from === "leaderboardpage";
}

?>