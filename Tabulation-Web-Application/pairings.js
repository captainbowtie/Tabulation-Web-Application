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
let round1PairingsExist = false;
let round2PairingsExist = false;
let round3PairingsExist = false;
let round4PairingsExist = false;

$(document).ready(function () {
    //Pull all data from server
    updateData().then(data => {
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
});

$("#pair1").on("click", function (e) {
    e.preventDefault();
    const pairings = pair1();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round1p${a}`).val(pairings[a].plaintiff);
        $(`#round1d${a}`).val(pairings[a].defense);
    }
});

$("#pair2").on("click", function (e) {
    e.preventDefault();
    const pairings = pair2();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round2p${a}`).val(pairings[a].plaintiff);
        $(`#round2d${a}`).val(pairings[a].defense);
    }
});

$("#pair3").on("click", function (e) {
    e.preventDefault();
    const pairings = pair3();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round3p${a}`).val(pairings[a].plaintiff);
        $(`#round3d${a}`).val(pairings[a].defense);
    }
});

$("#pair4").on("click", function (e) {
    e.preventDefault();
    const pairings = pair4();
    for (let a = 0; a < pairings.length; a++) {
        $(`#round4p${a}`).val(pairings[a].plaintiff);
        $(`#round4d${a}`).val(pairings[a].defense);
    }
});

