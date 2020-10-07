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

var round1Ballots;
var round2Ballots;
var round3Ballots;
var round4Ballots;
var round1Pairings;
var round2Pairings;
var round3Pairings;
var round4Pairings;
var pairingOptions;

$("document").ready(function () {
    round1Ballots = [];
    round2Ballots = [];
    round3Ballots = [];
    round4Ballots = [];
    round1Pairings = new Set();
    round2Pairings = new Set();
    round3Pairings = new Set();
    round4Pairings = new Set();

    let maxRound = 1;
    for (var a = 0; a < ballots.length; a++) {
        switch (ballots[a].round) {
            case 1:
                round1Ballots.push(ballots[a]);
                round1Pairings.add(`<option value='${ballots[a].pNumber}'>` + ballots[a].pNumber + " v. " + ballots[a].dNumber + "</option>");
                break;
            case 2:
                round2Ballots.push(ballots[a]);
                round2Pairings.add(`<option value='${ballots[a].pNumber}'>` + ballots[a].pNumber + " v. " + ballots[a].dNumber + "</option>");
                break;
            case 3:
                round3Ballots.push(ballots[a]);
                round3Pairings.add(`<option value='${ballots[a].pNumber}'>` + ballots[a].pNumber + " v. " + ballots[a].dNumber + "</option>");
                break;
            case 4:
                round4Ballots.push(ballots[a]);
                round4Pairings.add(`<option value='${ballots[a].pNumber}'>` + ballots[a].pNumber + " v. " + ballots[a].dNumber + "</option>");
                break;
        }
        if (ballots[a].round > maxRound) {
            maxRound = ballots[a].round;
        }
    }

    pairingOptions = [];
    pairingOptions[1] = "";
    pairingOptions[2] = "";
    pairingOptions[3] = "";
    pairingOptions[4] = "";
    for (let pairing of round1Pairings) {
        pairingOptions[1] += pairing;
    }
    for (let pairing of round2Pairings) {
        pairingOptions[2] += pairing;
    }
    for (let pairing of round3Pairings) {
        pairingOptions[3] += pairing;
    }
    for (let pairing of round4Pairings) {
        pairingOptions[4] += pairing;
    }

    switch (maxRound) {
        case 1:
            $("#round").val(1);
            updatePairings();
            break;
        case 2:
            $("#round").val(2);
            updatePairings();
            break;
        case 3:
            $("#round").val(3);
            updatePairings();
            break;
        case 4:
            $("#round").val(4);
            updatePairings();
            break;
    }
});

$("#round").on("change", function () {
    updatePairings();
});

$("#pairing").on("change", function () {
    updateBallots();
});

$("#ballot").on("change", function () {
    fillBallot();
});

function updatePairings() {
    $("#pairing").html(pairingOptions[$("#round").val()]);
    updateBallots();
}

function updateBallots() {
    var ballotOptions = "";
    for (var a = 0; a < ballots.length; a++) {
        if (ballots[a].round === parseInt($("#round").val()) && ballots[a].pNumber === parseInt($("#pairing").val())) {
            ballotOptions += `<option data-plaintiff='${ballots[a].pNumber}' data-round='${ballots[a].round}' value='${ballots[a].judge}'>` + `${ballots[a].judge}` + "</option>";
        }
    }
    $("#ballot").html(ballotOptions);
    fillBallot();
}

