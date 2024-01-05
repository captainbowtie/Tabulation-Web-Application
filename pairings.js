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
	//create tabs
	let bodyHTML = "<div id='pairings'><ul><li><a href='#round1'>Round 1</a></li><li><a href='#round2'>Round 2</a></li><li><a href='#round3'>Round 3</a></li><li><a href='#round4'>Round 4</a></li></ul><div id='round1'></div><div id='round2'></div><div id='round3'></div><div id='round4'></div></div>"
	$("#body").html(bodyHTML);
	$("#pairings").tabs();
	getTeams().then((teams) => {
		//create table for each tab
		for (let round = 1; round <= 4; round++) {
			//header
			let tableHTML = `<div style='display: grid;' round='${round}'>`;
			tableHTML += `<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Room</div><div style='grid-column: 2 / span 1; grid-row: 1 / span 1;'>π</div><div style='grid-column: 3 / span 1; grid-row: 1 / span 1;'>∆</div>`
			//generate html for select options that list each team
			let selectOptionHTML = ""
			for (let a = 0; a < teams.length; a++) {
				selectOptionHTML += `<option value='${teams[a].id}'>${teams[a].number + " " + teams[a].name}</option>`;
			}

			//row for each pairing
			for (let a = 0; a < teams.length / 2; a++) {
				let rowHTML = `<div style='grid-column: 1 / span 1; grid-row: ${2 + a} / span 1;'><input class='room' round='${round}' pairing='${a}'></div>`;
				rowHTML += `<div style='grid-column: 2 / span 1; grid-row: ${2 + a} / span 1;'><select class='plaintiff' round='${round}' pairing='${a}'>${selectOptionHTML}</select></div>`;
				rowHTML += `<div style='grid-column: 3 / span 1; grid-row: ${2 + a} / span 1;'><select class='defense' round='${round}' pairing='${a}'>${selectOptionHTML}</select></div>`;
				tableHTML += rowHTML;
			}
			tableHTML += "</div>";
			tableHTML += `<button class='saveButton' round='${round}' state='save'>Save Pairings</button>`;

			$(`#round${round}`).html(tableHTML);
		}

		$(".saveButton").click(function () {
			if ($(this).attr("state") == "save") {
				savePairings($(this).attr("round"));
			} else {
				$(this).attr("state", "save");
				$(this).html("Save Pairings");
				$(`[round=${$(this).attr("round")}]`).attr("disabled", false);
			}
		});

		fillPairings();
	});
}

function fillPairings() {
	getPairings().then((pairings) => {
		//sort pairings by round into separate arrays
		let roundPairings = [[], [], [], [], []];
		for (let a = 0; a < pairings.length; a++) {
			roundPairings[pairings[a].round].push(pairings[a]);
		}


		for (let round = 1; round < 4; round++) {
			//fill in pairings in table
			roundPairings[round].forEach(function (pairing, index) {
				$(`.room[round=${round}][pairing=${index}]`).val(pairing.room);
				$(`.plaintiff[round=${round}][pairing=${index}]`).val(pairing.plaintiff);
				$(`.defense[round=${round}][pairing=${index}]`).val(pairing.defense);
			})

			//adjust save button behavior and disable elements if necessary
			if (roundPairings[round].length > 0) { //check if pairings exist for a particular round
				$(`.room[round=${round}]`).attr("disabled", true);
				$(`.plaintiff[round=${round}]`).attr("disabled", true);
				$(`.defense[round=${round}]`).attr("disabled", true);
				$(`.saveButton[round=${round}]`).attr("state", "unlock");
				$(`.saveButton[round=${round}]`).html("Unlock Pairings");
			}
		}
	});
}

function savePairings(round) {
	let rooms = $(`.room[round='${round}']`)
	let plaintiffs = $(`.plaintiff[round='${round}']`)
	let defenses = $(`.defense[round='${round}']`)
	let pairings = [];
	for (let a = 0; a < rooms.length; a++) {
		let room = $(rooms[a]).val();
		let plaintiff;
		let defense;
		for (let b = 0; b < plaintiffs.length; b++) {
			if ($(rooms[a]).attr("pairing") == $(plaintiffs[b]).attr("pairing")) {
				plaintiff = $(plaintiffs[b]).val();
			}
			if ($(rooms[a]).attr("pairing") == $(defenses[b]).attr("pairing")) {
				defense = $(defenses[b]).val();
			}
		}

		let pairing = {
			room: room,
			plaintiff, plaintiff,
			defense: defense
		};
		pairings.push(pairing);
	}

	let pairingData = { round: round, pairings: pairings }

	$.post(
		"api/pairings/create.php",
		pairingData,
		function () {
			fillPairings();
		},
		"json").fail(() => {
			handleSessionExpiration();
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
			});
	})
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

function handleSessionExpiration() {
	let html = "User session expired. Please login again using your login link."
	$("body").html(html);
};