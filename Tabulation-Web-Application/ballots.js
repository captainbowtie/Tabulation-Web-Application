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

$("#unlockButton").on("click", function () {
    let ballotNumber = Number($("#ballot").val().substring(7));
    let unlockData = {"id":ballotNumber};
    $.ajax({
        url: "../api/ballots/unlock.php",
        method: "POST",
        data: unlockData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            warningModal(`Ballot Unlocked.\nYou can edit the ballot <a href="${response.url}">here</a>.`);
        } else {
            alert(response.message);
        }
    });
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
    //put numbers in ballot
    $("#pOpen").html(ballot.pOpen);
    $("#dOpen").html(ballot.dOpen);
    $("#pDx1").html(ballot.pDx1);
    $("#pDx2").html(ballot.pDx2);
    $("#pDx3").html(ballot.pDx3);
    $("#pWDx1").html(ballot.pWDx1);
    $("#pWDx2").html(ballot.pWDx2);
    $("#pWDx3").html(ballot.pWDx3);
    $("#pWCx1").html(ballot.pWCx1);
    $("#pWCx2").html(ballot.pWCx2);
    $("#pWCx3").html(ballot.pWCx3);
    $("#pCx1").html(ballot.pCx1);
    $("#pCx2").html(ballot.pCx2);
    $("#pCx3").html(ballot.pCx3);
    $("#dDx1").html(ballot.dDx1);
    $("#dDx2").html(ballot.dDx2);
    $("#dDx3").html(ballot.dDx3);
    $("#dWDx1").html(ballot.dWDx1);
    $("#dWDx2").html(ballot.dWDx2);
    $("#dWDx3").html(ballot.dWDx3);
    $("#dWCx1").html(ballot.dWCx1);
    $("#dWCx2").html(ballot.dWCx2);
    $("#dWCx3").html(ballot.dWCx3);
    $("#dCx1").html(ballot.dCx1);
    $("#dCx2").html(ballot.dCx2);
    $("#dCx3").html(ballot.dCx3);
    $("#pClose").html(ballot.pClose);
    $("#dClose").html(ballot.dClose);

    //calculate point totals
    let plaintiffPoints = ballot.pOpen +
            ballot.pDx1 +
            ballot.pDx2 +
            ballot.pDx3 +
            ballot.pWDx1 +
            ballot.pWDx2 +
            ballot.pWDx3 +
            ballot.pWCx1 +
            ballot.pWCx2 +
            ballot.pWCx3 +
            ballot.pCx1 +
            ballot.pCx2 +
            ballot.pCx3 + ballot.pClose;
    let defensePoints = ballot.dOpen +
            ballot.dDx1 +
            ballot.dDx2 +
            ballot.dDx3 +
            ballot.dWDx1 +
            ballot.dWDx2 +
            ballot.dWDx3 +
            ballot.dWCx1 +
            ballot.dWCx2 +
            ballot.dWCx3 +
            ballot.dCx1 +
            ballot.dCx2 +
            ballot.dCx3 + ballot.dClose;

    //determine lock status string
    let lockString = " (";
    if (ballot.locked) {
        lockString += "locked)";
        $("#unlockButton").prop("disabled", false);
    } else {
        lockString += "unlocked)";
        $("#unlockButton").prop("disabled", true);
    }
    if ((plaintiffPoints - defensePoints) > 0) {
        $("#tabRoomPD").html("Plaintiff wins: +" + (plaintiffPoints - defensePoints) + lockString);
    } else if ((plaintiffPoints - defensePoints) < 0) {
        $("#tabRoomPD").html("Defense wins: +" + (defensePoints - plaintiffPoints) + lockString);
    } else {
        $("#tabRoomPD").html("Tie" + lockString);
    }
}

function warningModal(text) {
    $("#warningModalText").html(text);
    $("#warningModal").modal();
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
