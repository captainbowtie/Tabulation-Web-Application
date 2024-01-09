/* 
 * Copyright (C) 2024 allen
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

$(document).ready(() => {
	fillHeader();
	fillBody();
});

function fillHeader() {
	$.get("header.php", (headerHTML) => {
		$("#header").html(headerHTML);
	});
}

function fillBody() {
	//create tabs
	let bodyHTML = "<div id='ballots'><ul><li><a href='#round1'>Round 1</a></li><li><a href='#round2'>Round 2</a></li><li><a href='#round3'>Round 3</a></li><li><a href='#round4'>Round 4</a></li></ul><div id='round1'></div><div id='round2'></div><div id='round3'></div><div id='round4'></div></div>"
	$("#body").html(bodyHTML);
	$("#assignments").tabs();
	Promise.all([getPairings(), getJudgeAssignments(), getTeams(), getJudges()]).then(values => {
		let pairings = values[0];
		let judgeAssignments = values[1];
		let teams = values[2]
		let judges = values[3];

		//add headers and buttons common to all tables
		for (let round = 1; round <= 4; round++) {
			let roundHTML = `<div id='round${round}Grid' style='display: grid;'>`

			let headerHTML = "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Room</div>";
			headerHTML += "<div style='grid-column: 2 / span 1; grid-row: 1 / span 1;'>π</div>";
			headerHTML += "<div style='grid-column: 3 / span 1; grid-row: 1 / span 1;'>∆</div>";
			headerHTML += "<div style='grid-column: 4 / span 1; grid-row: 1 / span 1;'>Ballot URL</div>";

			roundHTML += headerHTML;
			roundHTML += "</div>"

			roundHTML += `<div><button class='addColumn' round='${round}'>Add Judge Column</button><button class='removeColumn' round='${round}'>Remove Judge Column</button><button class='generate' round='${round}'>Generate Assignments</button><button class='save' round='${round}' state='save'>Save Assignments</button></div>`
			$(`#round${round}`).html(roundHTML);
		}

		//sort pairings by round into separate arrays
		let roundPairings = [[], [], [], [], []];
		for (let a = 0; a < pairings.length; a++) {
			roundPairings[pairings[a].round].push(pairings[a]);
		}

		//add pairings to tables
		for (let round = 1; round <= 4; round++) {
			roundPairings[round].forEach(function (pairing, index) {
				let pTeamNumber = getTeamNumberById(teams, pairing.plaintiff);
				let dTeamNumber = getTeamNumberById(teams, pairing.defense);

				let rowHTML = `<div class='room' style='grid-column: 1 / span 1; grid-row: ${index + 2} / span 1;' pairing='${pairing.id}'>${pairing.room}</div>`;
				rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${index + 2} / span 1;'>${pTeamNumber}</div>`;
				rowHTML += `<div style='grid-column: 3 / span 1; grid-row: ${index + 2} / span 1;'>${dTeamNumber}</div>`;
				rowHTML += `<div style='grid-column: 4 / span 1; grid-row: ${index + 2} / span 1;'><a href='b.php?p=${pairing.url}'>Link</a></div>`;
				$(`#round${round}Grid`).append(rowHTML);
			});
		}

		//sort assignments by round into separate arrays
		let roundAssignments = [[], [], [], [], []];
		judgeAssignments.forEach((assignment) => {
			for (let round = 1; round <= 4; round++) {
				roundPairings[round].forEach((pairing) => {
					if (pairing.id == assignment.pairing) {
						roundAssignments[round].push(assignment);
					}
				});
			}
		});

		for (let round = 1; round <= 4; round++) {
			let rooms = $(`#round${round}Grid`).children(".room");
			let pairingIds = [];
			for (let a = 0; a < rooms.length; a++) {
				pairingIds.push($(rooms[a]).attr("pairing"));
			}
			let optionsHTML = generateSelectOptions();

			if (roundAssignments[round].length > 0) {//if assignments exist, add them
				//determine max judges per round
				let judgeCounts = [];
				roundAssignments[round].forEach((assignment) => {
					judgeCounts[assignment.pairing] = 0;
				});
				roundAssignments[round].forEach((assignment) => {
					judgeCounts[assignment.pairing]++;
				});
				let maxJudges = 0;
				for (let a = 0; a < judgeCounts.length; a++) {
					if (judgeCounts[a] > maxJudges) {
						maxJudges = judgeCounts[a];
					}
				}

				for (let row = 0; row < pairingIds.length; row++) {
					for (let judge = 0; judge < maxJudges; judge++) {
						let selectHTML = `<div style='grid-column: ${judge + 5} / span 1; grid-row: ${row + 2} / span 1;'><select class='judge' pairing='${pairingIds[row]}' judge='${judge}'  round='${round}' disabled='true'>${optionsHTML}</select></div>`;
						$(`#round${round}Grid`).append(selectHTML);
					}
				}
				fillSelects();
				//disable the buttons for the round
				$(`.addColumn[round='${round}']`).attr("disabled", true);
				$(`.removeColumn[round='${round}']`).attr("disabled", true);
				$(`.generate[round='${round}']`).attr("disabled", true);
				$(`.save[round='${round}']`).html("Unlock Assignments");
				$(`.save[round='${round}']`).attr("state", "unlock");

			} else {//otherwise add the default of two empty judge slots
				for (let a = 0; a < pairingIds.length; a++) {
					let selectHTML = `<div style='grid-column: 5 / span 1; grid-row: ${a + 2} / span 1;'><select class='judge' pairing='${pairingIds[a]}' judge='0'  round='${round}'>${optionsHTML}</select></div>`;
					selectHTML += `<div style='grid-column: 6 / span 1; grid-row: ${a + 2} / span 1;'><select class='judge' pairing='${pairingIds[a]}' judge='1'  round='${round}'>${optionsHTML}</select></div>`;
					$(`#round${round}Grid`).append(selectHTML);
				}
			}
		}

		attachListeners();

		function generateSelectOptions() {
			let optionHTML = "<option value='0'>---</option>";
			judges.forEach((judge) => {
				optionHTML += `<option value='${judge.id}'>${judge.name}</option>`;
			});
			return optionHTML;
		};

		function fillSelects() {
			let selects = $(".judge");
			let assignments = judgeAssignments;
			for (let selectIndex = 0; selectIndex < selects.length; selectIndex++) {

				for (let assignmentIndex = 0; assignmentIndex < assignments.length; assignmentIndex++) {
					if ($(selects[selectIndex]).attr("pairing") == assignments[assignmentIndex].pairing) {
						$(selects[selectIndex]).val(assignments[assignmentIndex].judge);
						assignments.splice(assignmentIndex, 1);
						assignmentIndex = assignments.length;
					}
				}
			}
		};

		function attachListeners() {
			$(".addColumn").click(function () {
				let round = $(this).attr("round");

				//determine current number of judges per round
				let existingSelects = $(`.judge[round='${round}']`);
				let existingMaxJudgeIndex = -1;
				for (let a = 0; a < existingSelects.length; a++) {
					let selectJudgeIndex = $(existingSelects[a]).attr("judge");
					if (selectJudgeIndex > existingMaxJudgeIndex) {
						existingMaxJudgeIndex = parseInt(selectJudgeIndex);
					}
				}

				//add new selects
				let rooms = $(`#round${round}Grid`).children(".room");
				for (let a = 0; a < rooms.length; a++) {
					let selectHTML = `<div style='grid-column: ${existingMaxJudgeIndex + 6} / span 1; grid-row: ${a + 2} / span 1;'><select class='judge' pairing='${$(rooms[a]).attr("pairing")}' judge='${existingMaxJudgeIndex + 1}'  round='${round}'>${generateSelectOptions()}</select></div>`;
					$(`#round${round}Grid`).append(selectHTML);
				}

				//attach listeners to the new selects
				$(`.judge[round='${round}']`).on("change", screenAssignments);
			});
			$(".removeColumn").click(function () {
				let round = $(this).attr("round");
				//determine current number of judges per round
				let existingSelects = $(`.judge[round='${round}']`);
				let existingMaxJudgeIndex = -1;
				for (let a = 0; a < existingSelects.length; a++) {
					let selectJudgeIndex = $(existingSelects[a]).attr("judge");
					if (selectJudgeIndex > existingMaxJudgeIndex) {
						existingMaxJudgeIndex = parseInt(selectJudgeIndex);
					}
				}

				$(`.judge[round='${round}'][judge='${existingMaxJudgeIndex}']`).parent().remove();
			});
			$(".generate").click(function () {
				let round = $(this).attr("round");
				getJudges().then((judges) => {
					let roundJudges = filterJudges(judges);
					//get selects
					$(`.judge[round=${round}]`).val(0);
					let selects = $(`.judge[round=${round}]`);
					//shuffle the selects
					let shuffledSelects = shuffle(selects);
					//shuffle the judges
					let shuffledJudges = shuffle(roundJudges);
					//fill selects until they're all full or we run out of judges
					for (let a = 0; a < shuffledSelects.length; a++) {
						if (a < shuffledJudges.length) {
							$(shuffledSelects[a]).val(shuffledJudges[a].id);
						}
					}
					screenAssignments();
				});

				function filterJudges(judges) {
					let filteredJudges = [];
					judges.forEach((judge) => {
						if (judge[`round${round}`] == true || judge["checkedIn"] == true) {
							filteredJudges.push(judge);
						}
					});
					return filteredJudges;
				}
			});
			$(".save").click(function () {
				let round = $(this).attr("round");
				if ($(this).attr("state") == "save") {
					let roundSelects = $(`.judge[round='${round}']`);

					let assignments = [];
					for (let a = 0; a < roundSelects.length; a++) {
						let pairing = $(roundSelects[a]).attr("pairing");
						let judge = $(roundSelects[a]).val();
						if (judge != 0) {
							let assignment = {
								pairing: pairing,
								judge: judge
							};
							assignments.push(assignment);
						}
					}

					createAssignments(assignments);
				} else {
					$(`*[round='${round}']`).attr("disabled", false);
					$(`.save[round='${round}']`).html("Save Assignments");
					$(`.save[round='${round}']`).attr("state", "save");
				}
			});
		};
	})
}

function screenAssignments() {

}

function createAssignments(assignments) {
	let assignmentData = { assignments: assignments };
	$.post("api/judgeAssignments/create.php",
		assignmentData,
		() => {

		}, "json");
}

function getPairings() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/pairings/getAll.php",
			(pairings) => {
				resolve(pairings);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function getJudgeAssignments() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/judgeAssignments/getAll.php",
			(judgeAssignments) => {
				resolve(judgeAssignments);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function getTeams() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/teams/getAll.php",
			(teams) => {
				resolve(teams);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function getJudges() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/judges/getAll.php",
			(judges) => {
				resolve(judges);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};

function getTeamNumberById(teams, id) {
	let teamNumber = 0;
	for (let a = 0; a < teams.length; a++) {
		if (teams[a].id == id) {
			teamNumber = teams[a].number;
		}
	}
	return teamNumber;
}

function shuffle(array) {
	//CC BY-SA 4.0 https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
	let currentIndex = array.length, randomIndex;

	// While there remain elements to shuffle.
	while (currentIndex != 0) {

		// Pick a remaining element.
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex--;

		// And swap it with the current element.
		[array[currentIndex], array[randomIndex]] = [
			array[randomIndex], array[currentIndex]];
	}

	return array;
}