/* 
 * Copyright (C) 2021 allen
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

var currentRound;
var ballots;

$(document).ready(function () {
    switch (currentRound) {
        case 1:
            $('.nav-tabs a[href="#round1"]').tab('show');
            break;
        case 2:
            $('.nav-tabs a[href="#round2"]').tab('show');
            break;
        case 3:
            $('.nav-tabs a[href="#round3"]').tab('show');
            break;
        case 4:
            $('.nav-tabs a[href="#round4"]').tab('show');
            break;
    }
    setInterval(updateTable, 12000);
});

function updateBallotData() {
    return new Promise((resolve, reject) => {
        $.get("api/ballots/getAll.php", function (ballotData) {
            ballots = ballotData;
            resolve();
        }, "json");
    });
}

function updateTable() {
    updateBallotData().then(function () {
        ballots.forEach(function (ballot) {
            let scoreString = generateScoreString(ballot);
            $(`#${ballot.id}`).children(".score").html(scoreString);
            let statusString = generateStatusString(ballot);
            $(`#${ballot.id}`).children(".status").html(statusString);
        });
    });
}

function generateStatusString(ballot) {
    if (ballot.locked) {
        return "Finished";
    } else if (ballot.pOpen === 0) {
        return "Opening";
    } else if (ballot.pDx1 === 0) {
        return "π Wit 1";
    } else if (ballot.pDx2 === 0) {
        return "π Wit 2";
    } else if (ballot.pDx3 === 0) {
        return "π Wit 3";
    } else if (ballot.dDx1 === 0) {
        return "∆ Wit 1";
    } else if (ballot.dDx2 === 0) {
        return "∆ Wit 2";
    } else if (ballot.dDx3 === 0) {
        return "∆ Wit 3";
    } else if (ballot.pClose === 0) {
        return "Closing";
    } else {
        return "Almost Done";
    }
}

function generateScoreString(ballot) {
    let pTotal = totalPlaintiffPoints(ballot);
    let dTotal = totalDefensePoints(ballot);
    var scoreString;
    if (pTotal > dTotal) {
        scoreString = `π +${pTotal - dTotal}`;
    } else if (pTotal < dTotal) {
        scoreString = `∆ +${dTotal - pTotal}`;
    } else {
        scoreString = "T +0";
    }
    return scoreString;
}

function totalPlaintiffPoints(ballot) {
    var total = 0;
    total += ballot.pClose;
    total += ballot.pCx1;
    total += ballot.pCx2;
    total += ballot.pCx3;
    total += ballot.pDx1;
    total += ballot.pDx2;
    total += ballot.pDx3;
    total += ballot.pOpen;
    total += ballot.pWCx1;
    total += ballot.pWCx2;
    total += ballot.pWCx3;
    total += ballot.pWDx1;
    total += ballot.pWDx2;
    total += ballot.pWDx3;
    return total;
}

function totalDefensePoints(ballot) {
    var total = 0;
    total += ballot.dClose;
    total += ballot.dCx1;
    total += ballot.dCx2;
    total += ballot.dCx3;
    total += ballot.dDx1;
    total += ballot.dDx2;
    total += ballot.dDx3;
    total += ballot.dOpen;
    total += ballot.dWCx1;
    total += ballot.dWCx2;
    total += ballot.dWCx3;
    total += ballot.dWDx1;
    total += ballot.dWDx2;
    total += ballot.dWDx3;
    return total;
}
