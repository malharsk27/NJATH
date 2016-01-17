<?php
function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

/**
 * this function gives response after submitting some data to any URl.
 * Used here for the response of the anwesha login page
 */
function do_post_request($url, $data, $optional_headers = null){
    $params = array('http' => array(
                    'method' => 'POST',
                    'content' => $data
                ));
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp)
    {
        throw new Exception("Problem with $url, $php_errormsg");
    }
    $response='';
    while (!feof($fp))
    {
        $response = $response.fgets($fp);
    }
    if ($response === false)
    {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }

    fclose($fp);
    return $response;
}

/**
 * Returns false if username already exists
 */
function checkUsername($user) {
    require_once "./support/dbcon.php";
    global $db_connection;
    $query = "SELECT COUNT(*) FROM `Contestants` WHERE `username` = '$user'";
    // var_dump($query);   
    $res2 = mysqli_fetch_assoc(mysqli_query($db_connection,$query));
    mysqli_close($db_connection);
    return intval($res2["COUNT(*)"]) == 0;
}

function check() {
    global $error;
    global $CONST;
    $user = $_POST["usernamesignup"];
    $anw = $_POST["anweshasignup"];
    $pass = $_POST["passwordsignup"];

    /**
     * validating captcha
     */
    if(empty($_SESSION['6_letters_code'] ) || $_SESSION['6_letters_code'] != $_POST['6_letters_code']){
        $error["msg"] = "The captcha code does not match!";
        $error["component"] = "captcha";
        return;
    }

    /**
     * getting the status of login in anwesha website
     */
    $url = 'http://2016.anwesha.info/login/';
    $data = array ('username' => $anw,'password' => $pass);
    $data = http_build_query($data);
    $reply = do_post_request($url, $data);
    $res = (array)json_decode($reply);

    /**
     * getting the login status
     */
    if (!$res['status']){
        $error["msg"] = "Authentication failed. You entered an incorrect Anwesha ID or password.";
        $error["component"] = "anwesha";
        return;
    }
    
    $hash = sha1($pass);

    if (!checkUsername($user)){
        $error["msg"] = "Username already taken.";
        $error["component"] = "username";
        return;
    }

    if (!filter_var($user, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[\w]{5,15}$/')))) {
        $error["msg"] = "Inappropriate username (5 to 15 character needed)";
        $error["component"] = "username";
        return;
    }
    // var_dump($CONT);
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