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


$from = "tchestpage";
require './support/check.php';

if (!isset($_GET["q"]) || $_SESSION["question"] === "") {
    header("Location: ./profile.php");
    return;
}

function treasure_chest_located($i) {
    require_once './support/dbcon.php';
    global $db_connection;
    global $_SESSION;
    $value = 1 << $i;
    $_SESSION["tchests"] = $_SESSION["tchests"] | $value;
    $query = "UPDATE `ContestantsData` SET `TChests Unlocked`= '{$_SESSION["tchests"]}' WHERE "
            . "`Username` = '{$_SESSION["username"]}'";
    mysqli_query($db_connection, $query);
    
    $score = (int) ($_SESSION["total-score"] / 10);
    push_increase("Treasure Chest UNLOCKED!!!", $score, FALSE);
    sync_scores();
}

for ($i = 0; $i < 4; $i++) {
    $string = create_tchest_string($i, $_SESSION["prev-salt"]);
    if ($string === $_GET["q"] && !check_tchest($i)) {
        treasure_chest_located($i);
        break;
    }
}

header("Location: ./profile.php");
die();
