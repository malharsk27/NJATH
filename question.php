<?php

/*
 * Copyright (C) 2015 Sunny Narayan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//header('Location: http://njath.anwesha.info/closed.html');
$from = "questionpage";
require_once 'function.php';
require_once './support/check.php';

function check_answer($ans) {
    $quesFor = $_SESSION["question"];
    $browserOfuser = NULL;

    global $db_connection;
    global $CONST;

    $query = "SELECT `Q`.*,`Q-U`.* FROM `Questions` AS `Q` "
            . "LEFT JOIN `Questions-{$_SESSION["username"]}` AS `Q-U` ON `Q`.`Question ID` = `Q-U`.`Question ID` "
            . "LEFT JOIN `QuestionSolves` AS `S` ON `Q`.`Question ID` = `S`.`Question ID` "
            . "WHERE `Q`.`Question ID` = '{$_SESSION["question"]}'";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
/////////////////////////
    if ($query["Time Answered"] != "-1") {
        $_SESSION["question"] = "";
        mysqli_close($db_connection);
        header("Location: ./profile.php");
        die();
    }
////////////////////////////////
    $query["Attempts"] ++;

    $query["Check Answer"] = $query["Answer Regular"];

    if (!filter_var($ans, FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[a-z0-9]+$/')))) {
        return "Ooops! Wrong Answer! Keep Trying...";
    }

    if ($query["Check Answer"] != $ans) {
        $result = "UPDATE `Questions-{$_SESSION["username"]}` "
                . "SET `Attempts` = '{$query["Attempts"]}' "
                . "WHERE `Question ID` = '{$_SESSION["question"]}' ";
        mysqli_query($db_connection, $result);
        return "Ooops! Wrong Answer! Keep Trying...";
    }

    $timeAnsw = intval((time() + 59) / 60);

    $incr = intval($CONST["question-score"]);
        push_increase("Question Answered", $incr);

    if ($_SESSION["advance-level"]) {
    	push_increase("Bonus Question", $CONST["bonus-quest"]);
            $incr += $CONST["bonus-quest"];
    }

    sync_scores();

    $result = "UPDATE `Questions-{$_SESSION["username"]}` "
            . "SET `Time Answered`='{$timeAnsw}', `Obtained Score`='{$incr}', `Attempts`='{$query["Attempts"]}' "
            . "WHERE `Question ID` = '{$_SESSION["question"]}';";
    mysqli_query($db_connection, $result);

    $query = "SELECT COUNT(*) FROM `Questions-{$_SESSION["username"]}` AS `Q-U` "
            . "WHERE `Q-U`.`Question Number` LIKE '{$_SESSION["level"]}_'"
            . "AND `Q-U`.`Time Answered` != '-1' ";

    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    if (intval($query["COUNT(*)"]) >= $CONST["advance"]) {
        $_SESSION["advance-level"] = TRUE;
    }

    $query = "SELECT * FROM `QuestionSolves` AS `Q-U` "
            . "WHERE `Q-U`.`Question ID` = '{$_SESSION["question"]}'";

    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
    $query["Solves"] ++;

    if ($query["First Solve"] == -1) {
        $query["First Solve"] = $timeAnsw;
    }
    
    $result = "UPDATE `QuestionSolves` "
            . "SET `Solves` = '{$query["Solves"]}', `First Solve`='{$query["First Solve"]}' "
            . "WHERE `Question ID` = '{$_SESSION["question"]}';";
    mysqli_query($db_connection, $result);
    $_SESSION["question"] = "";
    mysqli_close($db_connection);
    header("Location: ./profile.php");
    die();
}

function check_question() {
    global $_POST;
    if (isset($_POST["answer"])) {
        return check_answer($_POST["answer"]);
    } else {
        return NULL;
    }
}

$wrong_msg = check_question();
unset($_POST);

$query = "SELECT * FROM `Questions` AS `Q` "
        . "LEFT JOIN `Questions-{$_SESSION["username"]}` AS `Q-U` ON `Q-U`.`Question ID`=`Q`.`Question ID` "
        . "WHERE `Q`.`Question ID` = '{$_SESSION["question"]}';";
$question = mysqli_fetch_array(mysqli_query($db_connection, $query));
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>NJATH - Question</title>
        <link href="question.css" rel="stylesheet" type="text/css" />
        <link href="navbar.css" rel="stylesheet" type="text/css" />
    </head>

    <body>

        <nav class="cl-effect-9">
            <a href="./profile.php">
                <span>Profile</span>
                <span>Your homepage</span>
            </a>
            <a href="./rules.php">
                <span>Rules</span>
                <span>The law of the Land!!!</span>
            </a>
            <a href="./leaderboard.php" >
                <span>Leaderboard</span>
                <span>View the Leaderboard</span>
            </a>
            <a href="https://apps.facebook.com/forumforpages/464686653559952/61cb4cc2-6449-4990-a445-9653c65ce60c/0">
                <span>Forum</span>
                <span>The Discussion Forum</span>
            </a>
            
            <a href="www.anwesha.info">
                <span>Anwesha</span>
                <span>Anwesha 2017</span>
            </a>
            
            <a href="./logout.php">
                <span>Logout</span>
                <span>Is it getting too difficult?</span>
            </a>
        </nav>


        <div id="user-info">
            <h2 id="user"><?php echo($_SESSION['username']); ?></h2>
            <h2 id="level"><?php echo substr_replace($question["Question Number"], ".", 1, 0); ?></h2> 
        </div>

        <div id="question-div" class="<?php
        switch ($question["Type"]) {
            case 1: echo "question-text";
                break;
            case 2: echo "question-image";
                break;
            case 3: echo "question-both";
                break;
        }
        ?>"><?php
                 if ((intval($question["Type"]) & 1) == 1) {
                     ?>
                <div id="question-text">
                    <h2><?php
                        if (startsWith($_SESSION["question"], "73")) {
                            echo "Download the file to solve the question";
                        } else {
                            echo $question["Question Text"];
                        }
                        ?></h2>
                    <?php
                    if (startsWith($_SESSION["question"], "73")) {
                        ?>
                        <a href="./images/q_win32.exe">Windows 32bit</a>
                        <a href="./images/q_win64.exe">Windows 64bit</a>
                        <a href="./images/q_lin32">Linux 32bit</a>
                        <a href="./images/q_lin64">Linux 64bit</a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            if ((intval($question["Type"]) & 2) == 2) {
            	/*if (!check_tchest(2)) {
            		?>
            		<img alt="Question Image" src="./images/image.php?q=<?php echo create_tchest_string(2, $_SESSION["salt"]); ?>"/> 
            		<?php
            	}
            	
            	if (!check_tchest(3)) {
            		?>
	                <img alt="Question Image" id="cropped" src="./images/image_exp.php?<?php echo "q=" . create_tchest_string(3, $_SESSION["salt"]) 
	                	. "&amp;img=" . $question["Question Picture"]; ?>"/> 
        	        <?php
            	} else {
                	?>
	                <img src="./images/<?php echo $question["Question Picture"]; ?>"/> 
        	        <?php
             }*/
             ?>
             <img src="./images/<?php echo $question["Question Picture"]; ?>"/>
             <?php   }
            ?>
        </div>


        <div id="form-wrapper">
           <?php
    /*        $query = "SELECT * FROM `ContestantsData` WHERE `Username` = '{$_SESSION["username"]}';";
            $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
            if (intval($question["HinAAted"]) == 0 && intval($query["Hints"]) > 0 && isset($question["Hint"]) && strlen($question["Hint"]) > 0) {
        */        ?>

                <!-- <form method="POST" id="form-hint" action="question.php">
                    <input id="hint" type="hidden" name="hint" value="<?/*php echo $_SESSION["salt"]; */?>"/>
                    <a href='javascript:;' onclick="document.getElementById('form-hint').submit();" id="hint-btn">
                        Show a hint
                        <span><?php /*echo intval($query["Hints"]);*/ ?> hints left</span>
                    </a>
                </form> -->
                <?php
            //}
            ?>

            <form method="POST" onkeypress="return event.keyCode != 13;" id="form-answer" action="question.php">
                <?php if (startsWith($_SESSION["question"], "71")) { ?>
                    <script language="javascript">
                        function unecape(string) {
                            string = "% 23 ^ 13 % 56(0x34, 0x37, 0x38, 0x41, 0x42)";
                            string = "+char+" == "+pass+" + " " ? string + pass : string;
                            char = document.getElementById("password").value;
                            return string;
                        }
                        function check() {
                            var userInput = document.getElementById("ans").value;
                            var pass = unescape('%34%32');
                            if (userInput == pass) {
                                document.getElementById('form-answer').submit();
                            } else {
                                alert("Wrong password.");
                            }
                        }
                    </script>
                    <input id="ans" name="answer" placeholder="Your answer here..." autocomplete="off"/>
                <?php } else if (startsWith($_SESSION["question"], "74")) {
                    ?>
                    <select name="answer" id="ans"  action="question.php">
                        <option value="1" selected>Pasty Cline</option>
                        <option value="2">Lauren Oliver</option>
                        <option value="3">Sehun</option>   
                        <option value="4">Kai</option>
                    </select>
                <?php } else { ?>
                    <input id="ans" name="answer" placeholder="Your answer here..." autocomplete="off"/>
                <?php }
                ?>
                <a href='javascript:;' onclick="<?php
                if (startsWith($_SESSION["question"], "71")) { 
                    echo "check();";
                } else {
                    echo "document.getElementById('form-answer').submit();";
               }
                ?>" id="submit-btn">
                    <span class="btn-text">Submit</span> 
                    <span class="btn-expandable"><span class="btn-slide-text"><?php
                            $msg[0] = "Are you sure??";
                            $msg[1] = "May I lock it?";
                            $msg[2] = "Double check!!";
                            $msg[3] = "Easy, aint it?";
                            $msg[4] = "Very peculiar!";
                            $msg[5] = "Is that sweat?";
                            $idx = rand(0, 5);
                            echo $msg[$idx];
                            ?></span>
                        <span class="btn-icon-right"><span></span></span></span>
                </a>
            </form>
        </div>

        <?php
        /*if (intval($question["Attempts"]) >= intval($CONST["tchest-tries"]) && !check_tchest(0)) {
            echo '<!-- ';
            echo "Try visiting " . $CONST["njath-home"] . "tchest.php?q=" . create_tchest_string(0, $_SESSION["salt"]);
            echo ' -->';
        }*/
        ?>
        
        <?php
        /*if (intval($question["Hinted"]) == 1) {
            echo '<!-- ';
            echo " Hint: " . $question["Hint"];
            echo ' -->';
        }*/
        ?>


        <?php
        if (isset($wrong_msg)) {
            ?>

            <div id="complete-hide">
                <div id="message-display">
                    <h2><?php echo $wrong_msg; ?></h2>
                </div>
            </div>

            <?php
            unset($wrong_msg);
        }
        ?>
        <!-- 
		{
			{
				{
					Answer : theeasternsails
					This is called the pyramid of doom. For more information, google pyramid of doom.
				}
			}
		}
	-->
    </body>
</html>  
