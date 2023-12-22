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
		console.log(newTeamNumber + " " + newTeamName);
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
		//fill conflicts
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

function generateTeamTable() {
	return new Promise((resolve, reject) => {
		let tableHTML = "<div style='display: grid;'>";
		getTeams().then((teams) => {
			//fill header with conflict text and first row
			let headerHTML = `<div style='grid-column: 2 / -1; grid-row: 1 / span 1;'>Conflicts</div><div style='grid-column: 1 / span 1; grid-row: 2 / span 1;'>Team</div>`;
			let rowsHTML = [];

			headerHTML += `<div style='grid-column: 2 / span 1; grid-row: 2 / span 1; writing-mode: vertical-lr'>Bye-Team?</div>`;


			//loop through all teams and add them to the header
			for (let a = 0; a < teams.length; a++) {
				headerHTML += `<div style='grid-column: ${a + 3} / span 1; grid-row: 2 / span 1; writing-mode: vertical-lr'>${teams[a].number + " " + teams[a].name}</div>`;
			}

			//loop through teams and create row for each team
			for (let a = 0; a < teams.length; a++) {
				let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${a + 3} / span 1;'><input class='teamNumber' teamId='${teams[a].id}' value='${teams[a].number}' size='4'><input class='teamName' teamId='${teams[a].id}' value='${teams[a].name}'><button class='deleteButton' teamId='${teams[a].id}'>Delete</button></div>`;
				rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${a + 3} / span 1;'><input type="checkbox" class='isByeTeam' teamId='${teams[a].id}' ${teams[a].isByeTeam ? 'checked' : ''}></div>`;

				for (let b = 0; b < teams.length; b++) {
					rowHTML += `<div style='grid-column: ${b + 3} / span 1; grid-row: ${a + 3} / span 1;'><input type="checkbox" class='conflict' teamId='${teams[a].id}' conflictTeamId='${teams[b].id}'></div>`
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
				if (teams.message == -1) {
					reject(handleSessionExpiration());
				} else {
					resolve(teams);
				}
			},
			"json");
	});
}

function updateTeamNumber(id, number) {
	let updateData = { id: id, number: number };
	$.post(
		"api/teams/updateNumber.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}

function updateTeamName(id, name) {
	let updateData = { id: id, name: name };
	$.post(
		"api/teams/updateName.php",
		updateData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
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
		"json");
}

function createConflict(team0, team1) {
	let conflictData = { team0: team0, team1: team1 }
	$.post(
		"api/teamConflicts/create.php",
		conflictData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}

function deleteConflict(team0, team1) {
	let conflictData = { team0: team0, team1: team1 }
	$.post(
		"api/teamConflicts/delete.php",
		conflictData,
		function (response) {
			if (response.message == -1) {
				handleSessionExpiration();
			}
		},
		"json");
}

//cc-by-sa by Davide Pizzolato https://stackoverflow.com/questions/14636536/how-to-check-if-a-variable-is-an-integer-in-javascript
function isInt(value) {
	return !isNaN(value) &&
		parseInt(Number(value)) == value &&
		!isNaN(parseInt(value, 10));
}
