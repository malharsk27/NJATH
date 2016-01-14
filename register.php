<?php
$from = "registerpage";
require './support/check.php';

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
    global $_POST;
    global $CONST;
    $user = $_POST["usernamesignup"];

    $anw = $_POST["anweshasignup"];
    $anw = intval(substr($anw, 3));
    $pass = $_POST["passwordsignup"];
    $hash = sha1($pass);
    if (!filter_var($user, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^(\w){1,15}$/')))) {
        $error["msg"] = "Inappropriate username";
        $error["component"] = "username";
    }
    if (!verify_anw_id($anw, $hash)) {
        $error["msg"] = "Inappropriate Anwesha ID.";
        $error["component"] = "anwesha";
    }
    
    if(empty($_SESSION['6_letters_code'] ) ||
      strcmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
    {
    //Note: the captcha code is compared case insensitively.
    //if you want case sensitive match, update the check above to
    // strcmp()
        $error["msg"] = "The captcha code does not match!";
        $error["component"] = "captcha";
    }

    if (isset($error)) {
        return $error;
    }

    require_once './support/dbcon.php';
    global $db_connection;

    $query = "SELECT COUNT(*) FROM `Contestants` WHERE `username` = '{$_POST["usernamesignup"]}'";
    $result = mysqli_fetch_array(mysqli_query($db_connection, $query));
    if ($result["COUNT(*)"] != 0) {
        $error["msg"] = "Username already taken! Please choose another!";
        $error["component"] = "username";
    }
    if (isset($error)) {
        return $error;
    }

    $cost = 10;

    $query = "UPDATE `Contestants` SET `username`='$user' WHERE Contestants.pId = $anw;";
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
    unset($q);
    unset($l);
    // require './mail/mail.php';
    // die("dead");
    return FALSE;
}

if (isset($_POST["usernamesignup"]) && isset($_POST["anweshasignup"]) &&
        isset($_POST["passwordsignup"])) {
    $error_msg = check();
}
?>


<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="navbar.css" type="text/css" rel="stylesheet" />
        <link href="register.css" type="text/css" rel="stylesheet" />
        <title>NJATH - ANWESHA 2k16 Registration</title>
        <script language="JavaScript" src="js/gen_validatorv31.js" type="text/javascript"></script>
    </head>
    <body>
        <nav class="cl-effect-9">
            <a href="index.php" >
                <span>Login</span>
                <span>Start the Awesome</span>
            </a>
            <a href="leaderboard.php">
                <span>Leaderboard</span>
                <span>View the Leaderboard</span>
            </a>
            <a href="http://www.facebook.com/iit.njath/app_202980683107053">
                <span>Forum</span>
                <span>The Discussion Forum</span>
            </a>
            <a href="http://www.iitp.ac.in">
                <span>IIT Patna</span>
                <span>All about our college</span>
            </a>
            <a href="rules.php">
                <span>Rules</span>
                <span>The law of the Land!!!</span>
            </a>
        </nav>


        <div id="wrapper">
            <form id="register" action="register.php" method="POST" autocomplete="on">
                <h1> Sign up </h1> 
                <?php if (isset($error_msg["component"])) {
                    ?>
                    <p id="error-msg">
                        <?php
                        echo $error_msg["msg"];
                        ?>
                    </p>
                    <?php
                }
                ?>
                <p> 
                    <label for="usernamesignup" class="uname" data-icon="u">Your username</label>
                    <input id="usernamesignup" name="usernamesignup" required="required" 
                           type="text" placeholder="eg. thejoker69" 
                           value="<?php if (isset($error_msg["component"]) && $error_msg["component"] != "username") echo $_POST["usernamesignup"]; ?>"
                           class="<?php if (isset($error_msg["component"]) && $error_msg["component"] == "username") echo 'error-component'; ?>"/>
                <p>
                    <label for="anweshasignup" class="anwesha" data-icon="a">Anwesha ID</label>
                    <input id="anweshasignup" name="anweshasignup" required="required"
                           type="text" placeholder="eg. ANW0000"
                           value="<?php if (isset($error_msg["component"]) && $error_msg["component"] != "anwesha") echo $_POST["anweshasignup"]; ?>"
                           class="<?php if (isset($error_msg["component"]) && $error_msg["component"] == "anwesha") echo 'error-component'; ?>"/>
                </p>

                <p> 
                    <label for="passwordsignup" class="youpasswd" data-icon="p">Your password </label>
                    <input id="passwordsignup" name="passwordsignup" required="required" 
                           type="password" placeholder="eg. X8df!90EO"
                           class="<?php if (isset($error_msg["component"]) && $error_msg["component"] == "password") echo 'error-component'; ?>"/>
                </p>
                
                <p>
                <img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
                <label for='message'>Enter the code above here :</label><br>
                <input id="6_letters_code" name="6_letters_code" type="text"><br>
                <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                </p>
                <p class="signin button"> 
                    <input type="submit" value="Sign up"/> 
                </p>
                <p class="change_link">Already a member ?<a href="index.php" class="to_register"> Go and log in </a>
                </p>
            </form>
        </div>
        <?php
        if (isset($error_msg) && $error_msg != TRUE) {
            ?>
            <div id="done-display">
                <div>
                    <h2> Registration SUCCESSFUL!! </h2>
                    <p>  Click <a href="rules.php">here</a> to continue. </p>
                </div>
            </div>
            <?php
        }
        ?>
        <script language='JavaScript' type='text/javascript'>
        function refreshCaptcha()
        {
            var img = document.images['captchaimg'];
            img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
        }
        </script>
    </body>
</html>
