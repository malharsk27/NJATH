<?php

/*
 * Copyright (C) 2014 radsaggi(ashutosh)
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
$from = "leaderboard";
require_once 'function.php';
require './support/check.php';
require_once './support/dbcon.php';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>NJATH - ANWESHA 2k17 Leaderboard</title>
        <link href="leaderboard.css" rel="stylesheet" type="text/css" />
        <link href="navbar.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <nav class="cl-effect-9">
            <?php
            if (isset($_SESSION["username"])) {
                ?>
                <a href="./profile.php" >
                    <span>Questions</span>
                    <span>Continue the HUNT!</span>
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
                    <span>New to the challenge?</span>
                </a>
                <?php
            }
            ?>
            <a href="http://www.facebook.com/iit.njath/app_202980683107053">
                <span>Forum</span>
                <span>The Discussion Forum</span>
            </a>
            <a href="http://www.iitp.ac.in">
                <span>IIT Patna</span>
                <span>All about our college</span>
            </a>
            <a href="http://2017.anwesha.info">
                <span>Anwesha</span>
                <span>Anwesha 2017</span>
            </a>
            <a href="rules.php">
                <span>Rules</span>
                <span>The law of the Land!!!</span>
            </a>
        </nav>

        <div id="table">
            <table class="data-table">
                <thead>
                    <th>Sl. No.</th>
                    <th>Username</th>
                    <!-- <th>College</th> -->
                    <th>Score</th>
                </thead>
                <?php
                // include "../php/functions.php";
                $query = "SELECT * FROM `Contestants` AS `C` "
                        . "LEFT JOIN `ContestantsData` AS `CD` ON `C`.`username` = `CD`.`Username` "
                        . "WHERE `C`.`Disqualified` = 0 "
                        . "ORDER BY `Total Score` DESC LIMIT 0, 20";
                $result = mysqli_query($db_connection, $query);
                // var_dump($db_connection);
                // die();
                $count = 1;
                $shown = 0;
                $user = "";
                while ($row = mysqli_fetch_array($result)) {
                    $ans_user = null;
                    /*if (strpos($row["Anwesha ID"], "I") === 0) {
                        $anw_user["college"] = "IIT Patna";
                    } else {
                        $anw_user = giv_participant($row["Anwesha ID"]);
                    }
                    $row["College"] = $anw_user["college"];*/
                    $user = isset($_SESSION["username"]) && $row["Username"] == $_SESSION["username"];
                    if ($user) {
                        $shown = 1;
                    }
                    ?>
                    <tr <?php if ($user) echo 'class="user"' ?>>
                        <td><?php echo($count); ?></td>
                        <td><?php echo($row["Username"]); ?></td>
                        <td><?php echo($row["Total Score"]); ?></td>
                    </tr>
                    <?php
                    $count++;
                }
                ?>
            </table>
            <?php
            if (!$shown && isset($_SESSION["username"])) {
                ?>
                <table class="data-table">
                    <?php
                    $query = "SELECT * FROM `Contestants` AS `C` "
                            . "LEFT JOIN `ContestantsData` AS `CD` ON `C`.`username` = `CD`.`Username` "
                            . "WHERE `C`.`username`='{$_SESSION["username"]}' ";
                    $result = mysqli_fetch_array(mysqli_query($db_connection, $query));
                    ?>
                    <tr<?php if ($user) echo 'class="user"' ?>>
                        <td><?php echo($count); ?></td>
                        <td><?php echo($row["Username"]); ?></td>
                        <!-- <td><?php echo($row["College"]); ?></td> -->
                        <td><?php echo($row["Total Score"]); ?></td>
                    </tr>
                </table>
                <?php
            }
            mysqli_close($db_connection);
            ?>
        </div>
    </body>
</html>