$("#submit1").on("click", function (e) {
    e.preventDefault();
    if (round1PairingsExist) {
        warningModal("Round 1 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(1);
    }
});

$("#submit2").on("click", function (e) {
    e.preventDefault();
    if (round2PairingsExist) {
        warningModal("Round 2 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(2);
    }
});

$("#submit3").on("click", function (e) {
    e.preventDefault();
    if (round3PairingsExist) {
        warningModal("Round 3 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(3);
    }
});

$("#submit4").on("click", function (e) {
    e.preventDefault();
    if (round4PairingsExist) {
        warningModal("Round 4 pairings already exist. Saving these pairings will overwrite the existing pairings.");
    } else {
        submitPairings(4);
    }
});

$("#modalSave").on("click", function (e) {
    e.preventDefault();
    const roundToSubmit = $(".active").attr("id").substring(5);
    submitPairings(roundToSubmit);
});

function submitPairings(round) {
    //create pairings json object
    let data = `{"round":${round},"pairings":[`;
    for (let a = 0; a < teams.length / 2; a++) {
        let plaintiff = $(`#round${round}p${a}`).val();
        let defense = $(`#round${round}d${a}`).val();
        let pairing = `{"plaintiff":${plaintiff},"defense":${defense}}`;
        data += pairing;
        if(a!==(teams.length/2-1)){
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
                        if (!swapList.includes([Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number), Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number)])) {
                            var pSwap = JSON.parse('{' +
                                    '"number":' + plaintiffTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(plaintiffTeams[a].wins - plaintiffTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }
                        //check that proposed swap has not already occurred for defense teams
                        if (!swapList.includes([Math.min(defenseTeams[a].number, defenseTeams[b].number), Math.max(defenseTeams[a].number, defenseTeams[b].number)])) {
                            var dSwap = JSON.parse('{' +
                                    '"number":' + defenseTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(defenseTeams[a].wins - defenseTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
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
                            swapList.push([Math.min(plaintiffTeams[a], plaintiffTeams[b]), Math.max(plaintiffTeams[a], plaintiffTeams[b])]);
                        }
                    }
                } else {
                    for (var b = 0; b < defenseTeams.length; b++) {
                        if (defenseTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = defenseTeams[b];
                            defenseTeams[b] = defenseTeams[a];
                            defenseTeams[a] = swapTeam;
                            swapList.push([Math.min(defenseTeams[a], defenseTeams[b]), Math.max(defenseTeams[a], defenseTeams[b])]);
                        }
                    }
                }
                a = plaintiffTeams.length;

            }
        }

    }
    //return pairings
    let pairings = [];
    for (var a = 0; a < plaintiffTeams.length; a++) {
        let pairing = `{"plaintiff":${plaintiffTeams[a].number},"defense":${defenseTeams[a].number}}`;
        pairings.push(JSON.parse(pairing));
    }
    return pairings;
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

    //rank teams
    teams = rankTeams(teams);

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
                    if (a !== b && b !== (a + 1)) {
                        //check that proposed pSwap has not already occurred
                        if (!swapList.includes([Math.min(teams[a].number, teams[b].number), Math.max(teams[a].number, teams[b].number)])) {
                            //create pSwap and add to list
                            var pSwap = JSON.parse('{' +
                                    '"number":' + teams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(teams[a].wins - teams[b].wins) + ',' +
                                    '"cs":' + Math.abs(teams[a].cs - teams[b].cs) + ',' +
                                    '"pd":' + Math.abs(teams[a].cs - teams[b].cs) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }
                        //check that proposed dSwap has not already occurred
                        if (!swapList.includes([Math.min(teams[a + 1].number, teams[b].number), Math.max(teams[a + 1].number, teams[b].number)])) {
                            //create dSwap and add to list
                            var dSwap = JSON.parse('{' +
                                    '"number":' + teams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(teams[a].wins - teams[b].wins) + ',' +
                                    '"cs":' + Math.abs(teams[a].cs - teams[b].cs) + ',' +
                                    '"pd":' + Math.abs(teams[a].cs - teams[b].cs) + ',' +
                                    '"rankSum":' + (a + b) +
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
                        }
                    }
                } else {//perform the dSwap
                    for (var b = 0; b < teams.length; b++) {
                        if (teams[b].number === finalSwaps[0].number) {
                            var swapTeam = teams[b];
                            teams[b] = teams[a + 1];
                            teams[a + 1] = swapTeam;
                            swapList.push([Math.min(teams[a + 1].number, teams[b].number), Math.max(teams[a + 1].number, teams[b].number)]);
                        }
                    }
                }
                a = teams.length;
            }
        }
    }


    //return pairings, flipping the side assignment of half of them at random
    const snakeFlag = Math.random();
    let pairings = [];
    if (snakeFlag < .5) {
        for (var a = 0; a < teams.length; a += 2) {
            if (a % 4 === 0) {
                let pairing = `{"plaintiff":${teams[a].number},"defense":${teams[a + 1].number}`;
            } else {
                let pairing = `{"plaintiff":${teams[a + 1].number},"defense":${teams[a].number}`;
            }
            pairings.push(JSON.parse(pairing));
        }
    } else {
        for (var a = 0; a < teams.length; a += 2) {
            if (a % 4 === 0) {
                let pairing = `{"plaintiff":${teams[a + 1].number},"defense":${teams[a].number}`;
            } else {
                let pairing = `{"plaintiff":${teams[a].number},"defense":${teams[a + 1].number}`;
            }
            pairings.push(JSON.parse(pairing));
        }
    }
    return pairings;
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
                        if (!swapList.includes([Math.min(plaintiffTeams[a].number, plaintiffTeams[b].number), Math.max(plaintiffTeams[a].number, plaintiffTeams[b].number)])) {
                            var pSwap = JSON.parse('{' +
                                    '"number":' + plaintiffTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(plaintiffTeams[a].wins - plaintiffTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(plaintiffTeams[a].cs - plaintiffTeams[b].cs) + ',' +
                                    '"rankSum":' + (a + b) +
                                    '}');
                            pSwaps.push(pSwap);
                        }
                        //check that proposed swap has not already occurred for defense teams
                        if (!swapList.includes([Math.min(defenseTeams[a].number, defenseTeams[b].number), Math.max(defenseTeams[a].number, defenseTeams[b].number)])) {
                            var dSwap = JSON.parse('{' +
                                    '"number":' + defenseTeams[b].number + ',' +
                                    '"rank":' + Math.abs(a - b) + ',' +
                                    '"wins":' + Math.abs(defenseTeams[a].wins - defenseTeams[b].wins) + ',' +
                                    '"cs":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
                                    '"pd":' + Math.abs(defenseTeams[a].cs - defenseTeams[b].cs) + ',' +
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
                            swapList.push([Math.min(plaintiffTeams[a], plaintiffTeams[b]), Math.max(plaintiffTeams[a], plaintiffTeams[b])]);
                        }
                    }
                } else {
                    for (var b = 0; b < defenseTeams.length; b++) {
                        if (defenseTeams[b].number === finalSwaps[0].number) {
                            var swapTeam = defenseTeams[b];
                            defenseTeams[b] = defenseTeams[a];
                            defenseTeams[a] = swapTeam;
                            swapList.push([Math.min(defenseTeams[a], defenseTeams[b]), Math.max(defenseTeams[a], defenseTeams[b])]);
                        }
                    }
                }
                a = plaintiffTeams.length;

            }
        }

    }


    //return pairings
    let pairings = [];
    for (var a = 0; a < plaintiffTeams.length; a++) {
        let pairing = `{"plaintiff":${plaintiffTeams[a].number},"defense":${defenseTeams[a].number}}`;
        pairings.push(JSON.parse(pairing));
    }
    return pairings;
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
            url: "../api/ballots/getAllPDs.php",
            dataType: "json"
        }).then(ballots => {
            resolve(ballots);
        });
    });
}

function rankTeams(teams) {
    var sortFunction = function (team0, team1) {
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
            //Randomly break tie; eventually this should be changed to team number tie breaker
            var coinflip = Math.random();
            if (coinflip < .5) {
                return 1;
            } else {
                return -1;
            }
        }
    };

    teams = teams.sort(sortFunction);

    return teams;
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

function warningModal(warning) {
    $("#warningModalText").text(warning);
    $("#warningModal").modal();
}

//cc-by-sa by CoolAJ86 https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}