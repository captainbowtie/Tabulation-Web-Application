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
var impermissibles;
var deleteTeamNumber;
var editTeamNumber;

$(document).ready(function () {
    fillData(parseInt($("#number").html()));
});

function fillData(number) {
    updateData().then(data => {
        fillTabCard();
        fillImpermissibles();

        function fillTabCard() {
            //update number and name
            for (var a = 0; a < teams.length; a++) {
                if (teams[a].number === number) {
                    $("#name").html(teams[a].name);
                }
            }
            //check that a pairing exists for particular round, if so, update as much of column as possible
            for (var a = 0; a < pairings.length; a++) {
                if (pairings[a].plaintiff === number) {//checks if pairing exists
                    //if pairing exists on plaintiff side, update with side and opponent
                    $("#round" + pairings[a].round + "Pairing").html("π v " + pairings[a].defense);

                    //then, check if any ballots exist for the round
                    var roundBallots = [];
                    for (var b = 0; b < ballots.length; b++) {
                        if (ballots[b].pairing === pairings[a].id) {
                            roundBallots.push(ballots[b]);
                        }
                    }
                    //if ballots do exist, use them to fill remaining rows of the round column
                    //Start with the ballots row, creating a table within the cell showing W,L,T and PD
                    var ballotHTML = "<table>\n";
                    if (roundBallots.length > 0) {
                        ballotHTML += "<tr>\n";//W,L, or T row
                    }
                    for (var b = 0; b < roundBallots.length; b++) {
                        ballotHTML += "<td>";
                        if (roundBallots[b].plaintiffPD > 0) {
                            ballotHTML += "W";
                        } else if (roundBallots[b].plaintiffPD === 0) {
                            ballotHTML += "T";
                        } else {
                            ballotHTML += "L";
                        }
                        ballotHTML += "</td>\n";
                    }
                    if (roundBallots.length > 0) {
                        ballotHTML += "</tr>\n<tr>";//PD row
                    }
                    for (var b = 0; b < roundBallots.length; b++) {
                        ballotHTML += "<td>" + roundBallots[b].plaintiffPD + "</td>\n";
                    }
                    if (roundBallots.length > 0) {
                        ballotHTML += "</tr>\n";
                    }
                    ballotHTML += "</table>\n";
                    $("#round" + pairings[a].round + "Ballots").html(ballotHTML);

                } else if (pairings[a].defense === number) {//checks if pairing exists
                    //if pairing exists on defense side, update with side and opponent
                    $("#round" + pairings[a].round + "Pairing").html("∆ v " + pairings[a].plaintiff);
                }
            }
        }
        function fillImpermissibles() {
            var teamImpermissibles = [];
            for (var a = 0; a < impermissibles.length; a++) {
                if (impermissibles[a].team0 === number) {
                    teamImpermissibles.push(impermissibles[a].team1);
                } else if (impermissibles[a].team1 === number) {
                    teamImpermissibles.push(impermissibles[a].team0);
                }
            }
            if (teamImpermissibles.length > 0) {
                var impermissibleHTML = "<table>\n";
                for (var a = 0; a < teamImpermissibles.length; a++) {
                    impermissibleHTML += "<tr>\n<td>";
                    for (var b = 0; b < teams.length; b++) {
                        if (teams[b].number === teamImpermissibles[a]) {
                            impermissibleHTML += teams[b].number + "</td>\n<td>" + teams[b].name + "</td>\n";
                            impermissibleHTML += "<td><a href='' class='delete' id='delete" + teams[b].number + "'>Delete</a></td>\n";
                            b = teams.length;
                        }
                    }
                    impermissibleHTML += "</td>\n</tr>\n";
                }
                impermissibleHTML += "</table>\n";
                $("#impermissibles").html(impermissibleHTML);
            }
        }
    });
}

$("body").on("click", ".delete", function (e) {
    e.preventDefault();
    deleteTeamNumber = e.target.id.substring(6);
    deleteConflictModal();
});

$("body").on("click", "#deleteConflictButton", function (e) {
    e.preventDefault();
    let data = `{"team0":${deleteTeamNumber},"team1":${$("#number").html()}}`;
    $.ajax({
        url: "../api/impermissibles/delete.php",
        method: "POST",
        data: data,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
});

$("#addButton").on("click", function (e) {
    e.preventDefault();
    if (validateNumber($("#newImpermissible").val())) {
        createImpermissible($("#newImpermissible").val());
    }
});

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

function getImpermissibles() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/impermissibles/getAll.php",
            dataType: "json"
        }).then(impermissibles => {
            resolve(impermissibles);
        });
    });
}

function updateData() {
    return new Promise(function (resolve, reject) {
        Promise.all([getTeams(), getPairings(), getImpermissibles(), getBallots()]).then(data => {
            teams = data[0];
            pairings = data[1];
            impermissibles = data[2];
            ballots = data[3];
            resolve();
        });
    });
}

function createImpermissible(number) {
    let data = `{"team0":${number},"team1":${$("#number").html()}}`;
    $.ajax({
        url: "../api/impermissibles/create.php",
        method: "POST",
        data: data,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            $("#newImpermissible").val("");
            window.location.reload();
        } else {
            warningModal(response.message);
        }
    });
}

function deleteConflictModal() {
    $("#deleteModalText").html(`Are you sure you want to delete team ${deleteTeamNumber} from this team's conflict list?`);
    $("#deleteConflictModal").modal();
}

function warningModal(warning) {
    $("#warningModalText").text(warning);
    $("#warningModal").modal();
}

function validateNumber(number) {
    return /^-{0,1}\d+$/.test(number);
}