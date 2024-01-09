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
	$("#ballots").tabs();

	getBallots().then((ballots) => {
		for (let round = 1; round <= 4; round++) {
			let tabHTML = "<div style='display: grid;'>";
			tabHTML += "<div style='grid-column: 1 / span 1; grid-row: 1 / span 1;'>Room</div>";
			tabHTML += "<div style='grid-column: 2 / span 1; grid-row: 1 / span 1;'>Plaintiff</div>";
			tabHTML += "<div style='grid-column: 3 / span 1; grid-row: 1 / span 1;'>Defense</div>";
			tabHTML += "<div style='grid-column: 4 / span 1; grid-row: 1 / span 1;'>π Result</div>";
			tabHTML += "<div style='grid-column: 5 / span 1; grid-row: 1 / span 1;'>Judge</div>";
			tabHTML += "<div style='grid-column: 6 / span 1; grid-row: 1 / span 1;'>Judge URL</div>";
			tabHTML += "<div style='grid-column: 7 / span 1; grid-row: 1 / span 1;'>Status</div>";
			tabHTML += "<div style='grid-column: 8 / span 1; grid-row: 1 / span 1;'>View/Edit</div>";
			tabHTML += "<div style='grid-column: 9 / span 1; grid-row: 1 / span 1;'>Release Comments</div>";
			tabHTML += "<div style='grid-column: 10 / span 1; grid-row: 1 / span 1;'>Release Scores</div>";

			ballots.forEach(function (ballot, index) {
				if (ballot.round == round) {
					tabHTML += `<div style='grid-column: 1 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.room}</div>`;
					tabHTML += `<div style='grid-column: 2 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.plaintiff}</div>`;
					tabHTML += `<div style='grid-column: 3 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.defense}</div>`;
					tabHTML += `<div style='grid-column: 4 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.pPD}</div>`;
					tabHTML += `<div style='grid-column: 5 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.judge}</div>`;
					tabHTML += `<div style='grid-column: 6 / span 1; grid-row: ${index + 2} / span 1;'><a href='b.php?b=${ballot.url}'>Link</a></div>`;
					tabHTML += `<div style='grid-column: 7 / span 1; grid-row: ${index + 2} / span 1;'>${ballot.locked ? 'Submitted' : 'In Progress'}</div>`;
					tabHTML += `<div style='grid-column: 8 / span 1; grid-row: ${index + 2} / span 1;'><a href='editBallot.php?ballot=${ballot.id}'>View/Edit</a></div>`;
					tabHTML += `<div style='grid-column: 9 / span 1; grid-row: ${index + 2} / span 1;'><input class='commentToggle' type=checkbox ballot='${ballot.id}' ${ballot.releaseComments ? 'checked' : ''} /></div>`;
					tabHTML += `<div style='grid-column: 10 / span 1; grid-row: ${index + 2} / span 1;'><input class='scoreToggle' type=checkbox ballot='${ballot.id}' ${ballot.releaseScores ? 'checked' : ''} /></div>`;
				}

			});



			tabHTML += "</div>";
			$(`#round${round}`).html(tabHTML);
		}
		attachListeners();
	});
	function attachListeners() {
		$(".commentToggle").on("change", function () {
			let ballotId = $(this).attr("ballot");
			let commentState = 0;
			if ($(this).prop("checked")) {
				commentState = 1;
			}
			toggleComments(ballotId, commentState);
		});

		$(".scoreToggle").on("change", function () {
			let ballotId = $(this).attr("ballot");
			let scoreState = 0;
			if ($(this).prop("checked")) {
				scoreState = 1;
			}
			toggleScores(ballotId, scoreState);
		});
	}
}

function getBallots() {
	return new Promise((resolve, reject) => {
		$.get("api/ballots/getAll.php", (ballots) => { resolve(ballots) }, "json")
	});
}

function toggleComments(ballotId, commentState) {
	let data = {
		ballotId: ballotId,
		commentsReleased: commentState
	}
	$.post("api/ballots/toggleCommentVisability.php", data, function () { }, "json")
}

function toggleScores(ballotId, scoreState) {
	let data = {
		ballotId: ballotId,
		scoresReleased: scoreState
	}
	$.post("api/ballots/toggleScoreVisibility.php", data, function () { }, "json")
}