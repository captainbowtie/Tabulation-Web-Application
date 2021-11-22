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
            $("#pOpen").html(ballots[a].pOpen);
            $("#pOpenComments").html(ballots[a].pOpenComments);
            $("#dOpen").html(ballots[a].dOpen);
            $("#dOpenComments").html(ballots[a].dOpenComments);
            $("#pDx1").html(ballots[a].pDx1);
            $("#pDx1Comments").html(ballots[a].pDx1Comments);
            $("#pWDx1").html(ballots[a].pWDx1);
            $("#pWDx1Comments").html(ballots[a].pWDx1Comments);
            $("#pWCx1").html(ballots[a].pWCx1);
            $("#pWCx1Comments").html(ballots[a].pWCx1Comments);
            $("#dCx1").html(ballots[a].dCx1);
            $("#dCx1Comments").html(ballots[a].dCx1Comments);
            $("#pDx2").html(ballots[a].pDx2);
            $("#pDx2Comments").html(ballots[a].pDx2Comments);
            $("#pWDx2").html(ballots[a].pWDx2);
            $("#pWDx2Comments").html(ballots[a].pWDx2Comments);
            $("#pWCx2").html(ballots[a].pWCx2);
            $("#pWCx2Comments").html(ballots[a].pWCx2Comments);
            $("#dCx2").html(ballots[a].dCx2);
            $("#dCx2Comments").html(ballots[a].dCx2Comments);
            $("#pDx3").html(ballots[a].pDx3);
            $("#pDx3Comments").html(ballots[a].pDx3Comments);
            $("#pWDx3").html(ballots[a].pWDx3);
            $("#pWDx3Comments").html(ballots[a].pWDx3Comments);
            $("#pWCx3").html(ballots[a].pWCx3);
            $("#pWCx3Comments").html(ballots[a].pWCx3Comments);
            $("#dCx3").html(ballots[a].dCx3);
            $("#dCx3Comments").html(ballots[a].dCx3Comments);
            $("#dDx1").html(ballots[a].dDx1);
            $("#dDx1Comments").html(ballots[a].dDx1Comments);
            $("#dWDx1").html(ballots[a].dWDx1);
            $("#dWDx1Comments").html(ballots[a].dWDx1Comments);
            $("#dWCx1").html(ballots[a].dWCx1);
            $("#dWCx1Comments").html(ballots[a].dWCx1Comments);
            $("#pCx1").html(ballots[a].pCx1);
            $("#pCx1Comments").html(ballots[a].pCx1Comments);
            $("#dDx2").html(ballots[a].dDx2);
            $("#dDx2Comments").html(ballots[a].dDx2Comments);
            $("#dWDx2").html(ballots[a].dWDx2);
            $("#dWDx2Comments").html(ballots[a].dWDx2Comments);
            $("#dWCx2").html(ballots[a].dWCx2);
            $("#dWCx2Comments").html(ballots[a].dWCx2Comments);
            $("#pCx2").html(ballots[a].pCx2);
            $("#pCx2Comments").html(ballots[a].pCx2Comments);
            $("#dDx3").html(ballots[a].dDx3);
            $("#dDx3Comments").html(ballots[a].dDx3Comments);
            $("#dWDx3").html(ballots[a].dWDx3);
            $("#dWDx3Comments").html(ballots[a].dWDx3Comments);
            $("#dWCx3").html(ballots[a].dWCx3);
            $("#dWCx3Comments").html(ballots[a].dWCx3Comments);
            $("#pCx3").html(ballots[a].pCx3);
            $("#pCx3Comments").html(ballots[a].pCx3Comments);
            $("#pClose").html(ballots[a].pClose);
            $("#pCloseComments").html(ballots[a].pCloseComments);
            $("#dClose").html(ballots[a].dClose);
            $("#dCloseComments").html(ballots[a].dCloseComments);
            $("#aty1").html(ballots[a].aty1);
            $("#aty2").html(ballots[a].aty2);
            $("#aty3").html(ballots[a].aty3);
            $("#aty4").html(ballots[a].aty4);
            $("#wit1").html(ballots[a].wit1);
            $("#wit2").html(ballots[a].wit2);
            $("#wit3").html(ballots[a].wit3);
            $("#wit4").html(ballots[a].wit4);
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

            let plaintiffPoints = parseInt(ballots[a].pOpen) +
                    parseInt(ballots[a].pDx1) +
                    parseInt(ballots[a].pDx2) +
                    parseInt(ballots[a].pDx3) +
                    parseInt(ballots[a].pWDx1) +
                    parseInt(ballots[a].pWDx2) +
                    parseInt(ballots[a].pWDx3) +
                    parseInt(ballots[a].pWCx1) +
                    parseInt(ballots[a].pWCx2) +
                    parseInt(ballots[a].pWCx3) +
                    parseInt(ballots[a].pCx1) +
                    parseInt(ballots[a].pCx2) +
                    parseInt(ballots[a].pCx3) +
                    parseInt(ballots[a].pClose);
            let defensePoints = parseInt(ballots[a].dOpen) +
                    parseInt(ballots[a].dDx1) +
                    parseInt(ballots[a].dDx2) +
                    parseInt(ballots[a].dDx3) +
                    parseInt(ballots[a].dWDx1) +
                    parseInt(ballots[a].dWDx2) +
                    parseInt(ballots[a].dWDx3) +
                    parseInt(ballots[a].dWCx1) +
                    parseInt(ballots[a].dWCx2) +
                    parseInt(ballots[a].dWCx3) +
                    parseInt(ballots[a].dCx1) +
                    parseInt(ballots[a].dCx2) +
                    parseInt(ballots[a].dCx3) +
                    parseInt(ballots[a].dClose);

            if ((plaintiffPoints - defensePoints) > 0) {
                $("#pointTotalDiv").html("Plaintiff wins: +" + (plaintiffPoints - defensePoints));
            } else if ((plaintiffPoints - defensePoints) < 0) {
                $("#pointTotalDiv").html("Defense wins: +" + (defensePoints - plaintiffPoints));
            } else {
                $("#pointTotalDiv").html("Tie");
            }
        }

    }
}