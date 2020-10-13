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

var zeroScore = false;

$("input[type='number']").on("change", function () {
    zeroScore = false;
    if (validateScore($(this).val())) {
        updateScore($(this).attr("id"), $(this).val());
    } else {
        let part = determinePart($(this).attr("id"));
        alert("Error: " + part + " must be a number between 0 and 10.");
    }
});

$("textarea").on("change", function () {
    zeroScore = false;
    let commentData = {"part": $(this).attr("id")};
    commentData.comment = $(this).val();
    commentData.url = url;

    $.ajax({
        url: "../api/judgeBallot/updateComment.php",
        method: "POST",
        data: commentData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {

        } else {
            alert(response.message);
        }
    });
})

$("select").on("change", function () {
    zeroScore = false;
    let individualData = {"part": $(this).attr("id")};
    individualData.studentName = $(this).val();
    individualData.url = url;

    $.ajax({
        url: "../api/judgeBallot/updateIndividual.php",
        method: "POST",
        data: individualData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {

        } else {
            alert(response.message);
        }
    });
})

$("#submit").on("click", function () {
    if (validateBallot()) {
        let ballotData = {"pOpen": $("#pOpen").val()};
        ballotData.dOpen = $("#dOpen").val();
        ballotData.pDx1 = $("#pDx1").val();
        ballotData.pWDx1 = $("#pWDx1").val();
        ballotData.pWCx1 = $("#pWCx1").val();
        ballotData.dCx1 = $("#dCx1").val();
        ballotData.pDx2 = $("#pDx2").val();
        ballotData.pWDx2 = $("#pWDx2").val();
        ballotData.pWCx2 = $("#pWCx2").val();
        ballotData.dCx2 = $("#dCx2").val();
        ballotData.pDx3 = $("#pDx3").val();
        ballotData.pWDx3 = $("#pWDx3").val();
        ballotData.pWCx3 = $("#pWCx3").val();
        ballotData.dCx3 = $("#dCx3").val();
        ballotData.dDx1 = $("#dDx1").val();
        ballotData.dWDx1 = $("#dWDx1").val();
        ballotData.dWCx1 = $("#dWCx1").val();
        ballotData.pCx1 = $("#pCx1").val();
        ballotData.dDx2 = $("#dDx2").val();
        ballotData.dWDx2 = $("#dWDx2").val();
        ballotData.dWCx2 = $("#dWCx2").val();
        ballotData.pCx2 = $("#pCx2").val();
        ballotData.dDx3 = $("#dDx3").val();
        ballotData.dWDx3 = $("#pOpen").val();
        ballotData.dWCx3 = $("#dWCx3").val();
        ballotData.pCx3 = $("#pCx3").val();
        ballotData.pClose = $("#pClose").val();
        ballotData.dClose = $("#dClose").val();

        let plaintiffPoints = parseInt(ballotData.pOpen) +
                parseInt(ballotData.pDx1) +
                parseInt(ballotData.pDx2) +
                parseInt(ballotData.pDx3) +
                parseInt(ballotData.pWDx1) +
                parseInt(ballotData.pWDx2) +
                parseInt(ballotData.pWDx3) +
                parseInt(ballotData.pWCx1) +
                parseInt(ballotData.pWCx2) +
                parseInt(ballotData.pWCx3) +
                parseInt(ballotData.pCx1) +
                parseInt(ballotData.pCx2) +
                parseInt(ballotData.pCx3) +
                parseInt(ballotData.pClose);
        let defensePoints = parseInt(ballotData.dOpen) +
                parseInt(ballotData.dDx1) +
                parseInt(ballotData.dDx2) +
                parseInt(ballotData.dDx3) +
                parseInt(ballotData.dWDx1) +
                parseInt(ballotData.dWDx2) +
                parseInt(ballotData.dWDx3) +
                parseInt(ballotData.dWCx1) +
                parseInt(ballotData.dWCx2) +
                parseInt(ballotData.dWCx3) +
                parseInt(ballotData.dCx1) +
                parseInt(ballotData.dCx2) +
                parseInt(ballotData.dCx3) +
                parseInt(ballotData.dClose);

        $("#lockModalText").html(
                `Plaintiff Points: ${plaintiffPoints}<br>` +
                `Defense Points: ${defensePoints}<br>` +
                `Locking the ballot will prevent further changes. If you need to modify the ballot, you will have to contact the tournament's tabulation director at allen@allenbarr.com. Would you like to lock the ballot?`);
        $("#lockModal").modal();
    }

});

