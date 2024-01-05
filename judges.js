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
	let bodyHTML = "<div id='judgeTable'></div>";
	bodyHTML += "<div><input id='judgeName' placeholder='name'><select id='judgeCategory'><option value='1'>1: sitting judges, trial attorneys, litigators and attorneys with indicia of mock trial experience</option><option value='2'>2: non-coach attorneys who do not fall within category one</option><option value='3'>3: coaches, law students, other non-attorneys</option></select><button id='addJudge'>Add</button></div>";
	$("#body").html(bodyHTML);
	fillJudgeTable();

	$("#addJudge").click(() => {
		let name = $("#judgeName").val();
		let category = $("#judgeCategory").val();
		if (name != "") {
			let judgeData = { name: name, category: category }
			$.post(
				"api/judges/create.php",
				judgeData,
				() => {
					fillBody();
				},
				"json").fail(() => {
					handleSessionExpiration();
				});
		}
	});

}

function fillJudgeTable() {
	generateJudgeTable().then((tableHTML) => {
		$("#judgeTable").html(tableHTML)
		fillConflicts();
		attachListeners();
	});
}

function generateJudgeTable() {
	return new Promise((resolve, reject) => {
		let tableHTML = "<div style='display: grid;'>";
		let headerHTML = "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Judge</div>";
		headerHTML += "<div style='grid-column: 2 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Category</div>";
		headerHTML += "<div style='grid-column: 3 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Present</div>";
		headerHTML += "<div  style='grid-column: 4 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Round 1</div>";
		headerHTML += "<div  style='grid-column: 5 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Round 2</div>";
		headerHTML += "<div  style='grid-column: 6 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Round 3</div>";
		headerHTML += "<div  style='grid-column: 7 / span 1; grid-row: 1 / span 1; writing-mode: vertical-lr;'>Round 4</div>";

		Promise.all([getJudges(), getTeams()]).then((values) => {
			let judges = values[0];
			let teams = values[1];

			//add a column to the table for each team
			for (let a = 0; a < teams.length; a++) {
				let teamHeaderHTML = `<div style='grid-column: ${8 + a} / span 1; grid-row: 1 / span 1;writing-mode: vertical-lr;'>${teams[a].number + " " + teams[a].name}</div>`;
				headerHTML += teamHeaderHTML;
			}
			tableHTML += headerHTML;

			//add a row to the table for each judge
			for (let a = 0; a < judges.length; a++) {
				let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${2 + a} / span 1;'><input class='judgeName' judgeid='${judges[a].id}' value='${judges[a].name}'></div>`;
				rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${2 + a} / span 1;'><select class='judgeCategory' judgeid='${judges[a].id}'>`;
				switch (judges[a].category) {
					case 1:
						rowHTML += "<option value='1' selected>1</option><option value='2'>2</option><option value='3'>3</option>"
						break;
					case 2:
						rowHTML += "<option value='1'>1</option><option value='2' selected>2</option><option value='3'>3</option>"
						break;
					case 3:
						rowHTML += "<option value='1'>1</option><option value='2'>2</option><option value='3' selected>3</option>"
						break;
				}
				rowHTML += "</select></div>"
				rowHTML += `<div style = 'grid-column: 3 / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='checkedIn' judgeid='${judges[a].id}' ${judges[a].checkedIn ? 'checked' : ''}></div>`;
				rowHTML += `<div style = 'grid-column: 4 / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='round' round='1' judgeid='${judges[a].id}' ${judges[a].round1 ? 'checked' : ''}></div>`;
				rowHTML += `<div style = 'grid-column: 5 / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='round' round='2' judgeid='${judges[a].id}' ${judges[a].round2 ? 'checked' : ''}></div>`;
				rowHTML += `<div style = 'grid-column: 6 / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='round' round='3' judgeid='${judges[a].id}' ${judges[a].round3 ? 'checked' : ''}></div>`;
				rowHTML += `<div style = 'grid-column: 7 / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='round' round='4' judgeid='${judges[a].id}' ${judges[a].round4 ? 'checked' : ''}></div>`;
				for (let b = 0; b < teams.length; b++) {
					rowHTML += `<div style = 'grid-column: ${8 + b} / span 1; grid-row: ${2 + a} / span 1;'><input type='checkbox' class='conflict' judgeid='${judges[a].id}' teamid='${teams[b].id}'></div>`;
				}

				tableHTML += rowHTML;
			}


			resolve(tableHTML);
		});
	});


};

function fillConflicts() {
	getConflicts().then((conflicts) => {
		let conflictChecks = $(".conflict");

		for (let a = 0; a < conflictChecks.length; a++) {
			$(conflictChecks[a]).prop("checked", false);
		}

		for (let a = 0; a < conflictChecks.length; a++) {
			let checkbox = $(conflictChecks[a]);
			let checkboxJudge = checkbox.attr("judgeid");
			let checkboxTeam = checkbox.attr("teamid");
			for (let b = 0; b < conflicts.length; b++) {
				let judge = conflicts[b].judge;
				let team = conflicts[b].team;
				if (checkboxJudge == judge && checkboxTeam == team) {
					checkbox.prop("checked", true);
				}
			}
		}
	});
};

function attachListeners() {
	$(".judgeName").on("input", function () {
		let id = $(this).attr("judgeid");
		let name = $(this).val();
		updateName(id, name);
	});
	$(".judgeCategory").on("change", function () {
		let id = $(this).attr("judgeid");
		let category = $(this).val();
		updateCategory(id, category);
	});
	$(".checkedIn").on("change", function () {
		let id = $(this).attr("judgeid");
		let checkedIn = 0;
		if ($(this).prop("checked")) {
			checkedIn = 1;
		}
		updateCheckedIn(id, checkedIn);
	});
	$(".round").on("change", function () {
		let id = $(this).attr("judgeid");
		let round = $(this).attr("round");
		let availability = 0;
		if ($(this).prop("checked")) {
			availability = 1;
		}
		updateAvailability(id, round, availability);
	});
	$(".conflict").on("change", function () {
		let judgeId = $(this).attr("judgeid");
		let teamId = $(this).attr("teamid");
		if ($(this).prop("checked")) {
			createConflict(judgeId, teamId);
		} else {
			deleteConflict(judgeId, teamId);
		}
	});
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
			"api/judgeConflicts/getAll.php",
			(conflicts) => {
				resolve(conflicts);
			},
			"json").fail(() => {
				handleSessionExpiration();
			});;
	})
}

function updateName(id, name) {
	let updateData = { id: id, name: name };
	$.post(
		"api/judges/updateName.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});;
}

function updateCategory(id, category) {
	let updateData = { id: id, category: category };
	$.post(
		"api/judges/updateCategory.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});;
}

function updateCheckedIn(id, checkedIn) {
	let updateData = { id: id, checkedIn: checkedIn };
	$.post(
		"api/judges/updateCheckedIn.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});;
}

function updateAvailability(id, round, availability) {
	let updateData = { id: id, round: round, availability: availability };
	$.post(
		"api/judges/updateAvailability.php",
		updateData,
		function (response) {
		},
		"json").fail(() => {
			handleSessionExpiration();
		});;
}

function createConflict(judge, team) {
	let conflictData = { judge: judge, team: team };
	$.post(
		"api/judgeConflicts/create.php",
		conflictData,
		function (response) {
			fillConflicts();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
};

function deleteConflict(judge, team) {
	let conflictData = { judge: judge, team: team };
	$.post(
		"api/judgeConflicts/delete.php",
		conflictData,
		function (response) {
			fillConflicts();
		},
		"json").fail(() => {
			handleSessionExpiration();
		});
};

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};
