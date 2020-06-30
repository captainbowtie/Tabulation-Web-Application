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

var teams;
var pairings;
var ballots;
var selectOptions = [];

$(document).ready(function () {
    //Pull all data from server
    updateData().then(data => {
        let currentRound = 1;
        for (var a = 0; a < pairings.length; a++) {
            if (pairings[a].round > currentRound) {
                currentRound = pairings[a].round;
            }
        }
        $("#round").val(`Round ${currentRound}`);
        fillPairingSelect();
    });
});

$("#round").on("change", function () {
    fillPairingSelect();
});
$("#pairing").on("change", function () {
    fillBallotSelect();
});
$("#ballot").on("change", function () {
    fillBallot();
});

function fillPairingSelect() {
    //determine which round's pairings should be listed
    let pairingOptionsHTML = [];
    let selectedRound = Number($("#round").val().substring(6, 7));

    //create option HTML
    for (var a = 0; a < pairings.length; a++) {
        if (pairings[a].round === selectedRound) {
            pairingOptionsHTML.push(`<option>${pairings[a].plaintiff} v. ${pairings[a].defense}</option>\n`);
        }
    }

    //fill the select with the options
    $("#pairing").empty();
    pairingOptionsHTML.forEach(function (option) {
        $("#pairing").append(option);
    });
    fillBallotSelect();
}
;

function fillBallotSelect() {
    //determine which pairing should have its ballots listed
    let ballotOptionsHTML = [];
    let plaintiff = Number($("#pairing").val().substring(0, 4));
    let defense = Number($("#pairing").val().substring(8, 13));
    let pairingId = 0;
    for (var a = 0; a < pairings.length; a++) {
        if (pairings[a].plaintiff === plaintiff && pairings[a].defense === defense) {
            pairingId = pairings[a].id;
        }
    }

    //create option HTML
    for (var a = 0; a < ballots.length; a++) {
        if (ballots[a].pairing === pairingId) {
            ballotOptionsHTML.push(`<option>Ballot ${ballots[a].id}</option>\n`);
        }
    }

    //put the options into the select
    $("#ballot").empty();
    ballotOptionsHTML.forEach(function (option) {
        $("#ballot").append(option);
    });

    //fill in ballot data from default ballot
    fillBallot();
}

function fillBallot() {
    let ballotNumber = Number($("#ballot").val().substring(7));
    let ballot;
    for (var a = 0; a < ballots.length; a++) {
        if (ballots[a].id === ballotNumber) {
            ballot = ballots[a];
        }
    }
    $("#pOpen").val(ballot.pOpen);
    $("#dOpen").val(ballot.dOpen);
    $("#pDx1").val(ballot.pDx1);
    $("#pDx2").val(ballot.pDx2);
    $("#pDx3").val(ballot.pDx3);
    $("#pWDx1").val(ballot.pWDx1);
    $("#pWDx2").val(ballot.pWDx2);
    $("#pWDx3").val(ballot.pWDx3);
    $("#pWCx1").val(ballot.pWCx1);
    $("#pWCx2").val(ballot.pWCx2);
    $("#pWCx3").val(ballot.pWCx3);
    $("#pCx1").val(ballot.pCx1);
    $("#pCx2").val(ballot.pCx2);
    $("#pCx3").val(ballot.pCx3);
    $("#dDx1").val(ballot.dDx1);
    $("#dDx2").val(ballot.dDx2);
    $("#dDx3").val(ballot.dDx3);
    $("#dWDx1").val(ballot.dWDx1);
    $("#dWDx2").val(ballot.dWDx2);
    $("#dWDx3").val(ballot.dWDx3);
    $("#dWCx1").val(ballot.dWCx1);
    $("#dWCx2").val(ballot.dWCx2);
    $("#dWCx3").val(ballot.dWCx3);
    $("#dCx1").val(ballot.dCx1);
    $("#dCx2").val(ballot.dCx2);
    $("#dCx3").val(ballot.dCx3);
    $("#pClose").val(ballot.pClose);
    $("#dClose").val(ballot.dClose);
}

$("#submit").on("click", function (event) {
    event.preventDefault();
    if (validateScores()) {
        let ballot = {};
        let regex = /[0-9]+/;
        ballot["id"] = regex.exec($("#ballot").val())[0];
        ballot["pOpen"] = $("#pOpen").val();
        ballot["dOpen"] = $("#dOpen").val();
        ballot["pDx1"] = $("#pDx1").val();
        ballot["pDx2"] = $("#pDx2").val();
        ballot["pDx3"] = $("#pDx3").val();
        ballot["pWDx1"] = $("#pWDx1").val();
        ballot["pWDx2"] = $("#pWDx2").val();
        ballot["pWDx3"] = $("#pWDx3").val();
        ballot["pWCx1"] = $("#pWCx1").val();
        ballot["pWCx2"] = $("#pWCx2").val();
        ballot["pWCx3"] = $("#pWCx3").val();
        ballot["pCx1"] = $("#pCx1").val();
        ballot["pCx2"] = $("#pCx2").val();
        ballot["pCx3"] = $("#pCx3").val();
        ballot["dDx1"] = $("#dDx1").val();
        ballot["dDx2"] = $("#dDx2").val();
        ballot["dDx3"] = $("#dDx3").val();
        ballot["dWDx1"] = $("#dWDx1").val();
        ballot["dWDx2"] = $("#dWDx2").val();
        ballot["dWDx3"] = $("#dWDx3").val();
        ballot["dWCx1"] = $("#dWCx1").val();
        ballot["dWCx2"] = $("#dWCx2").val();
        ballot["dWCx3"] = $("#dWCx3").val();
        ballot["dCx1"] = $("#dCx1").val();
        ballot["dCx2"] = $("#dCx2").val();
        ballot["dCx3"] = $("#dCx3").val();
        ballot["pClose"] = $("#pClose").val();
        ballot["dClose"] = $("#dClose").val();
        $.ajax({
            url: "../api/ballots/update.php",
            method: "POST",
            data: JSON.stringify(ballot),
            dataType: "json"
        }).then(response => {
            if (response.message === 0) {
                
            } else {
                warningModal(response.message);
            }
        });
    }


});

