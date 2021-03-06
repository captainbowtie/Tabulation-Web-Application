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
var judges;
var judgeConflicts;
let round1PairingsExist = false;
let round2PairingsExist = false;
let round3PairingsExist = false;
let round4PairingsExist = false;
let round1BallotsExist = false;
let round2BallotsExist = false;
let round3BallotsExist = false;
let round4BallotsExist = false;

var ballotWarningBallots;


$(document).ready(function () {
    //Pull all data from server
    updateData().then(data => {
        //to the extent pairings and judges exist, fill them in
        var round1Pairings = [];
        var round2Pairings = [];
        var round3Pairings = [];
        var round4Pairings = [];
        var round1Ballots = [];
        var round2Ballots = [];
        var round3Ballots = [];
        var round4Ballots = [];
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
            for (var b = 0; b < ballots.length; b++) {
                if (ballots[b].pairing === pairings[a].id) {
                    switch (pairings[a].round) {
                        case 1:
                            round1Ballots.push(ballots[b]);
                            break;
                        case 2:
                            round2Ballots.push(ballots[b]);
                            break;
                        case 3:
                            round3Ballots.push(ballots[b]);
                            break;
                        case 4:
                            round4Ballots.push(ballots[b]);
                            break;
                    }
                }
            }
        }

        //sort pairings and ballots by pairing id
        round1Pairings = round1Pairings.sort(sortByPairing);
        round2Pairings = round2Pairings.sort(sortByPairing);
        round3Pairings = round3Pairings.sort(sortByPairing);
        round4Pairings = round4Pairings.sort(sortByPairing);
        round1Ballots = round1Ballots.sort(sortByPairing);
        round2Ballots = round2Ballots.sort(sortByPairing);
        round3Ballots = round3Ballots.sort(sortByPairing);
        round4Ballots = round4Ballots.sort(sortByPairing);


        //fill in each round's pairings that exist
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

        //fill in each round's judges that exist
        let r1judgeSelects = $(".judgeSelect[data-round='1']");
        let r2judgeSelects = $(".judgeSelect[data-round='2']");
        let r3judgeSelects = $(".judgeSelect[data-round='3']");
        let r4judgeSelects = $(".judgeSelect[data-round='4']");
        for (var a = 0; a < round1Ballots.length; a++) {
            $(r1judgeSelects[a]).val(round1Ballots[a].judge);
            round1BallotsExist = true;
        }
        for (var a = 0; a < round2Ballots.length; a++) {
            $(r2judgeSelects[a]).val(round2Ballots[a].judge);
            round2BallotsExist = true;
        }
        for (var a = 0; a < round3Ballots.length; a++) {
            $(r3judgeSelects[a]).val(round3Ballots[a].judge);
            round3BallotsExist = true;
        }
        for (var a = 0; a < round4Ballots.length; a++) {
            $(r4judgeSelects[a]).val(round4Ballots[a].judge);
            round4BallotsExist = true;
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
        } else {
            $('.nav-tabs a[href="#round1"]').tab('show');
        }

        //if there are an odd number of teams, warn the user
        if (teams.length % 2 !== 0) {
            $("#oddTeamsModal").modal("toggle");
        }
    });
});

$("#pair1").on("click", function (e) {
    e.preventDefault();
    let pairings = pair1();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round1p${a}`).val(pairings[a].plaintiff);
        $(`#round1d${a}`).val(pairings[a].defense);
    }
});

$("#pair2").on("click", function (e) {
    e.preventDefault();
    let pairings = pair2();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round2p${a}`).val(pairings[a].plaintiff);
        $(`#round2d${a}`).val(pairings[a].defense);
    }
});

$("#pair3").on("click", function (e) {
    e.preventDefault();
    let pairings = pair3();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round3p${a}`).val(pairings[a].plaintiff);
        $(`#round3d${a}`).val(pairings[a].defense);
    }
});

$("#pair4").on("click", function (e) {
    e.preventDefault();
    let pairings = pair4();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round4p${a}`).val(pairings[a].plaintiff);
        $(`#round4d${a}`).val(pairings[a].defense);
    }
});

