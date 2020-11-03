<?php
/*
 * Copyright (C) 2020 allen
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
if ($_SESSION["isCoach"] || $_SESSION["isAdmin"]) {
    require_once __DIR__ . "/config.php";
    require_once SITE_ROOT . "/database.php";
    require_once SITE_ROOT . "/objects/settings.php";
    require_once SITE_ROOT . "/loginHeader.php";

    $db = new Database();
    $conn = $db->getConnection();

//get ballot data
    $ballotQuery = "SELECT * FROM ballots WHERE locked = 1";
    $ballotResult = $conn->query($ballotQuery);
    $i = 0;
    while ($row = $ballotResult->fetch_assoc()) {
        $ballots[$i]["pairing"] = intval($row["pairing"]);
        $ballots[$i]["judge"] = intval($row["judge"]);
        $ballots[$i]["pOpen"] = intval($row["pOpen"]);
        $ballots[$i]["dOpen"] = intval($row["dOpen"]);
        $ballots[$i]["pDx1"] = intval($row["pDx1"]);
        $ballots[$i]["pWDx1"] = intval($row["pWDx1"]);
        $ballots[$i]["pWCx1"] = intval($row["pWCx1"]);
        $ballots[$i]["dCx1"] = intval($row["dCx1"]);
        $ballots[$i]["pDx2"] = intval($row["pDx2"]);
        $ballots[$i]["pWDx2"] = intval($row["pWDx2"]);
        $ballots[$i]["pWCx2"] = intval($row["pWCx2"]);
        $ballots[$i]["dCx2"] = intval($row["dCx2"]);
        $ballots[$i]["pDx3"] = intval($row["pDx3"]);
        $ballots[$i]["pWDx3"] = intval($row["pWDx3"]);
        $ballots[$i]["pWCx3"] = intval($row["pWCx3"]);
        $ballots[$i]["dCx3"] = intval($row["dCx3"]);
        $ballots[$i]["dDx1"] = intval($row["dDx1"]);
        $ballots[$i]["dWDx1"] = intval($row["dWDx1"]);
        $ballots[$i]["dWCx1"] = intval($row["dWCx1"]);
        $ballots[$i]["pCx1"] = intval($row["pCx1"]);
        $ballots[$i]["dDx2"] = intval($row["dDx2"]);
        $ballots[$i]["dWDx2"] = intval($row["dWDx2"]);
        $ballots[$i]["dWCx2"] = intval($row["dWCx2"]);
        $ballots[$i]["pCx2"] = intval($row["pCx2"]);
        $ballots[$i]["dDx3"] = intval($row["dDx3"]);
        $ballots[$i]["dWDx3"] = intval($row["dWDx3"]);
        $ballots[$i]["dWCx3"] = intval($row["dWCx3"]);
        $ballots[$i]["pCx3"] = intval($row["pCx3"]);
        $ballots[$i]["pClose"] = intval($row["pClose"]);
        $ballots[$i]["dClose"] = intval($row["dClose"]);
        $ballots[$i]["pOpenComments"] = $row["pOpenComments"];
        $ballots[$i]["dOpenComments"] = $row["dOpenComments"];
        $ballots[$i]["pDx1Comments"] = $row["pDx1Comments"];
        $ballots[$i]["pWDx1Comments"] = $row["pWDx1Comments"];
        $ballots[$i]["pWCx1Comments"] = $row["pWCx1Comments"];
        $ballots[$i]["dCx1Comments"] = $row["dCx1Comments"];
        $ballots[$i]["pDx2Comments"] = $row["pDx2Comments"];
        $ballots[$i]["pWDx2Comments"] = $row["pWDx2Comments"];
        $ballots[$i]["pWCx2Comments"] = $row["pWCx2Comments"];
        $ballots[$i]["dCx2Comments"] = $row["dCx2Comments"];
        $ballots[$i]["pDx3Comments"] = $row["pDx3Comments"];
        $ballots[$i]["pWDx3Comments"] = $row["pWDx3Comments"];
        $ballots[$i]["pWCx3Comments"] = $row["pWCx3Comments"];
        $ballots[$i]["dCx3Comments"] = $row["dCx3Comments"];
        $ballots[$i]["dDx1Comments"] = $row["dDx1Comments"];
        $ballots[$i]["dWDx1Comments"] = $row["dWDx1Comments"];
        $ballots[$i]["dWCx1Comments"] = $row["dWCx1Comments"];
        $ballots[$i]["pCx1Comments"] = $row["pCx1Comments"];
        $ballots[$i]["dDx2Comments"] = $row["dDx2Comments"];
        $ballots[$i]["dWDx2Comments"] = $row["dWDx2Comments"];
        $ballots[$i]["dWCx2Comments"] = $row["dWCx2Comments"];
        $ballots[$i]["pCx2Comments"] = $row["pCx2Comments"];
        $ballots[$i]["dDx3Comments"] = $row["dDx3Comments"];
        $ballots[$i]["dWDx3Comments"] = $row["dWDx3Comments"];
        $ballots[$i]["dWCx3Comments"] = $row["dWCx3Comments"];
        $ballots[$i]["pCx3Comments"] = $row["pCx3Comments"];
        $ballots[$i]["pCloseComments"] = $row["pCloseComments"];
        $ballots[$i]["dCloseComments"] = $row["dCloseComments"];
        $ballots[$i]["aty1"] = ($row["aty1"]);
        $ballots[$i]["aty2"] = $row["aty2"];
        $ballots[$i]["aty3"] = $row["aty3"];
        $ballots[$i]["aty4"] = $row["aty4"];
        $ballots[$i]["wit1"] = $row["wit1"];
        $ballots[$i]["wit2"] = $row["wit2"];
        $ballots[$i]["wit3"] = $row["wit3"];
        $ballots[$i]["wit4"] = $row["wit4"];
        $i++;
    }
    $ballotResult->close();

//fill in judge names
    $judgeQuery = "SELECT id,name FROM judges";
    $judgeResult = $conn->query($judgeQuery);
    while ($row = $judgeResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($ballots); $a++) {
            if ($ballots[$a]["judge"] === intval($row["id"])) {
                $ballots[$a]["judge"] = $row["name"];
            }
        }
    }
    $judgeResult->close();

//fill in team numbers and names
//first need to convert pairing id to team ids
    $pairingQuery = "SELECT * FROM pairings";
    $pairingResult = $conn->query($pairingQuery);
    while ($row = $pairingResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($ballots); $a++) {
            if ($ballots[$a]["pairing"] === intval($row["id"])) {
                $ballots[$a]["pID"] = intval($row["plaintiff"]);
                $ballots[$a]["dID"] = intval($row["defense"]);
                $ballots[$a]["round"] = intval($row["round"]);
                $ballots[$a]["pOpenAttorney"] = $row["pOpen"];
                $ballots[$a]["dOpenAttorney"] = $row["dOpen"];
                $ballots[$a]["pDx1Attorney"] = $row["pDx1"];
                $ballots[$a]["pWDx1Witness"] = $row["pWDx1"];
                $ballots[$a]["dCx1Attorney"] = $row["dCx1"];
                $ballots[$a]["pDx2Attorney"] = $row["pDx2"];
                $ballots[$a]["pWDx2Witness"] = $row["pWDx2"];
                $ballots[$a]["dCx2Attorney"] = $row["dCx2"];
                $ballots[$a]["pDx3Attorney"] = $row["pDx3"];
                $ballots[$a]["pWDx3Witness"] = $row["pWDx3"];
                $ballots[$a]["dCx3Attorney"] = $row["dCx3"];
                $ballots[$a]["dDx1Attorney"] = $row["dDx1"];
                $ballots[$a]["dWDx1Witness"] = $row["dWDx1"];
                $ballots[$a]["pCx1Attorney"] = $row["pCx1"];
                $ballots[$a]["dDx2Attorney"] = $row["dDx2"];
                $ballots[$a]["dWDx2Witness"] = $row["dWDx2"];
                $ballots[$a]["pCx2Attorney"] = $row["pCx2"];
                $ballots[$a]["dDx3Attorney"] = $row["dDx3"];
                $ballots[$a]["dWDx3Witness"] = $row["dWDx3"];
                $ballots[$a]["pCx3Attorney"] = $row["pCx3"];
                $ballots[$a]["pCloseAttorney"] = $row["pClose"];
                $ballots[$a]["dCloseAttorney"] = $row["dClose"];
                $ballots[$a]["witness1"] = $row["wit1"];
                $ballots[$a]["witness2"] = $row["wit2"];
                $ballots[$a]["witness3"] = $row["wit3"];
                $ballots[$a]["witness4"] = $row["wit4"];
                $ballots[$a]["witness5"] = $row["wit5"];
                $ballots[$a]["witness6"] = $row["wit6"];

                //if round 4 ballots shouln't be visible, clear the ballot

                if (intVal($row["round"]) === 4 && !getSetting("roundFourBallotsViewable")) {
                    $ballots[$a]["pairing"] = intval($row["pairing"]);
                    $ballots[$a]["pOpen"] = 0;
                    $ballots[$a]["dOpen"] = 0;
                    $ballots[$a]["pDx1"] = 0;
                    $ballots[$a]["pWDx1"] = 0;
                    $ballots[$a]["pWCx1"] = 0;
                    $ballots[$a]["dCx1"] = 0;
                    $ballots[$a]["pDx2"] = 0;
                    $ballots[$a]["pWDx2"] = 0;
                    $ballots[$a]["pWCx2"] = 0;
                    $ballots[$a]["dCx2"] = 0;
                    $ballots[$a]["pDx3"] = 0;
                    $ballots[$a]["pWDx3"] = 0;
                    $ballots[$a]["pWCx3"] = 0;
                    $ballots[$a]["dCx3"] = 0;
                    $ballots[$a]["dDx1"] = 0;
                    $ballots[$a]["dWDx1"] = 0;
                    $ballots[$a]["dWCx1"] = 0;
                    $ballots[$a]["pCx1"] = 0;
                    $ballots[$a]["dDx2"] = 0;
                    $ballots[$a]["dWDx2"] = 0;
                    $ballots[$a]["dWCx2"] = 0;
                    $ballots[$a]["pCx2"] = 0;
                    $ballots[$a]["dDx3"] = 0;
                    $ballots[$a]["dWDx3"] = 0;
                    $ballots[$a]["dWCx3"] = 0;
                    $ballots[$a]["pCx3"] = 0;
                    $ballots[$a]["pClose"] = 0;
                    $ballots[$a]["dClose"] = 0;
                    $ballots[$a]["pOpenComments"] = "You Are Old, Father William-Lewis Carroll";
                    $ballots[$a]["dOpenComments"] = "Alice's Adventures in Wonderland, Chapter 5";
                    $ballots[$a]["pDx1Comments"] = "You are old, Father William, the young man said,
    And your hair has become very white;
And yet you incessantly stand on your head—
    Do you think, at your age, it is right?";
                    $ballots[$a]["pWDx1Comments"] = "In my youth, Father William replied to his son,
    I feared it might injure the brain;
But now that I'm perfectly sure I have none,
    Why, I do it again and again.";
                    $ballots[$a]["pWCx1Comments"] = "You are old, said the youth, as I mentioned before,
    And have grown most uncommonly fat;
Yet you turned a back-somersault in at the door—
    Pray, what is the reason of that?";
                    $ballots[$a]["dCx1Comments"] = "In my youth, said the sage, as he shook his grey locks,
    I kept all my limbs very supple
By the use of this ointment—one shilling the box—
    Allow me to sell you a couple.";
                    $ballots[$a]["pDx2Comments"] = "You are old, said the youth, and your jaws are too weak
    For anything tougher than suet;
Yet you finished the goose, with the bones and the beak—
    Pray, how did you manage to do it?";
                    $ballots[$a]["pWDx2Comments"] = "In my youth, said his father, I took to the law,
    And argued each case with my wife;
And the muscular strength, which it gave to my jaw,
    Has lasted the rest of my life.";
                    $ballots[$a]["pWCx2Comments"] = "You are old, said the youth, one would hardly suppose
    That your eye was as steady as ever;
Yet you balanced an eel on the end of your nose—
    What made you so awfully clever?";
                    $ballots[$a]["dCx2Comments"] = "I have answered three questions, and that is enough,
    Said his father; don't give yourself airs!
Do you think I can listen all day to such stuff?
    Be off, or I'll kick you down stairs!";
                    $ballots[$a]["pDx3Comments"] = "I'll tell thee everything I can;
There's little to relate,
I saw an aged, aged man,
A-sitting on a gate.
'Who are you, aged man?' I said.
'And how is it you live?'
And his answer trickled through my head
Like water through a sieve.";
                    $ballots[$a]["pWDx3Comments"] = "He said, 'I look for butterflies
That sleep among the wheat;
I make them into mutton-pies,
And sell them in the street.
I sell them unto men,' he said,
'Who sail on stormy seas;
And that's the way I get my bread
A trifle, if you please.'";
                    $ballots[$a]["pWCx3Comments"] = "But I was thinking of a plan
To dye one's whiskers green,
And always use so large a fan
That they could not be seen.
So, having no reply to give
To what the old man said,
I cried, 'Come, tell me how you live!'
And thumped him on the head.
";
                    $ballots[$a]["dCx3Comments"] = "The Aged Aged Man, Lewis Carroll, Through the Looking-Glass, and What Alice Found There, Chapter 8";
                    $ballots[$a]["dDx1Comments"] = "But I was thinking of a way
To feed one's self on batter,
And so go on from day to day
Getting a little fatter.
I shook him well from side to side,
Until his face was blue,
'Come, tell me how you live,' I cried,
'And what it is you do!'
";
                    $ballots[$a]["dWDx1Comments"] = "He said, 'I hunt for haddocks' eyes
Among the heather bright,
And work them into waistcoat-buttons
In the silent night.
And these I do not sell for gold
Or coin of silvery shine,
But for a copper halfpenny,
And that will purchase nine.";
                    $ballots[$a]["dWCx1Comments"] = "'I sometimes dig for buttered rolls,
Or set limed twigs for crabs;
I sometimes search the grassy knolls
For wheels of hansom-cabs.
And that's the way' (he gave a wink)
'By which I get my wealth
And very gladly will I drink
Your honor's noble health.'";
                    $ballots[$a]["pCx1Comments"] = "His accents mild took up the tale;
He said, 'I go my ways,
And when I find a mountain-rill,
I set it in a blaze;
And thence they make a stuff they call
Rowland's Macassar Oil
Yet twopence-halfpenny is all
They give me for my toil.'";
                    $ballots[$a]["dDx2Comments"] = "And now, if e'er by chance I put
My fingers into glue,
Or madly squeeze a right-hand foot
Into a left-hand shoe,
Or if I drop upon my toe
A very heavy weight,";
                    $ballots[$a]["dWDx2Comments"] = "I weep, for it reminds me so
Of that old man I used to know
Whose look was mild, whose speech was slow,
Whose hair was whiter than the snow,
Whose face was very like a crow,
With eyes, like cinders, all aglow,
Who seemed distracted with his woe,";
                    $ballots[$a]["dWCx2Comments"] = "Who rocked his body to and fro,
And muttered mumblingly and low,
As if his mouth were full of dough,
Who snorted like a buffalo
That summer evening long ago,
A-sitting on a gate. ";
                    $ballots[$a]["pCx2Comments"] = "I heard him then, for I had just
Completed my design
To keep the Menai bridge from rust
By boiling it in wine.
I thanked him much for telling me
The way he got his wealth,
But chiefly for his wish that he
Might drink my noble health.";
                    $ballots[$a]["dDx3Comments"] = "'Will you walk a little faster?' said a whiting to a snail,
'There's a porpoise close behind us, and he's treading on my tail.
See how eagerly the lobsters and the turtles all advance!
They are waiting on the shingle—will you come and join the dance?
    Will you, won't you, will you, won't you, will you join the dance?
    Will you, won't you, will you, won't you, won't you join the dance?";
                    $ballots[$a]["dWDx3Comments"] = "'You can really have no notion how delightful it will be
When they take us up and throw us, with the lobsters, out to sea!'
But the snail replied 'Too far, too far!' and gave a look askance—
Said he thanked the whiting kindly, but he would not join the dance.
    Would not, could not, would not, could not, would not join the dance.
    Would not, could not, would not, could not, could not join the dance.";
                    $ballots[$a]["dWCx3Comments"] = "'What matters it how far we go?' his scaly friend replied,
'There is another shore, you know, upon the other side.
The further off from England the nearer is to France—
Then turn not pale, beloved snail, but come and join the dance.
    Will you, won't you, will you, won't you, will you join the dance?
    Will you, won't you, will you, won't you, won't you join the dance?'";
                    $ballots[$a]["pCx3Comments"] = "The Lobster Quadrille, Lewis Carroll, Alice's Adventures in Wonderland, Chapter 10";
                    $ballots[$a]["pCloseComments"] = "Twinkle, twinkle, little bat!
How I wonder what you're at!
Up above the world you fly,
Like a teatray in the sky.";
                    $ballots[$a]["dCloseComments"] = "Twinkle, Twinkle, Little Bat, Lewis Carroll, Alice's Adventures in Wonderland, Chapter 7";
                    $ballots[$a]["aty1"] = "Joey Davis";
                    $ballots[$a]["aty2"] = "Sydney Park";
                    $ballots[$a]["aty3"] = "Parker Paige";
                    $ballots[$a]["aty4"] = "Kirby Doolittle";
                    $ballots[$a]["wit1"] = "Blake Lexington";
                    $ballots[$a]["wit2"] = "Jesse Duran";
                    $ballots[$a]["wit3"] = "Jordan Ryder";
                    $ballots[$a]["wit4"] = "Riley Winter";
                }
            }
        }
    }
    $pairingResult->close();
    $ballots = teamIDsToNumbers($ballots);

//delete any comments that the user is not authorized to see
    if ($_SESSION["isCoach"]) {
        $coachQuery = "SELECT team FROM coaches WHERE user = " . $_SESSION["id"];
        $coachResult = $conn->query($coachQuery);
        $coachedTeams = array();
        while ($row = $coachResult->fetch_assoc()) {
            array_push($coachedTeams, intval($row["team"]));
        }
        $coachResult->close();


        for ($a = 0; $a < sizeOf($ballots); $a++) {
            if (!in_array($ballots[$a]["pID"], $coachedTeams) && !in_array($ballots[$a]["dID"], $coachedTeams)) {
                $ballots[$a]["pOpenComments"] = "N/A";
                $ballots[$a]["dOpenComments"] = "N/A";
                $ballots[$a]["pDx1Comments"] = "N/A";
                $ballots[$a]["pWDx1Comments"] = "N/A";
                $ballots[$a]["pWCx1Comments"] = "N/A";
                $ballots[$a]["dCx1Comments"] = "N/A";
                $ballots[$a]["pDx2Comments"] = "N/A";
                $ballots[$a]["pWDx2Comments"] = "N/A";
                $ballots[$a]["pWCx2Comments"] = "N/A";
                $ballots[$a]["dCx2Comments"] = "N/A";
                $ballots[$a]["pDx3Comments"] = "N/A";
                $ballots[$a]["pWDx3Comments"] = "N/A";
                $ballots[$a]["pWCx3Comments"] = "N/A";
                $ballots[$a]["dCx3Comments"] = "N/A";
                $ballots[$a]["dDx1Comments"] = "N/A";
                $ballots[$a]["dWDx1Comments"] = "N/A";
                $ballots[$a]["dWCx1Comments"] = "N/A";
                $ballots[$a]["pCx1Comments"] = "N/A";
                $ballots[$a]["dDx2Comments"] = "N/A";
                $ballots[$a]["dWDx2Comments"] = "N/A";
                $ballots[$a]["dWCx2Comments"] = "N/A";
                $ballots[$a]["pCx2Comments"] = "N/A";
                $ballots[$a]["dDx3Comments"] = "N/A";
                $ballots[$a]["dWDx3Comments"] = "N/A";
                $ballots[$a]["dWCx3Comments"] = "N/A";
                $ballots[$a]["pCx3Comments"] = "N/A";
                $ballots[$a]["pCloseComments"] = "N/A";
                $ballots[$a]["dCloseComments"] = "N/A";
                $ballots[$a]["witness1"] = "N/A";
                $ballots[$a]["witness2"] = "N/A";
                $ballots[$a]["witness3"] = "N/A";
                $ballots[$a]["witness4"] = "N/A";
                $ballots[$a]["witness5"] = "N/A";
                $ballots[$a]["witness6"] = "N/A";
                $ballots[$a]["pOpenAttorney"] = "N/A";
                $ballots[$a]["dOpenAttorney"] = "N/A";
                $ballots[$a]["pDx1Attorney"] = "N/A";
                $ballots[$a]["pWDx1Witness"] = "N/A";
                $ballots[$a]["dCx1Attorney"] = "N/A";
                $ballots[$a]["pDx2Attorney"] = "N/A";
                $ballots[$a]["pWDx2Witness"] = "N/A";
                $ballots[$a]["dCx2Attorney"] = "N/A";
                $ballots[$a]["pDx3Attorney"] = "N/A";
                $ballots[$a]["pWDx3Witness"] = "N/A";
                $ballots[$a]["dCx3Attorney"] = "N/A";
                $ballots[$a]["dDx1Attorney"] = "N/A";
                $ballots[$a]["dWDx1Witness"] = "N/A";
                $ballots[$a]["pCx1Attorney"] = "N/A";
                $ballots[$a]["dDx2Attorney"] = "N/A";
                $ballots[$a]["dWDx2Witness"] = "N/A";
                $ballots[$a]["pCx2Attorney"] = "N/A";
                $ballots[$a]["dDx3Attorney"] = "N/A";
                $ballots[$a]["dWDx3Witness"] = "N/A";
                $ballots[$a]["pCx3Attorney"] = "N/A";
                $ballots[$a]["pCloseAttorney"] = "N/A";
                $ballots[$a]["dCloseAttorney"] = "N/A";
            }
        }
    }
    $conn->close();
} else {
    die("Access denied.");
}

function teamIDsToNumbers($ballots) {

    $teamDB = new Database();
    $teamConn = $teamDB->getConnection();
//then need to convert team ids to numbers and names
    $teamQuery = "SELECT id,number,name FROM teams";
    $teamResult = $teamConn->query($teamQuery);
    while ($row = $teamResult->fetch_assoc()) {
        for ($a = 0; $a < sizeOf($ballots); $a++) {
            if ($ballots[$a]["pID"] === intval($row["id"])) {
                $ballots[$a]["pNumber"] = intval($row["number"]);
                $ballots[$a]["pName"] = $row["name"];
            }
            if ($ballots[$a]["dID"] === intval($row["id"])) {
                $ballots[$a]["dNumber"] = intval($row["number"]);
                $ballots[$a]["dName"] = $row["name"];
            }
        }
    }
    $teamResult->close();
    $teamConn->close();
    return $ballots;
}
?><!DOCTYPE html>
<!--
Copyright (C) 2020 allen

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <!--Latest compiled and minified CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!--Optional theme-->
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity = "sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin = "anonymous">

        <link rel="stylesheet" href="comments.css">

        <title>Comments</title>
    </head>
    <body>
        <?php echo $header;?>
        <div id="selects">
            <select id="round"> 
                <option value="1">Round 1</option>
                <option value="2">Round 2</option>
                <option value="3">Round 3</option>
                <option value="4">Round 4</option>
            </select>
            <select id="pairing">
            </select>
            <select id="ballot">
            </select>
        </div>
        <div id="pointTotalDiv"></div>
        <div id="scoresDiv">
            <div class="accordion" id="scores">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#openingScores" aria-expanded="true" aria-controls="openingScores">
                                Opening
                            </button>
                        </h2>
                    </div>

                    <div id="openingScores" class="collapse show" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body speech">
                            <label for="pOpen" class="pSpeechLabel" id="pOpenLabel">π Open (<?php echo $pairing["pOpen"]; ?>):</label>
                            <p class="pSpeech" id="pOpen"></p>
                            <label for="dOpen" class="dSpeechLabel" id="dOpenLabel">∆ Open (<?php echo $pairing["dOpen"]; ?>):</label>
                            <p class="dSpeech" id="dOpen"></p>
                            <p class="pSpeechComments" id="pOpenComments"></p>
                            <p class="dSpeechComments" id="dOpenComments"></p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness1Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#p1Scores" aria-expanded="true" aria-controls="p1Scores">
                                Plaintiff Witness 1 (<?php echo $pairing["Wit1"]; //TODO: make Wit lower case          ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx1" class="pDxLabel" id="pDx1Label">Atty Dx (<?php echo $pairing["pDx1"]; ?>):</label>
                            <p class="pDx" id="pDx1"></p>
                            <label for="dCx1" class="dCxLabel" id="dCx1Label">Atty Cx (<?php echo $pairing["dCx1"]; ?>):</label>
                            <p class="dCx" id="dCx1"></p>
                            <p class="pDxComments" id="pDx1Comments"></p>
                            <p class="dCxComments" id="dCx1Comments"></p>

                            <label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Wit Dx (<?php echo $pairing["pWDx1"]; ?>):</label>
                            <p class="pWDx" id="pWDx1"></p>
                            <p class="pWDxComments" id="pWDx1Comments"></p>

                            <label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Wit Cx (<?php echo $pairing["pWDx1"]; ?>):</label>
                            <p class="pWCx" id="pWCx1"></p>
                            <p class="pWCxComments" id="pWCx1Comments"></p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness2Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#p2Scores" aria-expanded="true" aria-controls="p2Scores">
                                Plaintiff Witness 2 (<?php echo $pairing["Wit2"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx2" class="pDxLabel" id="pDx2Label">Atty Dx (<?php echo $pairing["pDx2"]; ?>):</label>
                            <p class="pDx" id="pDx2"></p>
                            <label for="dCx2" class="dCxLabel" id="dCx2Label">Atty Cx (<?php echo $pairing["dCx2"]; ?>):</label>
                            <p class="dCx" id="dCx2"></p>

                            <p class="pDxComments" id="pDx2Comments"></p>
                            <p class="dCxComments" id="dCx2Comments"></p>

                            <label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Wit Dx (<?php echo $pairing["pWDx2"]; ?>):</label>
                            <p class="pWDx" id="pWDx2" ></p>
                            <p class="pWDxComments" id="pWDx2Comments"></p>
                            <label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Wit Cx (<?php echo $pairing["pWDx2"]; ?>):</label>
                            <p class="pWCx" id="pWCx2"></p>
                            <p class="pWCxComments" id="pWCx2Comments"></p>
                        </div>


                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness3Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#p3Scores" aria-expanded="true" aria-controls="p3Scores">
                                Plaintiff Witness 3 (<?php echo $pairing["Wit3"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="p3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body plaintiffWitness">
                            <label for="pDx3" class="pDxLabel" id="pDx3Label">Atty Dx (<?php echo $pairing["pDx3"]; ?>):</label>
                            <p class="pDx" id="pDx3"></p>
                            <label for="dCx3" class="dCxLabel" id="dCx3Label">Atty Cx (<?php echo $pairing["dCx3"]; ?>):</label>
                            <p class="dCx" id="dCx3"></p>

                            <p class="pDxComments" id="pDx3Comments"></p>
                            <p class="dCxComments" id="dCx3Comments"></p>

                            <label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Wit Dx (<?php echo $pairing["pWDx3"]; ?>):</label>
                            <p class="pWDx" id="pWDx3"></p>

                            <p class="pWDxComments" id="pWDx3Comments"></p>

                            <label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Wit Cx (<?php echo $pairing["pWDx3"]; ?>):</label>
                            <p class="pWCx" id="pWCx3"></p>
                            <p class="pWCxComments" id="pWCx3Comments"></p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness4Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#d1Scores" aria-expanded="true" aria-controls="d1Scores">
                                Defense Witness 1 (<?php echo $pairing["Wit4"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx1" class="pCxLabel" id="pCx1Label">Atty Cx (<?php echo $pairing["pCx1"]; ?>):</label>
                            <p class="pCx" id="pCx1"></p>
                            <label for="dDx1" class="dDxLabel" id="dDx1Label">Atty Dx (<?php echo $pairing["dDx1"]; ?>):</label>
                            <p class="dDx" id="dDx1"></p>

                            <p class="pCxComments" id="pCx1Comments"></p>
                            <p class="dDxComments" id="dDx1Comments"></p>


                            <label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Wit Dx (<?php echo $pairing["dWDx1"]; ?>):</label>
                            <p class="dWDx" id="dWDx1"></p>

                            <p class="dWDxComments" id="dWDx1Comments"></p>

                            <label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Wit Cx (<?php echo $pairing["dWDx1"]; ?>):</label>
                            <p class="dWCx" id="dWCx1"></p>

                            <p class="dWCxComments" id="dWCx1Comments"></p>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness5Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#d2Scores" aria-expanded="true" aria-controls="d2Scores">
                                Defense Witness 2 (<?php echo $pairing["Wit5"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx2" class="pCxLabel" id="pCx2Label">Wit Cx (<?php echo $pairing["pCx2"]; ?>):</label>
                            <p class="pCx" id="pCx2"></p>
                            <label for="dDx2" class="dDxLabel" id="dDx2Label">Wit Dx (<?php echo $pairing["dDx2"]; ?>):</label>
                            <p class="dDx" id="dDx2"></p>

                            <p class="pCxComments" id="pCx2Comments"></p>
                            <p class="dDxComments" id="dDx2Comments"></p>

                            <label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Wit Dx (<?php echo $pairing["dWDx2"]; ?>):</label>
                            <p class="dWDx" id="dWDx2"></p>

                            <p class="dWDxComments" id="dWDx2Comments"></p>

                            <label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Wit Cx (<?php echo $pairing["dWDx2"]; ?>):</label>
                            <p class="dWCx" id="dWCx2"></p>

                            <p class="dWCxComments" id="dWCx2Comments"></p>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button id="witness6Header" class="btn btn-link" type="button" data-toggle="collapse" data-target="#d3Scores" aria-expanded="true" aria-controls="d3Scores">
                                Defense Witness 3 (<?php echo $pairing["Wit6"]; ?>)
                            </button>
                        </h2>
                    </div>

                    <div id="d3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body defenseWitness">
                            <label for="pCx3" class="pCxLabel" id="pCx3Label">Atty Cx (<?php echo $pairing["pCx3"]; ?>):</label>
                            <p class="pCx" id="pCx3"></p>
                            <label for="dDx3" class="dDxLabel" id="dDx3Label">Atty Dx (<?php echo $pairing["dDx3"]; ?>):</label>
                            <p class="dDx" id="dDx3"></p>

                            <p class="pCxComments" id="pCx3Comments"></p>
                            <p class="dDxComments" id="dDx3Comments"></p>

                            <label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Wit Dx (<?php echo $pairing["dWDx3"]; ?>):</label>
                            <p class="dWDx" id="dWDx3"></p>

                            <p class="dWDxComments" id="dWDx3Comments"></p>

                            <label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Wit Cx (<?php echo $pairing["dWDx3"]; ?>):</label>
                            <p class="dWCx" id="dWCx3"></p>

                            <p class="dWCxComments" id="dWCx3Comments"></p>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#closingScores" aria-expanded="true" aria-controls="closingScores">
                                Closing
                            </button>
                        </h2>
                    </div>

                    <div id="closingScores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                        <div class="card-body speech">
                            <label for="pClose" class="pSpeechLabel" id="pCloseLabel">π Close (<?php echo $pairing["pClose"]; ?>):</label>
                            <p class="pSpeech" id="pClose"></p>
                            <label for="dClose" class="dSpeechLabel" id="dCloseLabel">∆ Close (<?php echo $pairing["dClose"]; ?>):</label>
                            <p class="dSpeech" id="dClose"></p>
                            <p class="pSpeechComments" id="pCloseComments"></p>
                            <p class="dSpeechComments" id="dCloseComments"></p>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#individualAwards" aria-expanded="true" aria-controls="individualAwards">
                            Individual Awards
                        </button>
                    </h2>
                </div>

                <div id="individualAwards" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
                    <div class="card-body" id="awards">
                        <p id="attyLabel">Outstanding Attorneys</p>
                        <label for="aty1" class="attySelectLabel" id="atty1Label">Atty 1:</label>
                        <p id="aty1"></p>

                        <label for="aty2" class="attySelectLabel" id="atty2Label">Atty 2:</label>
                        <p id="aty2"></p>

                        <label for="aty3" class="attySelectLabel" id="atty3Label">Atty 3:</label>
                        <p id="aty3"></p>

                        <label for="aty4" class="attySelectLabel" id="atty4Label">Atty 4:</label>
                        <p id="aty4"></p>

                        <p id="witLabel">Outstanding Witnesses</p>
                        <label for="wit1" class="witSelectLabel" id="wit1Label">Wit 1:</label>
                        <p id="wit1"></p>

                        <label for="wit2" class="witSelectLabel" id="wit2Label">Wit 2:</label>
                        <p id="wit2"></p>

                        <label for="wit3" class="witSelectLabel" id="wit3Label">Wit 3:</label>
                        <p id="wit3"></p>

                        <label for="wit4" class="witSelectLabel" id="wit4Label">Wit 4:</label>
                        <p id="wit4"></p>

                    </div>
                </div>
            </div>
        </div>

        <script>var ballots = <?php echo json_encode($ballots); ?></script>
        <script src="comments.js"></script>
    </body>
</html>