function fillBallot() {
    for (var a = 0; a < ballots.length; a++) {
        if (ballots[a].judge === $('option:selected', "#ballot").val() && ballots[a].round === parseInt($('option:selected', "#ballot").attr("data-round")) && ballots[a].pNumber === parseInt($('option:selected', "#ballot").attr("data-plaintiff"))) {
            $("#pOpen").val(ballots[a].pOpen);
            $("#pOpenComments").val(ballots[a].pOpenComments);
            $("#dOpen").val(ballots[a].dOpen);
            $("#dOpenComments").val(ballots[a].dOpenComments);
            $("#pDx1").val(ballots[a].pDx1);
            $("#pDx1Comments").val(ballots[a].pDx1Comments);
            $("#pWDx1").val(ballots[a].pWDx1);
            $("#pWDx1Comments").val(ballots[a].pWDx1Comments);
            $("#pWCx1").val(ballots[a].pWCx1);
            $("#pWCx1Comments").val(ballots[a].pWCx1Comments);
            $("#dCx1").val(ballots[a].dCx1);
            $("#dCx1Comments").val(ballots[a].dCx1Comments);
            $("#pDx2").val(ballots[a].pDx2);
            $("#pDx2Comments").val(ballots[a].pDx2Comments);
            $("#pWDx2").val(ballots[a].pWDx2);
            $("#pWDx2Comments").val(ballots[a].pWDx2Comments);
            $("#pWCx2").val(ballots[a].pWCx2);
            $("#pWCx2Comments").val(ballots[a].pWCx2Comments);
            $("#dCx2").val(ballots[a].dCx2);
            $("#dCx2Comments").val(ballots[a].dCx2Comments);
            $("#pDx3").val(ballots[a].pDx3);
            $("#pDx3Comments").val(ballots[a].pDx3Comments);
            $("#pWDx3").val(ballots[a].pWDx3);
            $("#pWDx3Comments").val(ballots[a].pWDx3Comments);
            $("#pWCx3").val(ballots[a].pWCx3);
            $("#pWCx3Comments").val(ballots[a].pWCx3Comments);
            $("#dCx3").val(ballots[a].dCx3);
            $("#dCx3Comments").val(ballots[a].dCx3Comments);
            $("#dDx1").val(ballots[a].dDx1);
            $("#dDx1Comments").val(ballots[a].dDx1Comments);
            $("#dWDx1").val(ballots[a].dWDx1);
            $("#dWDx1Comments").val(ballots[a].dWDx1Comments);
            $("#dWCx1").val(ballots[a].dWCx1);
            $("#dWCx1Comments").val(ballots[a].dWCx1Comments);
            $("#pCx1").val(ballots[a].pCx1);
            $("#pCx1Comments").val(ballots[a].pCx1Comments);
            $("#dDx2").val(ballots[a].dDx2);
            $("#dDx2Comments").val(ballots[a].dDx2Comments);
            $("#dWDx2").val(ballots[a].dWDx2);
            $("#dWDx2Comments").val(ballots[a].dWDx2Comments);
            $("#dWCx2").val(ballots[a].dWCx2);
            $("#dWCx2Comments").val(ballots[a].dWCx2Comments);
            $("#pCx2").val(ballots[a].pCx2);
            $("#pCx2Comments").val(ballots[a].pCx2Comments);
            $("#dDx3").val(ballots[a].dDx3);
            $("#dDx3Comments").val(ballots[a].dDx3Comments);
            $("#dWDx3").val(ballots[a].dWDx3);
            $("#dWDx3Comments").val(ballots[a].dWDx3Comments);
            $("#dWCx3").val(ballots[a].dWCx3);
            $("#dWCx3Comments").val(ballots[a].dWCx3Comments);
            $("#pCx3").val(ballots[a].pCx3);
            $("#pCx3Comments").val(ballots[a].pCx3Comments);
            $("#pClose").val(ballots[a].pClose);
            $("#pCloseComments").val(ballots[a].pCloseComments);
            $("#dClose").val(ballots[a].dClose);
            $("#dCloseComments").val(ballots[a].dCloseComments);
            $("#aty1").val(ballots[a].aty1);
            $("#aty2").val(ballots[a].aty2);
            $("#aty3").val(ballots[a].aty3);
            $("#aty4").val(ballots[a].aty4);
            $("#wit1").val(ballots[a].wit1);
            $("#wit2").val(ballots[a].wit2);
            $("#wit3").val(ballots[a].wit3);
            $("#wit4").val(ballots[a].wit4);
            $("#pOpenLabel").html(`π Open (${ballots[a].pOpenAttorney}):`);
            $("#dOpenLabel").html(`∆ Open (${ballots[a].dOpenAttorney}):`);
            $("#pDx1Label").html(`Aty Dx (${ballots[a].pDx1Attorney}):`);
            $("#pWDx1Label").html(`Wit Dx (${ballots[a].pWDx1Witness}):`);
            $("#pWCx1Label").html(`Wit Cx (${ballots[a].pWDx1Witness}):`);
            $("#dCx1Label").html(`Aty Cx (${ballots[a].dCx1Attorney}):`);
            $("#pDx2Label").html(`Aty Dx (${ballots[a].pDx2Attorney}):`);
            $("#pWDx2Label").html(`Wit Dx (${ballots[a].pWDx2Witness}):`);
            $("#pWCx2Label").html(`Wit Cx (${ballots[a].pWDx2Witness}):`);
            $("#dCx2Label").html(`Aty Cx (${ballots[a].dCx2Attorney}):`);
            $("#pDx3Label").html(`Aty Dx (${ballots[a].pDx3Attorney}):`);
            $("#pWDx3Label").html(`Wit Dx (${ballots[a].pWDx3Witness}):`);
            $("#pWCx3Label").html(`Wit Cx (${ballots[a].pWDx3Witness}):`);
            $("#dCx3Label").html(`Aty Cx (${ballots[a].dCx3Attorney}):`);
            $("#dDx1Label").html(`Aty Dx (${ballots[a].dDx1Attorney}):`);
            $("#dWDx1Label").html(`Wit Dx (${ballots[a].dWDx1Witness}):`);
            $("#dWCx1Label").html(`Wit Cx (${ballots[a].dWDx1Witness}):`);
            $("#pCx1Label").html(`Aty Cx (${ballots[a].pCx1Attorney}):`);
            $("#dDx2Label").html(`Aty Dx (${ballots[a].dDx2Attorney}):`);
            $("#dWDx2Label").html(`Wit Dx (${ballots[a].dWDx2Witness}):`);
            $("#dWCx2Label").html(`Wit Cx (${ballots[a].dWDx2Witness}):`);
            $("#pCx2Label").html(`Aty Cx (${ballots[a].pCx2Attorney}):`);
            $("#dDx3Label").html(`Aty Dx (${ballots[a].dDx3Attorney}):`);
            $("#dWDx3Label").html(`Wit Dx (${ballots[a].dWDx3Witness}):`);
            $("#dWCx3Label").html(`Wit Cx (${ballots[a].dWDx3Witness}):`);
            $("#pCx3Label").html(`Aty Cx (${ballots[a].pCx3Attorney}):`);
            $("#pCloseLabel").html(`π Open (${ballots[a].pCloseAttorney}):`);
            $("#dCloseLabel").html(`∆ Open (${ballots[a].dCloseAttorney}):`);
            $("#witness1Header").html(`Plaintiff Witness 1 (${ballots[a].witness1}):`);
            $("#witness2Header").html(`Plaintiff Witness 2 (${ballots[a].witness2}):`);
            $("#witness3Header").html(`Plaintiff Witness 3 (${ballots[a].witness3}):`);
            $("#witness4Header").html(`Defense Witness 1 (${ballots[a].witness4}):`);
            $("#witness5Header").html(`Defense Witness 2 (${ballots[a].witness5}):`);
            $("#witness6Header").html(`Defense Witness 3 (${ballots[a].witness6}):`);
        }
    }
}