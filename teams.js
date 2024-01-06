/* 
 * Copyright (C) 2023 allen
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
	let bodyHTML = "<div id='teamTable'></div>";
	bodyHTML += "<div><input id='newTeamNumber' size='4' placeholder='####'><input id='newTeamName' placeholder='Name'><button id='createTeam'>Create Team</button></div>"
	$("#body").html(bodyHTML);
	fillTeamTable();

	$("#createTeam").click(() => {
		let newTeamNumber = $("#newTeamNumber").val();
		let newTeamName = $("#newTeamName").val();
		if (newTeamName != "" && isInt(newTeamNumber)) {
			let teamData = { number: newTeamNumber, name: newTeamName }
			$.post(
				"api/teams/create.php",
				teamData,
				(result) => {
					if (result.message == -1) {
						handleSessionExpiration();
					} else {
						fillBody();
					}
				},
				"json");
		}
	});
}

function fillTeamTable() {
	generateTeamTable().then(function (tableHTML) {
		$("#teamTable").html(tableHTML);
		fillConflicts();
		attachListeners();
	});
}

function attachListeners() {
	$(".teamNumber").on("input", function () {
		let id = $(this).attr("teamid");
		let number = $(this).val();
		updateTeamNumber(id, number);
	});
	$(".teamName").on("input", function () {
		let id = $(this).attr("teamid");
		let name = $(this).val();
		updateTeamName(id, name);
	});
	$(".isByeTeam").on("change", function () {
		let id = $(this).attr("teamid");
		let isByeTeam = 0;
		if ($(this).prop("checked")) {
			isByeTeam = 1;
		}
		updateIsByeTeam(id, isByeTeam);
	});
	$(".conflict").on("change", function () {
		let team0 = $(this).attr("teamid");
		let team1 = $(this).attr("conflictTeamId");
		if ($(this).prop("checked")) {
			createConflict(team0, team1);
		} else {
			deleteConflict(team0, team1);
		}
	});

	/*
	$(".deleteButton").click(function () {
		let id = $(this).attr("teamid");
		deleteTeam(id);
	});*/
}

function fillConflicts() {
	getConflicts().then((conflicts) => {
		let conflictChecks = $(".conflict");
		for (let a = 0; a < conflictChecks.length; a++) {
			$(conflictChecks[a]).prop("checked", false);
		}

		for (let a = 0; a < conflictChecks.length; a++) {
			let conflictCheck = $(conflictChecks[a]);
			let checkTeam0 = conflictCheck.attr("teamid");
			let checkTeam1 = conflictCheck.attr("conflictteamid");
			for (let b = 0; b < conflicts.length; b++) {
				let conflictTeam0 = conflicts[b].team0;
				let conflictTeam1 = conflicts[b].team1;
				if ((checkTeam0 == conflictTeam0 && checkTeam1 == conflictTeam1) || (checkTeam0 == conflictTeam1 && checkTeam1 == conflictTeam0)) {
					conflictCheck.prop("checked", true);
				}
			}
		}
	});
}

function generateTeamTable() {
	return new Promise((resolve, reject) => {
		let tableHTML = "<div style='display: grid;'>";
		getTeams().then((teams) => {
			//fill header with conflict text and first row
			let headerHTML = `<div style='grid-column: 4 / span 1; grid-row: 1 / span 1;'>Conflicts</div><div style='grid-column: 1 / span 1; grid-row: 2 / span 1;'>Team</div>`;

			headerHTML += `<div style='grid-column: 2 / span 1; grid-row: 2 / span 1; writing-mode: vertical-lr;'>Bye-Team?</div>`;
			headerHTML += `<div style='grid-column: 3 / span 1; grid-row: 2 / span 1; writing-mode: vertical-lr;'>URL</div>`;

			//loop through all teams and add them to the header
			for (let a = 0; a < teams.length; a++) {
				headerHTML += `<div style='grid-column: ${a + 4} / span 1; grid-row: 2 / span 1; writing-mode: vertical-lr;'>${teams[a].number + " " + teams[a].name}</div>`;
			}

			//loop through teams and create row for each team
			let rowsHTML = [];
			for (let a = 0; a < teams.length; a++) {
				let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${a + 3} / span 1;'><input class='teamNumber' teamId='${teams[a].id}' value='${teams[a].number}' size='4'><input class='teamName' teamId='${teams[a].id}' value='${teams[a].name}'><button class='deleteButton' teamId='${teams[a].id}' disabled>Delete</button></div>`;
				rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${a + 3} / span 1;'><input type="checkbox" class='isByeTeam' teamId='${teams[a].id}' ${teams[a].isByeTeam ? 'checked' : ''}></div>`;
				rowHTML += `<div style='grid-column: 3 / span 1; grid-row: ${a + 3} / span 1;'><a href='team.php?t=${teams[a].url}'>Link</a></div>`;

				for (let b = 0; b < teams.length; b++) {
					rowHTML += `<div style='grid-column: ${b + 4} / span 1; grid-row: ${a + 3} / span 1;'><input type="checkbox" class='conflict' teamId='${teams[a].id}' conflictTeamId='${teams[b].id}'></div>`
				}
				rowsHTML.push(rowHTML);
			}

			tableHTML += headerHTML;
			for (let a = 0; a < rowsHTML.length; a++) {
				tableHTML += rowsHTML[a];
			}
			tableHTML += "</div>";

			resolve(tableHTML);
		});
	});
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

function getConflicts() {
	return new Promise((resolve, reject) => {
		$.get(
			"api/teamConflicts/getAll.php",
			(conflicts) => {
				resolve(conflicts);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function updateTeamNumber(id, number) {
	let updateData = { id: id, number: number };
	$.post(
		"api/teams/updateNumber.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function updateTeamName(id, name) {
	let updateData = { id: id, name: name };
	$.post(
		"api/teams/updateName.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function updateIsByeTeam(id, isByeTeam) {
	let updateData = { id: id, isByeTeam: isByeTeam };
	$.post(
		"api/teams/updateIsByeTeam.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function createConflict(team0, team1) {
	let conflictData = { team0: team0, team1: team1 }
	$.post(
		"api/teamConflicts/create.php",
		conflictData,
		function (response) {
			fillConflicts();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function deleteConflict(team0, team1) {
	let conflictData = { team0: team0, team1: team1 }
	$.post(
		"api/teamConflicts/delete.php",
		conflictData,
		function (response) {
			fillConflicts();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
}

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};


//cc-by-sa by Davide Pizzolato https://stackoverflow.com/questions/14636536/how-to-check-if-a-variable-is-an-integer-in-javascript
function isInt(value) {
	return !isNaN(value) &&
		parseInt(Number(value)) == value &&
		!isNaN(parseInt(value, 10));
}