function validateScores() {
    let scoreErrorList = "";
    if (!isBetween0and10(parseInt($("#pOpen").val()))) {
        scoreErrorList += "Plaintiff Opening\n";
    }
    if (!isBetween0and10(parseInt($("#dOpen").val()))) {
        scoreErrorList += "Defense Opening\n";
    }
    if (!isBetween0and10(parseInt($("#pDx1").val()))) {
        scoreErrorList += "Plaintiff Attorney Direct 1\n";
    }
    if (!isBetween0and10(parseInt($("#pDx2").val()))) {
        scoreErrorList += "Plaintiff Attorney Direct 2\n";
    }
    if (!isBetween0and10(parseInt($("#pDx3").val()))) {
        scoreErrorList += "Plaintiff Attorney Direct 3\n";
    }
    if (!isBetween0and10(parseInt($("#pWDx1").val()))) {
        scoreErrorList += "Plaintiff Witness Direct 1\n";
    }
    if (!isBetween0and10(parseInt($("#pWDx2").val()))) {
        scoreErrorList += "Plaintiff Witness Direct 2\n";
    }
    if (!isBetween0and10(parseInt($("#pWDx3").val()))) {
        scoreErrorList += "Plaintiff Witness Direct 3\n";
    }
    if (!isBetween0and10(parseInt($("#pWCx1").val()))) {
        scoreErrorList += "Plaintiff Witness Cross 1\n";
    }
    if (!isBetween0and10(parseInt($("#pWCx2").val()))) {
        scoreErrorList += "Plaintiff Witness Cross 2\n";
    }
    if (!isBetween0and10(parseInt($("#pWCx3").val()))) {
        scoreErrorList += "Plaintiff Witness Cross 3\n";
    }
    if (!isBetween0and10(parseInt($("#pCx1").val()))) {
        scoreErrorList += "Plaintiff Attorney Cross 1\n";
    }
    if (!isBetween0and10(parseInt($("#pCx2").val()))) {
        scoreErrorList += "Plaintiff Attorney Cross 2\n";
    }
    if (!isBetween0and10(parseInt($("#pCx3").val()))) {
        scoreErrorList += "Plaintiff Attorney Cross 3\n";
    }
    if (!isBetween0and10(parseInt($("#dDx1").val()))) {
        scoreErrorList += "Defense Attorney Direct 1\n";
    }
    if (!isBetween0and10(parseInt($("#dDx2").val()))) {
        scoreErrorList += "Defense Attorney Direct 2\n";
    }
    if (!isBetween0and10(parseInt($("#dDx3").val()))) {
        scoreErrorList += "Defense Attorney Direct 3\n";
    }
    if (!isBetween0and10(parseInt($("#dWDx1").val()))) {
        scoreErrorList += "Defense Witness Direct 1\n";
    }
    if (!isBetween0and10(parseInt($("#dWDx2").val()))) {
        scoreErrorList += "Defense Witness Direct 2\n";
    }
    if (!isBetween0and10(parseInt($("#dWDx3").val()))) {
        scoreErrorList += "Defense Witness Direct 3\n";
    }
    if (!isBetween0and10(parseInt($("#dWCx1").val()))) {
        scoreErrorList += "Defense Witness Cross 1\n";
    }
    if (!isBetween0and10(parseInt($("#dWCx2").val()))) {
        scoreErrorList += "Defense Witness Cross 2\n";
    }
    if (!isBetween0and10(parseInt($("#dWCx3").val()))) {
        scoreErrorList += "Defense Witness Cross 3\n";
    }
    if (!isBetween0and10(parseInt($("#dCx1").val()))) {
        scoreErrorList += "Defense Attorney Cross 1\n";
    }
    if (!isBetween0and10(parseInt($("#dCx2").val()))) {
        scoreErrorList += "Defense Attorney Cross 2\n";
    }
    if (!isBetween0and10(parseInt($("#dCx3").val()))) {
        scoreErrorList += "Defense Attorney Cross 3\n";
    }
    if (!isBetween0and10(parseInt($("#pClose").val()))) {
        scoreErrorList += "Plaintiff Closing\n";
    }
    if (!isBetween0and10(parseInt($("#dClose").val()))) {
        scoreErrorList += "Defense Opening\n";
    }

    if (scoreErrorList.length === 0) {
        return true;
    } else {
        warningModal("The following parts have errors in their scores:\n" + scoreErrorList);
        return false;
    }
}

function warningModal(text) {
    $("#warningModalText").text(text);
    $("#warningModal").modal();
}

function isBetween0and10(int) {
    if (Number.isInteger(int)) {
        if (int >= 0 && int <= 10) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function updateData() {
    return new Promise(function (resolve, reject) {
        Promise.all([getTeams(), getPairings(), getBallots()]).then(data => {
            teams = data[0];
            pairings = data[1];
            ballots = data[2];
            resolve();
        });
    });
}

function getTeams() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/teams/getAll.php",
            dataType: "json"
        }).then(teams => {
            resolve(teams);
        });
    });

}

function getPairings() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/pairings/getAll.php",
            dataType: "json"
        }).then(pairings => {
            resolve(pairings);
        });
    });
}

function getBallots() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/ballots/getAll.php",
            dataType: "json"
        }).then(ballots => {
            resolve(ballots);
        });
    });
}