$("#lockButton").on("click", function () {
    let ballotData = {"pOpen": $("#pOpen").val()};
    ballotData.pOpenComments = $("#pOpenComments").val();
    ballotData.dOpen = $("#dOpen").val();
    ballotData.dOpenComments = $("#dOpenComments").val();
    ballotData.pDx1 = $("#pDx1").val();
    ballotData.pDx1Comments = $("#pDx1Comments").val();
    ballotData.pWDx1 = $("#pWDx1").val();
    ballotData.pWDx1Comments = $("#pWDx1Comments").val();
    ballotData.pWCx1 = $("#pWCx1").val();
    ballotData.pWCx1Comments = $("#pWCx1Comments").val();
    ballotData.dCx1 = $("#dCx1").val();
    ballotData.dCx1Comments = $("#dCx1Comments").val();
    ballotData.pDx2 = $("#pDx2").val();
    ballotData.pDx2Comments = $("#pDx2Comments").val();
    ballotData.pWDx2 = $("#pWDx2").val();
    ballotData.pWDx2Comments = $("#pWDx2Comments").val();
    ballotData.pWCx2 = $("#pWCx2").val();
    ballotData.pWCx2Comments = $("#pWCx2Comments").val();
    ballotData.dCx2 = $("#dCx2").val();
    ballotData.dCx2Comments = $("#dCx2Comments").val();
    ballotData.pDx3 = $("#pDx3").val();
    ballotData.pDx3Comments = $("#pDx3Comments").val();
    ballotData.pWDx3 = $("#pWDx3").val();
    ballotData.pWDx3Comments = $("#pWDx3Comments").val();
    ballotData.pWCx3 = $("#pWCx3").val();
    ballotData.pWCx3Comments = $("#pWCx3Comments").val();
    ballotData.dCx3 = $("#dCx3").val();
    ballotData.dCx3Comments = $("#dCx3Comments").val();
    ballotData.dDx1 = $("#dDx1").val();
    ballotData.dDx1Comments = $("#dDx1Comments").val();
    ballotData.dWDx1 = $("#dWDx1").val();
    ballotData.dWDx1Comments = $("#dWDx1Comments").val();
    ballotData.dWCx1 = $("#dWCx1").val();
    ballotData.dWCx1Comments = $("#dWCx1Comments").val();
    ballotData.pCx1 = $("#pCx1").val();
    ballotData.pCx1Comments = $("#pCx1Comments").val();
    ballotData.dDx2 = $("#dDx2").val();
    ballotData.dDx2Comments = $("#dDx2Comments").val();
    ballotData.dWDx2 = $("#dWDx2").val();
    ballotData.dWDx2Comments = $("#dWDx2Comments").val();
    ballotData.dWCx2 = $("#dWCx2").val();
    ballotData.dWCx2Comments = $("#dWCx2Comments").val();
    ballotData.pCx2 = $("#pCx2").val();
    ballotData.pCx2Comments = $("#pCx2Comments").val();
    ballotData.dDx3 = $("#dDx3").val();
    ballotData.dDx3Comments = $("#dDx3Comments").val();
    ballotData.dWDx3 = $("#pOpen").val();
    ballotData.dWDx3Comments = $("#dWDx3Comments").val();
    ballotData.dWCx3 = $("#dWCx3").val();
    ballotData.dWCx3Comments = $("#dWCx3Comments").val();
    ballotData.pCx3 = $("#pCx3").val();
    ballotData.pCx3Comments = $("#pCx3Comments").val();
    ballotData.pClose = $("#pClose").val();
    ballotData.pCloseComments = $("#pCloseComments").val();
    ballotData.dClose = $("#dClose").val();
    ballotData.dCloseComments = $("#dCloseComments").val();
    ballotData.aty1 = $("#aty1").val();
    ballotData.aty2 = $("#aty2").val();
    ballotData.aty3 = $("#aty3").val();
    ballotData.aty4 = $("#aty4").val();
    ballotData.wit1 = $("#wit1").val();
    ballotData.wit2 = $("#wit2").val();
    ballotData.wit3 = $("#wit3").val();
    ballotData.wit4 = $("#wit4").val();
    ballotData.url = url;

    $.ajax({
        url: "../api/judgeBallot/lockBallot.php",
        method: "POST",
        data: ballotData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            alert("Ballot successfully submitted. If you need to make any changes, please contact allen@allenbarr.com.");
        } else {
            alert(response.message);
        }
    });
});

