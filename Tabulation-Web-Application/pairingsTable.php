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

//import requirements
require_once __DIR__ . "/config.php";
require_once SITE_ROOT . "/database.php";
require_once SITE_ROOT . "/objects/team.php";

//get team data;
$teams = getAllTeams();

//generate select content
$pSelects = [];
$dSelects = [];
for ($c = 1; $c <= 4; $c++) { //non-zero indexed to match round number
    for ($a = 0; $a < sizeOf($teams) / 2; $a++) {
        $pSelect = "<select id='round$c" . "p$a'>\n";
        $dSelect = "<select id='round$c" . "d$a'>\n";
        for ($b = 0; $b < sizeOf($teams); $b++) {
            $pSelect = $pSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
            $dSelect = $dSelect . "<option value='" . $teams[$b]["number"] . "'>" . $teams[$b]["number"] . " " . $teams[$b]["name"] . "</option>\n";
        }
        $pSelect = $pSelect . "</select>\n";
        $dSelect = $dSelect . "</select>\n";
        $pSelects[$c][] = $pSelect;
        $dSelects[$c][] = $dSelect;
    }
}

//fill page html variables
$tabHTML = [];
$tabHTML[1] = "<h3>Round 1</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>";
$tabHTML[2] = "<h3>Round 2</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>";
$tabHTML[3] = "<h3>Round 3</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>";
$tabHTML[4] = "<h3>Round 4</h3>
                <table>
                    <tr>
                        <th>π</th>
                        <th>∆</th>
                    </tr>";
for ($b = 1; $b <= 4; $b++) {
    for ($a = 0; $a < sizeOf($pSelects[$b]); $a++) {
        $tabHTML[$b] .= "<tr>\n";
        $tabHTML[$b] .= "<td>\n" . $pSelects[$b][$a] . "</td>\n";
        $tabHTML[$b] .= "<td>\n" . $dSelects[$b][$a] . "</td>\n";
        $tabHTML[$b] .= "</tr>\n";
    }
    $tabHTML[$b] .= "</table>";
}
?>


<div class="tab-content">
    <div id="round1" class="tab-pane fade in active">

        <?php
        echo $tabHTML[1];
        ?>    
    </div>
    <div id="round2" class="tab-pane fade">
        <?php
        echo $tabHTML[2];
        ?>
    </div>
    <div id="round3" class="tab-pane fade">
        <?php
        echo $tabHTML[3];
        ?>
    </div>
    <div id="round4" class="tab-pane fade">
        <?php
        echo $tabHTML[4];
        ?>
    </div>
</div>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#round1">Round 1</a></li>
    <li><a data-toggle="tab" href="#round2">Round 2</a></li>
    <li><a data-toggle="tab" href="#round3">Round 3</a></li>
    <li><a data-toggle="tab" href="#round4">Round 4</a></li>
</ul>
<script>
    var pairings;
    let round1PairingsExist = false;
    let round2PairingsExist = false;
    let round3PairingsExist = false;
    let round4PairingsExist = false;
    

    //Pull all data from server
    getPairings().then(data => {
        //to the extent pairings exist, fill them in
        var round1Pairings = [];
        var round2Pairings = [];
        var round3Pairings = [];
        var round4Pairings = [];
        //categorize pairings into rounds
        for (var a = 0; a < pairings.length; a++) {
            switch (pairings[a].round) {
                case 1:
                    round1Pairings.push(pairings[a]);
                    break;
                case 2:
                    round2Pairings.push(pairings[a]);
                    break;
                case 3:
                    round3Pairings.push(pairings[a]);
                    break;
                case 4:
                    round4Pairings.push(pairings[a]);
                    break;
            }
        }
        //fill in each round that exists
        for (var a = 0; a < round1Pairings.length; a++) {
            $(`#round1p${a}`).val(round1Pairings[a].plaintiff);
            $(`#round1d${a}`).val(round1Pairings[a].defense);
            round1PairingsExist = true;
        }
        for (var a = 0; a < round2Pairings.length; a++) {
            $(`#round2p${a}`).val(round2Pairings[a].plaintiff);
            $(`#round2d${a}`).val(round2Pairings[a].defense);
            round2PairingsExist = true;
        }
        for (var a = 0; a < round3Pairings.length; a++) {
            $(`#round3p${a}`).val(round3Pairings[a].plaintiff);
            $(`#round3d${a}`).val(round3Pairings[a].defense);
            round3PairingsExist = true;
        }
        for (var a = 0; a < round4Pairings.length; a++) {
            $(`#round4p${a}`).val(round4Pairings[a].plaintiff);
            $(`#round4d${a}`).val(round4Pairings[a].defense);
            round4PairingsExist = true;
        }

        //switch tab to current round
        if (round4PairingsExist) {
            $('.nav-tabs a[href="#round4"]').tab('show');
        } else if (round3PairingsExist) {
            $('.nav-tabs a[href="#round3"]').tab('show');
        } else if (round2PairingsExist) {
            $('.nav-tabs a[href="#round2"]').tab('show');
        } else if (round1PairingsExist) {
            $('.nav-tabs a[href="#round1"]').tab('show');
        }
    });




function getPairings() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/pairings/getAll.php",
            dataType: "json"
        }).then(data => {
            pairings = data;
            resolve();
        });
    });
}


</script>

