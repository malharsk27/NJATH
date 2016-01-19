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
require_once './support/dbcon.php';
$from = "leaderboard";
require_once './support/check.php';
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>NJATH - ANWESHA 2k16 - RULES</title>
        <link href="rules.css" rel="stylesheet" type="text/css" />
        <link href="navbar.css" rel="stylesheet" type="text/css" />
        <style type="text/css">

        </style>
    </head>
    <body>
        <nav class="cl-effect-9">
            <?php
            if (isset($_SESSION["username"])) {
                ?>
                <a href="./profile.php" >
                    <span>Questions</span>
                    <span>Resume the HUNT!</span>
                </a>
                <?php
            } else {
                ?>
                <a href="index.php" >
                    <span>Login</span>
                    <span>Start the Awesome</span>
                </a>
                <a href="register.php">
                    <span>Register</span>
                    <span>New to the challenge ?</span>
                </a>
                <?php
            }
            ?>
            <a href="leaderboard.php" >
                <span>Leaderboard</span>
                <span>View the Leaderboard</span>
            </a>
            <a href="http://www.facebook.com/iit.njath/app_202980683107053">
                <span>Forum</span>
                <span>The Discussion Forum</span>
            </a>
            <a href="http://2016.anwesha.info">
                <span>Anwesha</span>
                <span>Anwesha 2k16</span>
            </a>
            <a href="http://www.iitp.ac.in">
                <span>IIT Patna</span>
                <span>All about our college</span>
            </a>
        </nav>
        <div id="rules-content">
            <h2>Rules for the contest</h2>

            <ol id="rules-list">
                <li> <p> NJATH is an online treasure-hunt contest conducted during Anwesha. You will be given questions, and you have to decipher the solution. Be careful with spellings and answers to questions. </p> </li> 
                <li> <p> Look out for clues and hints everywhere including the URL of the page, the page source and all the details provided in the question. You can use Google, Wikipedia or anything else for help. </p> </li> 
                <li> <p> Only answers as alpha-numeric characters in lower case (a-z, 0-9) are allowed. e.g. Suppose your answer is “I Love NJATH”, then write it as “ilovenjath”. In case of dates like 31st Jan 2016, write 31012016. </p> </li> 
                <li> <p> Use Anwesha ID and password only while registering.</p> </li>
                <li> <p> Stay tuned on the Discussion forum on our FB page for getting hints. </p> </li> 
                <li> <p> Users providing answers or direct link in any form through any medium will be disqualified. </p> </li>
				<li> <p> There is a Total Score which denotes the players game score so far. There is also a Level Score which will be used for opening questions. </p> </li>
				<li> <p> Opening a question reduces Level Score. No more questions can be opened once the level score drops to zero. Solving questions increments Total Score as well as Level Score. </p> </li>
                <li> <p> There are 6 levels in the hunt. Each level contains 8 questions of which at least 6 need to be answered to advance to the next level. </p> </li> 
                <li> <p> The remaining 2 questions serve as bonus questions. Bonus questions provide more points than ordinary questions of that level.</p> </li> 
                <li> <p> Your profile page will contain a list of all questions at the current level, each highlighted differently - unopened in blue, opened in yellow and solved in green. </p> </li> 
                <li> <p> Each question tag also contains information about scoring system. </p></li> 
                <li> <p> Once you advance to the next level, you can't go back. </p></li> 
                <li> <p> Bonus questions if opened have to be solved in order to move to the next level, otherwise a heavy penalty will be imposed on the score. </p> </li> 
                <li> <p> Each player starts off with a score of 40x(level no.), in each level. You will be awarded scores for correct answers.  </p> </li> 
                <li> <p> You will have to pay score costs for opening questions, which will be deducted from level score and not from total score so choose wisely! The level score at no point of time can fall below zero! </p> </li> 
                <li> <p> The player who has the maximum points at the end is the Winner. </p></li>
                

                        </ol>
                        </div>
                        </body>
                        </html>