function validateBallot() {
    //check individual awards
    let individuals = $("select");
    for (var a = 0; a < individuals.length; a++) {
        if (individuals[a].value === "N/A") {
            alert("Please fill out all individual awards.")
            return false;
        }
    }
    if (!awardsUnique(individuals)) {
        alert("Please ensure you have different individuals listed for each individual award ranking.");
        return false;
    }

    //check scores
    let scores = $("input[type='number']");
    for (var a = 0; a < scores.length; a++) {
        if (!validateScore(scores[a].value)) {
            let part = determinePart(scores[a].id);
            alert("Error: " + part + " must be a number between 0 and 10.");
            return false;
        } else if (parseInt(scores[a].value) === 0 && !zeroScore) {
            let part = determinePart(scores[a].id);
            alert("You have entered a 0 for: " + part + ". A score of 0 may only be used if the part was " +
                    "not performed. If the part was performed, please enter a score. If the part was not performed, please click the lock button again to confirm.");
            zeroScore = true;
            return false;
        }
    }

    return true;
}

function updateScore(part, score) {
    let updateData = `{"part":"${part}","score":${score},"url":"${url}"}`;

    $.ajax({
        url: "../api/judgeBallot/updateScore.php",
        method: "POST",
        data: updateData,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {

        } else {
            alert(response.message);
        }
    });
}

function validateScore(score) {
    if (!score) {
        return false;
    } else if (score === "") {
        return false
    } else if (isNaN(score)) {
        return false;
    } else if (!Number.isInteger(score * 1)) {
        return false;
    } else if (score < 0) {
        return false;
    } else if (score > 10) {
        return false;
    } else {
        return true;
    }
}

function determinePart(part) {
    switch (part) {
        case "pOpen":
            return "Plaintiff Open";
            break;
        case "dOpen":
            return "Defense Open";
            break;
        case "pDx1":
            return "Plaintiff Directing Attorney 1";
            break;
        case "pDx2":
            return "Plaintiff Directing Attorney 2";
            break;
        case "pDx3":
            return "Plaintiff Directing Attorney 3";
            break;
        case "pCx1":
            return "Plaintiff Crossing Attorney 1";
            break;
        case "pCx2":
            return "Plaintiff Crossing Attorney 2";
            break;
        case "pCx3":
            return "Plaintiff Crossing Attorney 3";
            break;
        case "dDx1":
            return "Defense Directing Attorney 1";
            break;
        case "dDx2":
            return "Defense Directing Attorney 2";
            break;
        case "dDx3":
            return "Defense Directing Attorney 3";
            break;
        case "dCx1":
            return "Defense Crossing Attorney 1";
            break;
        case "dCx2":
            return "Defense Crossing Attorney 2";
            break;
        case "dCx3":
            return "Defense Crossing Attorney 3";
            break;
        case "dWDx1":
            return "Defense Witness Direct 1";
            break;
        case "dWDx2":
            return "Defense Witness Direct 2";
            break;
        case "dWDx3":
            return "Defense Witness Direct 3";
            break;
        case "dWCx1":
            return "Defense Witness Cross 1";
            break;
        case "dWCx2":
            return "Defense Witness Cross 2";
            break;
        case "dWCx3":
            return "Defense Witness Cross 3";
            break;
        case "pWDx1":
            return "Plaintiff Witness Direct 1";
            break;
        case "pWDx2":
            return "Plaintiff Witness Direct 2";
            break;
        case "pWDx3":
            return "Plaintiff Witness Direct 3";
            break;
        case "pWCx1":
            return "Plaintiff Witness Cross 1";
            break;
        case "pWCx2":
            return "Plaintiff Witness Cross 2";
            break;
        case "pWCx3":
            return "Plaintiff Witness Cross 3";
            break;
        case "pClose":
            return "Plaintiff Close";
            break;
        case "dClose":
            return "Defense Close";
            break;

    }
}

function awardsUnique(individuals) {
    let individualsSet = new Set();
    for (var a = 0; a < individuals.length; a++) {
        individualsSet.add(individuals[a].value);
    }
    let individualsArray = [];
    for (var a = 0; a < individuals.length; a++) {
        individualsArray.push(individuals[a].value);
    }
    if (individualsArray.length === individualsSet.size) {
        return true;
    } else {
        return false;
    }
}