$("#submit1").on("click", function (e) {
    e.preventDefault();
    if (round1PairingsExist) {
        pairingsWarningModal("Round 1 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(1);
    }
});

$("#submit2").on("click", function (e) {
    e.preventDefault();
    if (round2PairingsExist) {
        pairingsWarningModal("Round 2 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(2);
    }
});

$("#submit3").on("click", function (e) {
    e.preventDefault();
    if (round3PairingsExist) {
        pairingsWarningModal("Round 3 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(3);
    }
});

$("#submit4").on("click", function (e) {
    e.preventDefault();
    if (round4PairingsExist) {
        pairingsWarningModal("Round 4 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(4);
    }
});

$("#assignJudges1").on("click", function (e) {
    $(".judgeSelect").css("color", "black");
    e.preventDefault();
    assignJudges(1);
});

$("#assignJudges2").on("click", function (e) {
    $(".judgeSelect").css("color", "black");
    e.preventDefault();
    assignJudges(2);
});

$("#assignJudges3").on("click", function (e) {
    $(".judgeSelect").css("color", "black");
    e.preventDefault();
    assignJudges(3);
});

$("#assignJudges4").on("click", function (e) {
    $(".judgeSelect").css("color", "black");
    e.preventDefault();
    assignJudges(4);
});

function assignJudges(round) {
    //find the judges that can judge this round
    let roundJudges = [];
    switch (round) {
        case 1:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round1 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 2:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round2 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 3:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round3 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 4:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round4 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
    }

    //sort the judges into tiers, but not for round one
    let tier1 = [];
    let tier2 = [];
    let tier3 = [];
    if (round !== 1) {
        for (var a = 0; a < roundJudges.length; a++) {
            switch (roundJudges[a].category) {
                case 1:
                    tier1.push(roundJudges[a]);
                    break;
                case 2:
                    tier2.push(roundJudges[a]);
                    break;
                case 3:
                    tier3.push(roundJudges[a]);
                    break;
            }
        }
    } else {
        for (var a = 0; a < roundJudges.length; a++) {
            tier1.push(roundJudges[a]);
        }
    }

    //create judge assignment array
    let judgesPerRound = parseInt($("#judgesPerRound").val());
    let judgeAssignments = [];
    let i = 0;
    for (var a = 0; a < pairings.length; a++) {
        if (pairings[a].round === round) {
            judgeAssignments[i] = [];
            judgeAssignments[i][0] = pairings[a];
            for (var b = 0; b < judgesPerRound; b++) {
                judgeAssignments[i][b + 1] = 0;
            }
            i++;
        }
    }

    //ye olde monte carlo way of assigning judges
    let judgesValid = false;
    let insufficientJudges = false;
    let attemptCounter = 0;
    while (!judgesValid) {
        let shuffledTier1 = shuffle(tier1);
        let shuffledTier2 = shuffle(tier2);
        let shuffledTier3 = shuffle(tier3);
        for (var a = 0; a < judgesPerRound; a++) {
            for (var b = 0; b < judgeAssignments.length; b++) {
                if (shuffledTier1.length > 0) {
                    judgeAssignments[b][a + 1] = shuffledTier1[0];
                    shuffledTier1.splice(0, 1);
                } else if (shuffledTier2.length > 0) {
                    judgeAssignments[b][a + 1] = shuffledTier2[0];
                    shuffledTier2.splice(0, 1);
                } else if (shuffledTier3.length > 0) {
                    judgeAssignments[b][a + 1] = shuffledTier3[0];
                    shuffledTier3.splice(0, 1);
                } else {
                    insufficientJudges = true;
                }

            }
        }

        //check if assignments valid
        if (insufficientJudges) {
            alert("Insufficent judges to assign automatically. Please assign manually.");
            judgesValid = true;
        } else if (attemptCounter === 100000) {
            $("#judgeAssignmentText").html("Unable to assign judges according to the AMTA rulebook after 100,000 attempts. Keep trying or assign randomly instead?");
            displayAttemptModal(round);
            judgesValid = true;
        } else {
            judgesValid = judgeAssignmentsValid(judgeAssignments);
            attemptCounter++;
            if (judgesValid) {
                fillJudgeSelects(judgeAssignments, round);
            }
        }
    }
}

function displayAttemptModal(round) {
    $("#randomJudges").prop("data-round", round);
    $("#keepTrying").prop("data-round", round);
    $("#judgeAssignmentModal").modal("show");
}

$("#randomJudges").click(function (e) {
    e.preventDefault();
    $("#judgeAssignmentModal").modal("hide");
    $("#judgeAssignmentModal").on("hidden.bs.modal", function (e) {
        $(e.currentTarget).unbind();
        assignJudgesRandomly(parseInt($("#randomJudges").prop("data-round")));
    });
});

$("#keepTrying").click(function (e) {
    e.preventDefault();
    $("#judgeAssignmentModal").modal("hide");
    $("#judgeAssignmentModal").on("hidden.bs.modal", function (e) {
        $(e.currentTarget).unbind();
        assignJudges(parseInt($("#keepTrying").prop("data-round")));
    });

});

function assignJudgesRandomly(round) {
    //find the judges that can judge this round
    let roundJudges = [];
    switch (round) {
        case 1:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round1 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 2:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round2 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 3:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round3 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
        case 4:
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round4 === true) {
                    roundJudges.push(judges[a]);
                }
            }
            break;
    }

    //create judge assignment array
    let judgesPerRound = parseInt($("#judgesPerRound").val());
    let judgeAssignments = [];
    let i = 0;
    for (var a = 0; a < pairings.length; a++) {
        if (pairings[a].round === 4) {
            judgeAssignments[i] = [];
            judgeAssignments[i][0] = pairings[a];
            for (var b = 0; b < judgesPerRound; b++) {
                judgeAssignments[i][b + 1] = 0;
            }
            i++;
        }
    }

    //ye olde monte carlo way of assigning judges
    let judgesValid = false;
    let insufficientJudges = false;
    let attemptCounter = 0;
    while (!judgesValid) {
        let shuffledJudges = shuffle(roundJudges);
        for (var a = 0; a < judgesPerRound; a++) {
            for (var b = 0; b < judgeAssignments.length; b++) {
                if (shuffledJudges.length > 0) {
                    judgeAssignments[b][a + 1] = shuffledJudges[0];
                    shuffledJudges.splice(0, 1);
                } else {
                    insufficientJudges = true;
                }

            }
        }

        //check if assignments valid
        if (insufficientJudges) {
            alert("Insufficent judges to assign automatically. Please assign manually.");
            judgesValid = true;
        } else if (attemptCounter === 100000) {
            alert("Unable to randomly assign judges after 100,000 attempts. Please assign manually.");
            judgesValid = true;
        } else {
            judgesValid = judgeAssignmentsValid(judgeAssignments);
            attemptCounter++;
            if (judgesValid) {
                fillJudgeSelects(judgeAssignments, round);
            }
        }
    }
}

function fillJudgeSelects(judgeAssignments, round) {
    for (var a = 0; a < judgeAssignments.length; a++) {
        for (var b = 1; b < judgeAssignments[a].length; b++) {
            $(`.judgeSelect[data-round='${round}'][data-pairing='${a}'][data-judge='${b - 1}']`).val(judgeAssignments[a][b].id);
        }
    }
}

function judgeAssignmentsValid(judgeAssignments) {
    if (coachConflictsExist(judgeAssignments) || pastRoundConflictsExist(judgeAssignments)) {
        return false;
    } else {
        return true;
    }

    function coachConflictsExist(judgeAssignments) {
        for (var a = 0; a < judgeAssignments.length; a++) {
            for (var b = 1; b <= judgeAssignments[a].length; b++) {
                for (var c = 0; c < judgeConflicts.length; c++) {
                    let judgeID = judgeConflicts[c].judge;
                    let teamNumber = judgeConflicts[c].team;
                    if (judgeAssignments[a][0]["plaintiff"] === teamNumber && judgeAssignments[a][1]["id"] === judgeID) {
                        return true;
                    } else if (judgeAssignments[a][0]["defense"] === teamNumber && judgeAssignments[a][1]["id"] === judgeID) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    function pastRoundConflictsExist(judgeAssignments) {
        for (var a = 0; a < judgeAssignments.length; a++) {
            for (var b = 1; b < judgeAssignments[a].length; b++) {
                for (var c = 0; c < ballots.length; c++) {
                    if (ballots[c].judge === judgeAssignments[a][b].id) {
                        for (var d = 0; d < pairings.length; d++) {
                            if (pairings[d].id === ballots[c].pairing) {
                                if (pairings[d].plaintiff === judgeAssignments[a][0]["plaintiff"]) {
                                    return true;
                                } else if (pairings[d].defense === judgeAssignments[a][0]["plaintiff"]) {
                                    return true;
                                } else if (pairings[d].plaintiff === judgeAssignments[a][0]["defense"]) {
                                    return true;
                                } else if (pairings[d].defense === judgeAssignments[a][0]["defense"]) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
}

$("#submitJudges1").on("click", function (e) {
    e.preventDefault();
    validateJudges(1).then(judgesValid => {
        if (judgesValid) {
            let ballots = generateBallots(1);
            submitBallots(ballots);
        }
    });
});

$("#submitJudges2").on("click", function (e) {
    e.preventDefault();
    validateJudges(2).then(judgesValid => {
        if (judgesValid) {
            let ballots = generateBallots(2);
            submitBallots(ballots);
        }
    });
});

$("#submitJudges3").on("click", function (e) {
    e.preventDefault();
    validateJudges(3).then(judgesValid => {
        if (judgesValid) {
            let ballots = generateBallots(3);
            submitBallots(ballots);
        }
    });
});

$("#submitJudges4").on("click", function (e) {
    e.preventDefault();
    validateJudges(4).then(judgesValid => {
        if (judgesValid) {
            let ballots = generateBallots(4);
            submitBallots(ballots);
        }
    });
});

$("#pairingSave").on("click", function (e) {
    e.preventDefault();
    const roundToSubmit = $(".active").attr("id").substring(5);
    submitPairings(roundToSubmit);
});

$("#ballotSave").on("click", function (e) {
    e.preventDefault();
    submitBallots(ballotWarningBallots);
});

$("#settings").on("submit", e => e.preventDefault());

$("#judgesPerRound").on("change", function () {
    if (Number.isInteger(parseInt($(this).val()))) {
        updateSetting("judgesPerRound", $(this).val());
    }
});

$("#tieBreaker").on("change", function () {
    updateSetting("lowerTeamIsHigherRank", $(this).val());
});

$("#snakeStart").on("change", function () {
    updateSetting("snakeStartsOnPlaintiff", $(this).val());
});

$("#roundFourBallotsViewable").on("change", function () {
    updateSetting("roundFourBallotsViewable", $(this).val());
});

$(".judgeSelect").change(function (e) {
    $(this).css("color", "black");
});

function updateSetting(field, value) {
    let updateData = `{
    "field":"${field}",
    "value":${value}
    }`;
    $.ajax({
        url: "../api/settings/set.php",
        method: "POST",
        data: updateData,
        dataType: "json"
    }).then(response => {
        if (response.message != 0) {
            warningModal(response.message);
        } else if (field === "judgesPerRound") {
            location.reload();
        }
    });
}

function submitPairings(round) {
    //create pairings json object
    let data = `{"round":${round},"pairings":[`;
    for (let a = 0; a < teams.length / 2; a++) {
        let plaintiff = $(`#round${round}p${a}`).val();
        let defense = $(`#round${round}d${a}`).val();
        let pairing = `{"plaintiff":${plaintiff},"defense":${defense}}`;
        data += pairing;
        if (a !== (teams.length / 2 - 1)) {
            data += ",";
        }
    }
    data += "]}";


    //send pairings to the server
    $.ajax({
        url: "../api/pairings/create.php",
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
}

function submitBallots(ballots) {
    data = JSON.stringify(ballots);
    $.ajax({
        url: "../api/ballots/assignJudge.php",
        method: "POST",
        data: data,
        dataType: "json"
    }).then(response => {
        if (response.message === 0) {
            window.location.reload();
        } else {
            alert(response.message);
        }
    });
}

function pair1() {
    var noConflicts = false;
    while (noConflicts === false) {
        //Shuffle the list of teams, assign to plaintiff and defense
        var shuffledTeams = shuffle(teams);
        var plaintiffTeams = [];
        var defenseTeams = [];
        for (var a = 0; a < shuffledTeams.length / 2; a++) {
            plaintiffTeams[a] = shuffledTeams[a * 2];
            defenseTeams[a] = shuffledTeams[a * 2 + 1];
        }
        //check for pairing conflicts
        noConflicts = !pairingsHaveConflicts(plaintiffTeams, defenseTeams);
    }

    //return pairings
    let pairings = [];
    for (var a = 0; a < plaintiffTeams.length; a++) {
        let pairing = `{"plaintiff":${plaintiffTeams[a].number},"defense":${defenseTeams[a].number}}`;
        pairings.push(JSON.parse(pairing));
    }
    return pairings;
}

function pair2() {
    //iterate through each team
    for (var a = 0; a < teams.length; a++) {
        //initialize fields for team
        teams[a].wins = 0.0;
        teams[a].cs = 0.0;
        teams[a].pd = 0.0;
        //for each team, find the pairing it was a part of during round 1
        for (var b = 0; b < pairings.length; b++) {
            //check side team was on during round 1 and flag accordingly
            if (pairings[b].round === 1 && pairings[b].plaintiff === teams[a].number) {
                teams[a].needsPlaintiff = false;
            } else if (pairings[b].round === 1 && pairings[b].defense === teams[a].number) {
                teams[a].needsPlaintiff = true;
            }
        }
    }

    //sort through all ballots and assign wins and PD accordingly
    for (var a = 0; a < ballots.length; a++) {
        for (var b = 0; b < pairings.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (ballots[a].pairing === pairings[b].id) {
                    if (teams[c].number === pairings[b].plaintiff) {
                        if (ballots[a].plaintiffPD > 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd += ballots[a].plaintiffPD;
                    } else if (teams[c].number === pairings[b].defense) {
                        if (ballots[a].plaintiffPD < 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd -= ballots[a].plaintiffPD;
                    }
                }
            }
        }
    }

    //sort through all teams and assign CS       
    for (var a = 0; a < pairings.length; a++) {
        for (var b = 0; b < teams.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (pairings[a].plaintiff === teams[b].number && pairings[a].defense === teams[c].number) {
                    teams[b].cs += teams[c].wins;
                    teams[c].cs += teams[b].wins;
                }
            }
        }
    }

//set bye-team to have record of -1
    for (var a = 0; a < teams.length; a++) {
        if (teams[a].number === 1985) {
            teams[a].wins = -1;
        }
    }

    //divide teams into needs plaintiff and needs defense
    var needsPlaintiffTeams = [];
    var needsDefenseTeams = [];
    for (var a = 0; a < teams.length; a++) {
        if (teams[a].needsPlaintiff === true) {
            needsPlaintiffTeams.push(teams[a]);
        } else {
            needsDefenseTeams.push(teams[a]);
        }
    }

    //rank teams
    var plaintiffTeams = rankTeams(needsPlaintiffTeams);
    var defenseTeams = rankTeams(needsDefenseTeams);

    //print intial pairings
    console.log("Initial Pairings:");
    for (var a = 0; a < plaintiffTeams.length; a++) {
        console.log(plaintiffTeams[a].number + " " + plaintiffTeams[a].wins + " PD: " + plaintiffTeams[a].pd + " v. " + defenseTeams[a].number + " " + defenseTeams[a].wins + " PD: " + defenseTeams[a].pd);
    }

    //check for and resolve conflicts
    var noConflicts = !pairingsHaveConflicts(plaintiffTeams, defenseTeams);
    var swapList = [];
    while (!noConflicts) {
        noConflicts = true;
        for (var a = 0; a < plaintiffTeams.length; a++) {
            var pTeam = [];
            var dTeam = [];
            pTeam.push(plaintiffTeams[a]);
            dTeam.push(defenseTeams[a]);
            if (pairingsHaveConflicts(pTeam, dTeam)) {
                noConflicts = false;
                //create list of proposed swaps
                var pSwaps = [];
                var dSwaps = [];
                for (var b = 0; b < plaintiffTeams.length; b++) {
                    if (b !== a) {
                        //check that proposed swap has not already occurred for plaintiff teams
                        let pSwapExists = false;
                        let dSwapExists = false;
                        let pSwapMin = Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number);
                        let pSwapMax = Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number);
                        let dSwapMin = Math.min(defenseTeams[a].number, defenseTeams[b].number);
                        let dSwapMax = Math.max(defenseTeams[a].number, defenseTeams[b].number);
                        for (var c = 0; c < swapList.length; c++) {
                            if (swapList[c][0] === pSwapMin) {
                                if (swapList[c][1] === pSwapMax) {
                                    pSwapExists = true;
                                }
                            } else if (swapList[c][0] === dSwapMin) {
                                if (swapList[c][1] === dSwapMax) {
                                    dSwapExists = true;
                                }
                            }
                        }

                        //if pSwap has not already been performed, add it to pSwap list
                        if (!pSwapExists) {
                            var pSwap = JSON.parse('{' +
                                    '"number":' + plaintiffTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(plaintiffTeams[a].wins - plaintiffTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(plaintiffTeams[a].pd - plaintiffTeams[b].pd) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }

                        //if dSwap has not already been performed, add it to dSwap list
                        if (!dSwapExists) {
                            var dSwap = JSON.parse('{' +
                                    '"number":' + defenseTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(defenseTeams[a].wins - defenseTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(defenseTeams[a].pd - defenseTeams[b].pd) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            dSwaps.push(dSwap);
                        }
                    }
                }

                //rank the swaps within each constrained side
                pSwaps = rankSwaps(pSwaps);
                dSwaps = rankSwaps(dSwaps);
                //proposed D swap is second to ensure that if all things are equal, defense gets swapped
                var finalSwaps = [pSwaps[0], dSwaps[0]];
                finalSwaps = rankSwaps(finalSwaps);
                //execute the swap
                if (pSwaps.includes(finalSwaps[0])) {
                    for (var b = 0; b < plaintiffTeams.length; b++) {
                        if (plaintiffTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = plaintiffTeams[b];
                            plaintiffTeams[b] = plaintiffTeams[a];
                            plaintiffTeams[a] = swapTeam;
                            swapList.push([Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number), Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number)]);
                            b = plaintiffTeams.length;
                        }
                    }
                } else {
                    for (var b = 0; b < defenseTeams.length; b++) {
                        if (defenseTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = defenseTeams[b];
                            defenseTeams[b] = defenseTeams[a];
                            defenseTeams[a] = swapTeam;
                            swapList.push([Math.min(defenseTeams[a].number, defenseTeams[b].number), Math.max(defenseTeams[a].number, defenseTeams[b].number)]);
                            b = defenseTeams.length;
                        }
                    }
                }

                //print swap to console
                console.log("Swap Performed:");
                for (var b = 0; b < plaintiffTeams.length; b++) {
                    console.log(plaintiffTeams[b].number + " " + plaintiffTeams[b].wins + " PD: " + plaintiffTeams[b].pd + " v. " + defenseTeams[b].number + " " + defenseTeams[b].wins + " PD: " + defenseTeams[b].pd);

                }

                //restart the loop
                a = plaintiffTeams.length;

            }
        }

    }
    //return pairings
    let r2pairings = [];
    for (var a = 0; a < plaintiffTeams.length; a++) {
        let pairing = `{"plaintiff":${plaintiffTeams[a].number},"defense":${defenseTeams[a].number}}`;
        r2pairings.push(JSON.parse(pairing));
    }
    return r2pairings;
}

function pair3() {
    for (var a = 0; a < teams.length; a++) {
        //initialize fields for team
        teams[a].wins = 0.0;
        teams[a].cs = 0.0;
        teams[a].pd = 0.0;
    }

    //sort through all ballots and assign wins and PD accordingly
    for (var a = 0; a < ballots.length; a++) {
        for (var b = 0; b < pairings.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (ballots[a].pairing === pairings[b].id) {
                    if (teams[c].number === pairings[b].plaintiff) {
                        if (ballots[a].plaintiffPD > 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd += ballots[a].plaintiffPD;
                    } else if (teams[c].number === pairings[b].defense) {
                        if (ballots[a].plaintiffPD < 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd -= ballots[a].plaintiffPD;
                    }
                }
            }
        }
    }

    //sort through all teams and assign CS       
    for (var a = 0; a < pairings.length; a++) {
        for (var b = 0; b < teams.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (pairings[a].plaintiff === teams[b].number && pairings[a].defense === teams[c].number) {
                    teams[b].cs += teams[c].wins;
                    teams[c].cs += teams[b].wins;
                }
            }
        }
    }

    //give bye-team a record of -1
    for (var a = 0; a < teams.length; a++) {
        if (teams[a].number === 1985) {
            teams[a].wins = -1;
        }
    }

    //rank teams
    teams = rankTeams(teams);

    //print initial pairings to console
    console.log("Initial Pairings:");
    for (var a = 0; a < teams.length; a += 2) {
        console.log(teams[a].number + " v. " + teams[a + 1].number);
    }

    //check for and resolve conflicts
    var noConflicts = false;
    var swapList = [];
    while (!noConflicts) {
        noConflicts = true;
        //iterate through each pairing
        for (var a = 0; a < teams.length; a += 2) {
            var pTeam = [teams[a]];
            var dTeam = [teams[a + 1]];
            //check if the pairing has a conflict
            if (pairingsHaveConflicts(pTeam, dTeam)) {
                noConflicts = false;
                var pSwaps = [];
                var dSwaps = [];
                for (var b = 0; b < teams.length; b++) {
                    //check that teams will not be swapping with themselves or each other
                    if (a !== b && (a + 1) !== b) {
                        //check that proposed pSwap has not already occurred
                        let pSwapExists = false;
                        let dSwapExists = false;
                        let pSwapMin = Math.min(teams[a].number, teams[b].number);
                        let pSwapMax = Math.max(teams[a].number, teams[b].number);
                        let dSwapMin = Math.min(teams[a + 1].number, teams[b].number);
                        let dSwapMax = Math.max(teams[a + 1].number, teams[b].number);
                        for (var c = 0; c < swapList.length; c++) {
                            if (swapList[c][0] === pSwapMin) {
                                if (swapList[c][1] === pSwapMax) {
                                    pSwapExists = true;
                                }
                            } else if (swapList[c][0] === dSwapMin) {
                                if (swapList[c][1] === dSwapMax) {
                                    dSwapExists = true;
                                }
                            }
                        }

                        //if pSwap has not already occurred, add to possible swap list
                        if (!pSwapExists) {
                            //create pSwap and add to list
                            var pSwap = JSON.parse('{' +
                                    '"number":' + teams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(teams[a].wins - teams[b].wins) + ',' +
                                    '"cs":' + Math.abs(teams[a].cs - teams[b].cs) + ',' +
                                    '"pd":' + Math.abs(teams[a].pd - teams[b].pd) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }

                        //if dSwap has not already occurred, add to possible swap list
                        if (!dSwapExists) {
                            //create dSwap and add to list
                            var dSwap = JSON.parse('{' +
                                    '"number":' + teams[b].number + ',' +
                                    '"rank":' + Math.abs((a + 1) - b) + ',' +
                                    '"wins":' + Math.abs(teams[a + 1].wins - teams[b].wins) + ',' +
                                    '"cs":' + Math.abs(teams[a + 1].cs - teams[b].cs) + ',' +
                                    '"pd":' + Math.abs(teams[a + 1].pd - teams[b].pd) + ',' +
                                    '"rankSum":' + ((a + 1) + b) +
                                    '}');
                            dSwaps.push(dSwap);
                        }
                    }
                }
                //rank swaps
                pSwaps = rankSwaps(pSwaps);
                dSwaps = rankSwaps(dSwaps);
                var finalSwaps = [pSwaps[0], dSwaps[0]];
                finalSwaps = rankSwaps(finalSwaps);
                //perform the swap
                if (pSwaps.includes(finalSwaps[0])) { //perform the pSwap
                    for (var b = 0; b < teams.length; b++) {
                        if (teams[b].number === finalSwaps[0].number) {
                            var swapTeam = teams[b];
                            teams[b] = teams[a];
                            teams[a] = swapTeam;
                            swapList.push([Math.min(teams[a].number, teams[b].number), Math.max(teams[a].number, teams[b].number)]);
                            b = teams.length;
                        }
                    }
                } else {//perform the dSwap
                    for (var b = 0; b < teams.length; b++) {
                        if (teams[b].number === finalSwaps[0].number) {
                            var swapTeam = teams[b];
                            teams[b] = teams[a + 1];
                            teams[a + 1] = swapTeam;
                            swapList.push([Math.min(teams[a + 1].number, teams[b].number), Math.max(teams[a + 1].number, teams[b].number)]);
                            b = teams.length;
                        }
                    }
                }

                //print swap to console
                console.log("Swap Performed:");
                for (var b = 0; b < teams.length; b += 2) {
                    console.log(teams[b].number + " v. " + teams[b + 1].number);
                }

                //restart the loop
                a = teams.length;
            }
        }
    }


    //return pairings, flipping the side assignment of half of them at random
    let r3pairings = [];
    if ($("#snakeStart").val() === "true") {
        for (var a = 0; a < teams.length; a += 2) {
            if (a % 4 === 0) {
                var pairing = `{"plaintiff":${teams[a].number},"defense":${teams[a + 1].number}}`;
            } else {
                var pairing = `{"plaintiff":${teams[a + 1].number},"defense":${teams[a].number}}`;
            }
            r3pairings.push(JSON.parse(pairing));
        }
    } else {
        for (var a = 0; a < teams.length; a += 2) {
            if (a % 4 === 0) {
                var pairing = `{"plaintiff":${teams[a + 1].number},"defense":${teams[a].number}}`;
            } else {
                var pairing = `{"plaintiff":${teams[a].number},"defense":${teams[a + 1].number}}`;
            }
            r3pairings.push(JSON.parse(pairing));
        }
    }
    return r3pairings;
}

function pair4() {
    //iterate through each team
    for (var a = 0; a < teams.length; a++) {
        //initialize fields for team
        teams[a].wins = 0.0;
        teams[a].cs = 0.0;
        teams[a].pd = 0.0;
        //for each team, find the pairing it was a part of during round 3
        for (var b = 0; b < pairings.length; b++) {
            //check side team was on during round 1 and flag accordingly
            if (pairings[b].round === 3 && pairings[b].plaintiff === teams[a].number) {
                teams[a].needsPlaintiff = false;
            } else if (pairings[b].round === 3 && pairings[b].defense === teams[a].number) {
                teams[a].needsPlaintiff = true;
            }
        }
    }

    //sort through all ballots and assign wins and PD accordingly
    for (var a = 0; a < ballots.length; a++) {
        for (var b = 0; b < pairings.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (ballots[a].pairing === pairings[b].id) {
                    if (teams[c].number === pairings[b].plaintiff) {
                        if (ballots[a].plaintiffPD > 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd += ballots[a].plaintiffPD;
                    } else if (teams[c].number === pairings[b].defense) {
                        if (ballots[a].plaintiffPD < 0) {
                            teams[c].wins++;
                        } else if (ballots[a].plaintiffPD === 0) {
                            teams[c].wins += 0.5;
                        }
                        teams[c].pd -= ballots[a].plaintiffPD;
                    }
                }
            }
        }
    }

    //sort through all teams and assign CS       
    for (var a = 0; a < pairings.length; a++) {
        for (var b = 0; b < teams.length; b++) {
            for (var c = 0; c < teams.length; c++) {
                if (pairings[a].plaintiff === teams[b].number && pairings[a].defense === teams[c].number) {
                    teams[b].cs += teams[c].wins;
                    teams[c].cs += teams[b].wins;
                }
            }
        }
    }

    for (var a = 0; a < teams.length; a++) {
        if (teams[a].number === 1985) {
            teams[a].wins = -1;
        }
    }

    //divide teams into needs plaintiff and needs defense
    var needsPlaintiffTeams = [];
    var needsDefenseTeams = [];
    for (var a = 0; a < teams.length; a++) {
        if (teams[a].needsPlaintiff === true) {
            needsPlaintiffTeams.push(teams[a]);
        } else {
            needsDefenseTeams.push(teams[a]);
        }
    }

    //rank teams
    var plaintiffTeams = rankTeams(needsPlaintiffTeams);
    var defenseTeams = rankTeams(needsDefenseTeams);

    //print intial pairings
    console.log("Initial Pairings:");
    for (var a = 0; a < plaintiffTeams.length; a++) {
        console.log(plaintiffTeams[a].number + " v. " + defenseTeams[a].number);
    }

    //check for conflicts
    var noConflicts = !pairingsHaveConflicts(plaintiffTeams, defenseTeams);
    var swapList = [];
    while (!noConflicts) {
        noConflicts = true;
        for (var a = 0; a < plaintiffTeams.length; a++) {
            var pTeam = [];
            var dTeam = [];
            pTeam.push(plaintiffTeams[a]);
            dTeam.push(defenseTeams[a]);
            if (pairingsHaveConflicts(pTeam, dTeam)) {
                noConflicts = false;
                //create list of proposed swaps
                var pSwaps = [];
                var dSwaps = [];
                for (var b = 0; b < plaintiffTeams.length; b++) {
                    if (b !== a) {
                        //check that proposed swap has not already occurred for plaintiff teams
                        let pSwapExists = false;
                        let dSwapExists = false;
                        let pSwapMin = Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number);
                        let pSwapMax = Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number);
                        let dSwapMin = Math.min(defenseTeams[a].number, defenseTeams[b].number);
                        let dSwapMax = Math.max(defenseTeams[a].number, defenseTeams[b].number);
                        for (var c = 0; c < swapList.length; c++) {
                            if (swapList[c][0] === pSwapMin) {
                                if (swapList[c][1] === pSwapMax) {
                                    pSwapExists = true;
                                }
                            } else if (swapList[c][0] === dSwapMin) {
                                if (swapList[c][1] === dSwapMax) {
                                    dSwapExists = true;
                                }
                            }
                        }

                        //check that proposed swap has not already occurred for plaintiff teams
                        if (!pSwapExists) {
                            var pSwap = JSON.parse('{' +
                                    '"number":' + plaintiffTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(plaintiffTeams[a].wins - plaintiffTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(plaintiffTeams[a].pd - plaintiffTeams[b].pd) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }
                        //check that proposed swap has not already occurred for defense teams
                        if (!dSwapExists) {
                            var dSwap = JSON.parse('{' +
                                    '"number":' + defenseTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(defenseTeams[a].wins - defenseTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(defenseTeams[a].pd - defenseTeams[b].pd) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            dSwaps.push(dSwap);
                        }
                    }
                }
                //rank the swaps within each constrained side
                pSwaps = rankSwaps(pSwaps);
                dSwaps = rankSwaps(dSwaps);
                //proposed D swap is second to ensure that if all things are equal, defense gets swapped
                var finalSwaps = [pSwaps[0], dSwaps[0]];
                finalSwaps = rankSwaps(finalSwaps);

                //execute the swap
                if (pSwaps.includes(finalSwaps[0])) {
                    for (var b = 0; b < plaintiffTeams.length; b++) {
                        if (plaintiffTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = plaintiffTeams[b];
                            plaintiffTeams[b] = plaintiffTeams[a];
                            plaintiffTeams[a] = swapTeam;
                            swapList.push([Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number), Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number)]);
                            b = plaintiffTeams.length;
                        }
                    }
                } else {
                    for (var b = 0; b < defenseTeams.length; b++) {
                        if (defenseTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = defenseTeams[b];
                            defenseTeams[b] = defenseTeams[a];
                            defenseTeams[a] = swapTeam;
                            swapList.push([Math.min(defenseTeams[a].number, defenseTeams[b].number), Math.max(defenseTeams[a].number, defenseTeams[b].number)]);
                            b = defenseTeams.length;
                        }
                    }
                }

                //print swap to console
                console.log("Swap Performed:");
                for (var b = 0; b < plaintiffTeams.length; b++) {
                    console.log(plaintiffTeams[b].number + " v. " + defenseTeams[b].number);
                }

                //restart the loop
                a = plaintiffTeams.length;

            }
        }

    }


    //return pairings
    let r4pairings = [];
    for (var a = 0; a < plaintiffTeams.length; a++) {
        let pairing = `{"plaintiff":${plaintiffTeams[a].number},"defense":${defenseTeams[a].number}}`;
        r4pairings.push(JSON.parse(pairing));
    }
    return r4pairings;
}

function pairingsHaveConflicts(plaintiffTeams, defenseTeams) {
    //initialize return variable
    var conflicts = false;

    //iterate through all pairings, seeing if any have a pairing on the impermissible list
    for (var a = 0; a < plaintiffTeams.length; a++) {
        for (var b = 0; b < impermissibles.length; b++) {
            if ((plaintiffTeams[a].number === impermissibles[b].team0 ||
                    plaintiffTeams[a].number === impermissibles[b].team1) &&
                    (defenseTeams[a].number === impermissibles[b].team0 ||
                            defenseTeams[a].number === impermissibles[b].team1)) {
                //If an impermissible exists, set flag variable
                conflicts = true;
            }
        }
    }
    return conflicts;
}

function updateData() {
    return new Promise(function (resolve, reject) {
        Promise.all([getTeams(), getPairings(), getImpermissibles(), getBallots(), getJudges(), getJudgeConflicts()]).then(data => {
            teams = data[0];
            pairings = data[1];
            impermissibles = data[2];
            ballots = data[3];
            judges = data[4];
            judgeConflicts = data[5];
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

function getJudgeConflicts() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/judgeConflicts/getAll.php",
            dataType: "json"
        }).then(judgeConflicts => {
            resolve(judgeConflicts);
        });
    });
}

function getImpermissibles() {
    return new Promise(function (resolve, reject) {
        //get list of team impermissibles
        var impermissibles = new Promise(function (resolveImpermissibles, rejectImpermissibles) {
            $.ajax({
                url: "../api/impermissibles/getAll.php",
                dataType: "json"
            }).then(impermissibles => {
                resolveImpermissibles(impermissibles);
            });
        });
        //get pairing data (to add to impermissibles)
        var pairings = new Promise(function (resolvePairings, rejectPairings) {
            $.ajax({
                url: "../api/pairings/getAll.php",
                dataType: "json"
            }).then(pairings => {
                resolvePairings(pairings);
            });
        });
        //combine pairings with team impermissibles to get all impermissibles
        Promise.all([impermissibles, pairings]).then(data => {
            var impermissibles = data[0];
            var pairings = data[1];
            var numberOfImpermissibles = impermissibles.length;
            for (var a = 0; a < pairings.length; a++) {
                impermissibles.push({"team0": 0, "team1": 0});
                impermissibles[numberOfImpermissibles + a].team0 = pairings[a].plaintiff;
                impermissibles[numberOfImpermissibles + a].team1 = pairings[a].defense;
            }
            resolve(impermissibles);
        });
    });
}

function getBallots() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/ballots/getAll.php",
            dataType: "json"
        }).then(ballots => {
            for (var a = 0; a < ballots.length; a++) {
                ballots[a].plaintiffPD =
                        (ballots[a].pClose +
                                ballots[a].pCx1 + ballots[a].pCx2 + ballots[a].pCx3 +
                                ballots[a].pDx1 + ballots[a].pDx2 + ballots[a].pDx3 +
                                ballots[a].pOpen +
                                ballots[a].pWCx1 + ballots[a].pWCx2 + ballots[a].pWCx3 +
                                ballots[a].pWDx1 + ballots[a].pWDx2 + ballots[a].pWDx3) -
                        (ballots[a].dClose +
                                ballots[a].dCx1 + ballots[a].dCx2 + ballots[a].dCx3 +
                                ballots[a].dDx1 + ballots[a].dDx2 + ballots[a].dDx3 +
                                ballots[a].dOpen +
                                ballots[a].dWCx1 + ballots[a].dWCx2 + ballots[a].dWCx3 +
                                ballots[a].dWDx1 + ballots[a].dWDx2 + ballots[a].dWDx3);
            }
            resolve(ballots);
        });
    });
}

function getJudges() {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: "../api/judges/getAll.php",
            dataType: "json"
        }).then(judges => {
            for (var a = 0; a < judges.length; a++) {
                if (judges[a].round1 === "1") {
                    judges[a].round1 = true;
                } else {
                    judges[a].round1 = false;
                }
                if (judges[a].round2 === "1") {
                    judges[a].round2 = true;
                } else {
                    judges[a].round2 = false;
                }
                if (judges[a].round3 === "1") {
                    judges[a].round3 = true;
                } else {
                    judges[a].round3 = false;
                }
                if (judges[a].round4 === "1") {
                    judges[a].round4 = true;
                } else {
                    judges[a].round4 = false;
                }
            }
            resolve(judges);
        });
    });
}

function rankTeams(teams) {
    let unsortedTeams = teams;
    const sortFunction = function (team0, team1) {
        //first check if wins is higher
        if (team0.wins < team1.wins) {
            return 1;
        } else if (team0.wins > team1.wins) {
            return -1;
        }
        //if not check if CS is higher
        if (team0.cs < team1.cs) {
            return 1;
        } else if (team0.cs > team1.cs) {
            return -1;
        }
        //if not, check if PD is higher
        if (team0.pd < team1.pd) {
            return 1;
        } else if (team0.pd > team1.pd) {
            return -1;
        } else {
            //team number tie breaker
            if ($("#tieBreaker").val() === "true") {
                if (team0.number < team1.number) {
                    return -1;
                } else {
                    return 1;
                }
            } else {
                if (team0.number < team1.number) {
                    return 1;
                } else {
                    return -1;
                }
            }
        }
    };

    let sortedTeams = unsortedTeams.sort(sortFunction);

    return sortedTeams;
}

function rankSwaps(swaps) {
    //create ranking function
    var sortFunction = function (swap0, swap1) {
        //first check rank difference
        if (swap0.rank > swap1.rank) {
            return 1;
        } else if (swap0.rank < swap1.rank) {
            return -1;
        }
        //then check difference of wins
        if (swap0.wins > swap1.wins) {
            return 1;
        } else if (swap0.wins < swap1.wins) {
            return -1;
        }
        //then check difference of cs
        if (swap0.cs > swap1.cs) {
            return 1;
        } else if (swap0.cs < swap1.cd) {
            return -1;
        }
        //then check difference of PD
        if (swap0.pd > swap1.pd) {
            return 1;
        } else if (swap0.pd < swap1.pd) {
            return -1;
        }
        //then check sum of ranks
        if (swap0.rankSum > swap1.RankSum) {
            return 1;
        } else if (swap0.rankSum < swap1.rankSum) {
            return -1;
        } else {//otherwise return the second team (need to take steps to ensure this is defense)
            return -1;
        }
    };
    return swaps.sort(sortFunction);
}

function validateJudges(round) {
    //get arrays of relevant <select> elements
    let judgeSelects = $(`.judgeSelect[data-round='${round}']`);
    let pSelects = $(`.pSelect[data-round='${round}']`);
    let dSelects = $(`.dSelect[data-round='${round}']`);

    //check that no judge is assigned to more than one round
    var duplicateJudges = false;
    let judgeIdArray = [];
    for (let a = 0; a < judgeSelects.length; a++) {
        if (parseInt(judgeSelects[a].value) !== 0) {
            judgeIdArray[judgeSelects[a].value] = 0;
        }
    }
    for (let a = 0; a < judgeSelects.length; a++) {
        if (parseInt(judgeSelects[a].value) !== 0) {
            judgeIdArray[judgeSelects[a].value]++;
        }
    }
    Object.keys(judgeIdArray).forEach(function (judgeId) {
        if (judgeIdArray[judgeId] !== 1) {
            for (let a = 0; a < judgeSelects.length; a++) {
                if (judgeId === judgeSelects[a].value) {
                    $(judgeSelects[a]).css("color", "red");
                }
            }
            duplicateJudges = true;
        }
    });





    //check that judges do not have school conflicts
    var schoolConflicts = false;
    for (let a = 0; a < judgeSelects.length; a++) {
        if (parseInt(judgeSelects[a].value) !== 0) {
            for (let b = 0; b < judgeConflicts.length; b++) {
                if (judgeConflicts[b].judge === parseInt(judgeSelects[a].value)) {
                    for (let c = 0; c < pSelects.length; c++) {
                        if (judgeConflicts[b].team === parseInt(pSelects[c].value) &&
                                $(pSelects[c]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                            schoolConflicts = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                        if (judgeConflicts[b].team === parseInt(dSelects[c].value) &&
                                $(dSelects[c]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                            schoolConflicts = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                    }
                }
            }
        }
    }

    //check that judge does not have previous round conflict
    var pastRoundConflicts = false;
    for (let a = 0; a < judgeSelects.length; a++) {
        for (let b = 0; b < ballots.length; b++) {
            if (parseInt(judgeSelects[a].value) === ballots[b].judge) {
                for (let c = 0; c < pairings.length; c++) {
                    if (ballots[b].pairing === pairings[c].id) {
                        for (let d = 0; d < pSelects.length; d++) {
                            if (parseInt(pSelects[d].value) === pairings[c].plaintiff &&
                                    $(pSelects[d]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                                pastRoundConflicts = true;
                                $(judgeSelects[a]).css("color", "red");
                                console.log($(judgeSelects[a]));
                            } else if (parseInt(pSelects[d].value) === pairings[c].defense &&
                                    $(pSelects[d]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                                pastRoundConflicts = true;
                                $(judgeSelects[a]).css("color", "red");
                            }
                            if (parseInt(dSelects[d].value) === pairings[c].plaintiff &&
                                    $(dSelects[d]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                                pastRoundConflicts = true;
                                $(judgeSelects[a]).css("color", "red");
                            } else if (parseInt(dSelects[d].value) === pairings[c].defense &&
                                    $(dSelects[d]).attr("data-pairing") === $(judgeSelects[a]).attr("data-pairing")) {
                                pastRoundConflicts = true;
                                $(judgeSelects[a]).css("color", "red");
                            }
                        }
                    }
                }
            }
        }
    }

    //check that judge can actually judge this round
    var judgesUnavailable = false;
    for (let a = 0; a < judgeSelects.length; a++) {
        for (let b = 0; b < judges.length; b++) {
            if (parseInt(judgeSelects[a].value) === judges[b].id) {
                switch (round) {
                    case 1:
                        if (!judges[b].round1) {
                            judgesUnavailable = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                        break;
                    case 2:
                        if (!judges[b].round2) {
                            judgesUnavailable = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                        break;
                    case 3:
                        if (!judges[b].round3) {
                            judgesUnavailable = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                        break;
                    case 4:
                        if (!judges[b].round4) {
                            judgesUnavailable = true;
                            $(judgeSelects[a]).css("color", "red");
                        }
                        break;
                }
            }
        }
    }
//TODO make the one causing the error turn red

    return new Promise(function (resolve, reject) {
        if (!duplicateJudges && !schoolConflicts && !pastRoundConflicts && !judgesUnavailable) {
            resolve(true);
        } else {
            var modalText = "The following errors were found in judge assignments:<br>";
            if (duplicateJudges) {
                modalText += "• Same judge assigned to multiple rounds<br>";
            }
            if (schoolConflicts) {
                modalText += "• Judge has conflict with assigned school<br>";
            }
            if (pastRoundConflicts) {
                modalText += "• Judge has previously seen same team at this tornament<br>";
            }
            if (judgesUnavailable) {
                modalText += "• Judge's listed availability indicates it cannot judge this round<br>";
            }
            modalText += "Close this dialog and modify the assignments, or proceed with assignments anyway?";
            $("#invalidJudgesModalText").html(modalText);
            $("#invalidJudgesModal").modal("toggle");
            $("#reassignJudges").click(function () {
                $("#reassignJudges").unbind();
                resolve(false);
            });
            $("#assignAnyway").click(function () {
                $("#assignAnyway").unbind();
                resolve(true);
            });
        }

    });

}

function generateBallots(round) {
    var ballots = [];
    let judgeSelects = $(".judgeSelect");
    let pSelects = $(".pSelect");
    let dSelects = $(".dSelect");
    for (var a = 0; a < judgeSelects.length; a++) {//iterate through all judge selects
        if (parseInt(judgeSelects[a].attributes["data-round"].value) === round) {//check that we're in correct round
            for (var b = 0; b < pSelects.length; b++) {//then iterate through all plaintiff selects
                if (parseInt(pSelects[b].attributes["data-round"].value) === round && //check that the plaintiff is in round one
                        parseInt(pSelects[b].attributes["data-pairing"].value) === parseInt(judgeSelects[a].attributes["data-pairing"].value)) {//and that the rows match
                    for (var c = 0; c < dSelects.length; c++) {//finally, iterate through defense selects
                        if (parseInt(dSelects[c].attributes["data-round"].value) === round && //check that the defense is in round one
                                parseInt(dSelects[c].attributes["data-pairing"].value) === parseInt(judgeSelects[a].attributes["data-pairing"].value)) {//and that the rows match
                            let ballot = {
                                "plaintiff": parseInt(pSelects[b].value),
                                "defense": parseInt(dSelects[c].value),
                                "judge": parseInt(judgeSelects[a].value)};
                            ballots.push(ballot);
                        }
                    }
                }
            }
        }
    }
    return ballots;
}

function pairingsWarningModal(warning) {
    $("#pairingsWarningModalText").text(warning);
    $("#pairingsWarningModal").modal();
}

function ballotsWarningModal(warning) {
    $("#ballotsWarningModalText").text(warning);
    $("#ballotsWarningModal").modal();
}

function sortByPairing(objA, objB) {
    return objA.pairing - objB.pairing;
}

//cc-by-sa by CoolAJ86 https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
function shuffle(array) {
    var clone = array.slice(0);
    var currentIndex = clone.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = clone[currentIndex];
        clone[currentIndex] = clone[randomIndex];
        clone[randomIndex] = temporaryValue;
    }

    return clone;
}