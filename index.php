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



require_once 'function.php';
 error_reporting(E_ALL);
 ini_set("display_errors", 1);
function checkLogin() {
    if (!filter_var($_POST["login"], FILTER_VALIDATE_REGEXP, array("options" => array('regexp' => '/^[\w]{5,15}$/')))) {
        $error["msg"] = "Incorrect username";
        $error["type"] = "auth_failed";
        $error["error"] = TRUE;
        return $error;
    }

    $user = $_POST["login"];
    $pass = $_POST["password"];
    global $db_connection;

   //  $query = "SELECT count(*) as count  FROM `Contestants` WHERE `Username` = '{$user}' and `verified` is NULL";
   //  $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
   // //mysqli_close($db_connection);
   //  if ($query["count"] != '1') {
   //      $error["type"] = "verification";
   //      $error["error"] = TRUE;
   //      return $error;
   //  }

    $query = "SELECT * FROM `Contestants` WHERE `username` = '{$user}'";
    $query = mysqli_fetch_array(mysqli_query($db_connection, $query));
   //mysqli_close($db_connection);
    if (!isset($query["password"])) {
        $error["type"] = "auth_failed";
        $error["error"] = TRUE;
        return $error;
    }
    
    $hash = sha1($pass);

    if ($hash != $query["password"]) {
        $error["type"] = "auth_failed";
        $error["error"] = TRUE;
        return $error;
    }

    $error["username"] = $user;
    $error["error"] = FALSE;
    return $error;
}

if (isset($_POST["login"]) && isset($_POST["password"])) {
    require_once './support/dbcon.php';
    $error = checkLogin();
    if (!$error["error"]) {
        $user = $error["username"];
        unset($error);
    }
}
$from = "homepage";
require_once 'function.php';
require './support/check.php';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>NJATH - ANWESHA 2017 - HOME</title>
        <link href="index.css" rel="stylesheet" type="text/css" />
        <link href="navbar.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/g.js"></script>
        <script type="text/javascript" src="js/sliderman.1.3.7.js"></script>
        <link rel="stylesheet" type="text/css" href="sliderman.css" />
    </head>
    <body>

        <nav class="cl-effect-9">
            <a href="register.php">
                <span>Register</span>
                <span>New to the challenge?</span>
            </a>
            <a href="leaderboard.php" >
                <span>Leaderboard</span>
                <span>The Rulers</span>
            </a>
            <a href="https://apps.facebook.com/forumforpages/464686653559952/61cb4cc2-6449-4990-a445-9653c65ce60c/0">
                <span>Forum</span>
                <span>The Discussion Forum</span>
            </a>
            <a href="http://www.iitp.ac.in">
                <span>IIT Patna</span>
                <span>About our college</span>
            </a>
            <a href="http://2017.anwesha.info">
                <span>Anwesha 2017</span>
                <span>The Eastern Sails</span>
            </a>
          
            <a href="rules.php">
                <span>Rules</span>
                <span>The law of the Land!!!</span>
            </a>
        </nav>


        <div class="login">
            <h2> Login </h2>
            <form method="post" action="" class="loginform">
                <p>
                    <label for="login">Username:</label>
                    <input type="text" name="login" id="login" placeholder="Username" autofocus="true"/>
                </p>

                <p>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password"/>
                </p>

                <p class="login-submit">
                    <button type="submit" class="login-button">Login</button>
                </p>

                <?php
                if (isset($error) && $error["error"]) {
                    if ($error["type"] === "auth_failed") {
                        ?>
                        <p class = "error-display">Authentication failed. You entered an incorrect username or password. New User?<a href = "register.php">Register here</a> or Forgot Password? Send a mail to <a href = "mailto:kulkarni.cs15@iitp.ac.in">NJATH</a>.</p>
                        <?php 
                    }
                }
                ?>
            </form>
        </div>
        <!--login form completed-->



        <!--image slider started-->
        <div id="wrapper">

            <div id="examples_outer">

                <div id="slider_container_2">

                   <div id="SliderName_2" class="SliderName_2">
                        <img src="images/1.jpg" width=100% height=100% alt="Demo2 first" title="Demo2 first" usemap="#img1map" />
                        <map name="img1map">
                            <area href="#img1map-area1" shape="rect" coords="100,100,200,200" />
                            <area href="#img1map-area2" shape="rect" coords="300,100,400,200" />
                        </map>
                        <div class="SliderName_2Description">IIT PATNA PRESENTS :: <strong> NJATH</strong></div>
                        <img src="images/2.jpg" width=2000" height="450" alt="Demo2 second" title="Demo2 second" />
                        <div class="SliderName_2Description"><strong>NOT JUST ANOTHER TREASURE HUNT </strong></div>
                        <img src="images/3.jpg" width="2000" height="450" alt="Demo2 third" title="Demo2 third" />
                        <div class="SliderName_2Description"><strong>COMPLETE LEVELS TO ADVANCE</strong> </div>
                        <img src="images/5.jpg" width="2000" height="450" alt="Demo2 fifth" title="Demo2 fifth" />
                        <div class="SliderName_2Description"><strong>SEARCH FOR CLUES</strong> </div>
                        <img src="images/6.jpg" width="2000" height="450" alt="Demo2 sixth" title="Demo2 sixth" />
                        <div class="SliderName_2Description"><strong>STORM YOUR BRAIN</strong> </div>
                        <img src="images/7.jpg" width="2000" height="450" alt="Demo2 seventh" title="Demo2 seventh" />
                        <div class="SliderName_2Description"><strong>READ THE QUESTION CAREFULLY</strong> </div>
                        
                    </div> 
                    <div class="c"></div>
                    <div id="SliderNameNavigation_2"></div>
                    <div class="c"></div>

                    <script type="text/javascript">
                        effectsDemo2 = 'rain,stairs';
                        var demoSlider_2 = Sliderman.slider({container: 'SliderName_2', width: 700, height: 450, effects: effectsDemo2,
                            display: {
                                autoplay: 3000,
                                loading: {background: '#000000', opacity: 0.5, image: 'images/loading.gif'},
                                buttons: {hide: false, opacity: 1, prev: {className: 'SliderNamePrev_2', label: ''}, next: {className: 'SliderNameNext_2', label: ''}},
                                description: {hide: false, background: '#000000', opacity: 0.4, height: 50, position: 'bottom'},
                                navigation: {container: 'SliderNameNavigation_2', label: '<img src="images/clear.gif" />'}
                            }
                        });
                    </script>

                    <div class="c"></div>
                </div>
                <div class="c"></div>
            </div>

            <div class="c"></div>
        </div>
        <!--image slider finished-->


        

        <?php
        if (isset($_GET["msg"])) {
            ?>

            <div id="complete-hide">
                <div id="message-display">
                    <h2><?php echo $_GET["msg"]; ?></h2>
                </div>
            </div>

            <?php
            unset($_GET);
        }
        ?>
    </body>

</html>
