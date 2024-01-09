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
	fillBallotData();
	//timeout to autosave ballot
});

function fillBallotData() {
	let data;
	if (ballotId !== 'undefined') {
		data = `ballot=${ballotId}`;
	}
	$.get("api/ballots/getBallotData.php", data, (ballot) => {
		$("#roundInfo").html(ballot.pTeam + " v. " + ballot.dTeam);
		$("#judgeName").val(ballot.judge);
		$("#pOpen").val(ballot.pOpen);
		$("#pDx1").val(ballot.pDx1);
		$("#pDx2").val(ballot.pDx2);
		$("#pDx3").val(ballot.pDx3);
		$("#pCx1").val(ballot.pCx1);
		$("#pCx2").val(ballot.pCx2);
		$("#pCx3").val(ballot.pCx3);
		$("#pWDx1").val(ballot.pWDx1);
		$("#pWDx2").val(ballot.pWDx2);
		$("#pWDx3").val(ballot.pWDx3);
		$("#pWCx1").val(ballot.pWCx1);
		$("#pWCx2").val(ballot.pWCx2);
		$("#pWCx3").val(ballot.pWCx3);
		$("#pClose").val(ballot.pClose);
		$("#pOpenComments").val(ballot.pOpenComments);
		$("#pDx1Comments").val(ballot.pDx1Comments);
		$("#pDx2Comments").val(ballot.pDx2Comments);
		$("#pDx3Comments").val(ballot.pDx3Comments);
		$("#pCx1Comments").val(ballot.pCx1Comments);
		$("#pCx2Comments").val(ballot.pCx2Comments);
		$("#pCx3Comments").val(ballot.pCx3Comments);
		$("#pWDx1Comments").val(ballot.pWDx1Comments);
		$("#pWDx2Comments").val(ballot.pWDx2Comments);
		$("#pWDx3Comments").val(ballot.pWDx3Comments);
		$("#pWCx1Comments").val(ballot.pWCx1Comments);
		$("#pWCx2Comments").val(ballot.pWCx2Comments);
		$("#pWCx3Comments").val(ballot.pWCx3Comments);
		$("#pCloseComments").val(ballot.pCloseComments);
		$("#dOpen").val(ballot.dOpen);
		$("#dDx1").val(ballot.dDx1);
		$("#dDx2").val(ballot.dDx2);
		$("#dDx3").val(ballot.dDx3);
		$("#dCx1").val(ballot.dCx1);
		$("#dCx2").val(ballot.dCx2);
		$("#dCx3").val(ballot.dCx3);
		$("#dWDx1").val(ballot.dWDx1);
		$("#dWDx2").val(ballot.dWDx2);
		$("#dWDx3").val(ballot.dWDx3);
		$("#dWCx1").val(ballot.dWCx1);
		$("#dWCx2").val(ballot.dWCx2);
		$("#dWCx3").val(ballot.dWCx3);
		$("#dClose").val(ballot.dClose);
		$("#dOpenComments").val(ballot.dOpenComments);
		$("#dDx1Comments").val(ballot.dDx1Comments);
		$("#dDx2Comments").val(ballot.dDx2Comments);
		$("#dDx3Comments").val(ballot.dDx3Comments);
		$("#dCx1Comments").val(ballot.dCx1Comments);
		$("#dCx2Comments").val(ballot.dCx2Comments);
		$("#dCx3Comments").val(ballot.dCx3Comments);
		$("#dWDx1Comments").val(ballot.dWDx1Comments);
		$("#dWDx2Comments").val(ballot.dWDx2Comments);
		$("#dWDx3Comments").val(ballot.dWDx3Comments);
		$("#dWCx1Comments").val(ballot.dWCx1Comments);
		$("#dWCx2Comments").val(ballot.dWCx2Comments);
		$("#dWCx3Comments").val(ballot.dWCx3Comments);
		$("#dCloseComments").val(ballot.dCloseComments);
		$("#pOpenAttorney").html(`π Open (${ballot.pOpenAttorney}):`);
		$("#pDx1Label").html(`Atty Dx (${ballot.pDx1Attorney}):`);
		$("#pDx2Label").html(`Atty Dx (${ballot.pDx2Attorney}):`);
		$("#pDx3Label").html(`Atty Dx (${ballot.pDx3Attorney}):`);
		$("#pCx1Label").html(`Atty Cx (${ballot.pCx1Attorney}):`);
		$("#pCx2Label").html(`Atty Cx (${ballot.pCx2Attorney}):`);
		$("#pCx3Label").html(`Atty Cx (${ballot.pCx3Attorney}):`);
		$("#pWDx1Label").html(`Wit Dx (${ballot.pWDx1Student}):`);
		$("#pWDx2Label").html(`Wit Dx (${ballot.pWDx2Student}):`);
		$("#pWDx3Label").html(`Wit Dx (${ballot.pWDx3Student}):`);
		$("#pWCx1Label").html(`Wit Cx (${ballot.pWDx1Student}):`);
		$("#pWCx2Label").html(`Wit Cx (${ballot.pWDx2Student}):`);
		$("#pWCx3Label").html(`Wit Cx (${ballot.pWDx3Student}):`);
		$("#pCloseAttorney").html(`π Close (${ballot.pCloseAttorney}):`);
		$("#dOpenAttorney").html(`∆ Open (${ballot.dOpenAttorney}):`);
		$("#dDx1Label").html(`Atty Dx (${ballot.dDx1Attorney}):`);
		$("#dDx2Label").html(`Atty Dx (${ballot.dDx2Attorney}):`);
		$("#dDx3Label").html(`Atty Dx (${ballot.dDx3Attorney}):`);
		$("#dCx1Label").html(`Atty Cx (${ballot.dCx1Attorney}):`);
		$("#dCx2Label").html(`Atty Cx (${ballot.dCx2Attorney}):`);
		$("#dCx3Label").html(`Atty Cx (${ballot.dCx3Attorney}):`);
		$("#dWDx1Label").html(`Wit Dx (${ballot.dWDx1Student}):`);
		$("#dWDx2Label").html(`Wit Dx (${ballot.dWDx2Student}):`);
		$("#dWDx3Label").html(`Wit Dx (${ballot.dWDx3Student}):`);
		$("#dWCx1Label").html(`Wit Cx (${ballot.dWDx1Student}):`);
		$("#dWCx2Label").html(`Wit Cx (${ballot.dWDx2Student}):`);
		$("#dWCx3Label").html(`Wit Cx (${ballot.dWDx3Student}):`);
		$("#dCloseAttorney").html(`∆ Close (${ballot.dCloseAttorney}):`);
		$("#witness1").html(`Plaintiff Witness 1 (${ballot.witness1})`);
		$("#witness2").html(`Plaintiff Witness 2 (${ballot.witness2})`);
		$("#witness3").html(`Plaintiff Witness 3 (${ballot.witness3})`);
		$("#witness4").html(`Defense Witness 1 (${ballot.witness4})`);
		$("#witness5").html(`Defense Witness 2 (${ballot.witness5})`);
		$("#witness6").html(`Defense Witness 3 (${ballot.witness6})`);
		let attorneyOptions = "<option value='0'>---</option>";
		attorneyOptions += `<option value='${ballot.pDx1AttorneyId}'>${ballot.pDx1Attorney}</option>`;
		attorneyOptions += `<option value='${ballot.pDx2AttorneyId}'>${ballot.pDx2Attorney}</option>`;
		attorneyOptions += `<option value='${ballot.pDx3AttorneyId}'>${ballot.pDx3Attorney}</option>`;
		attorneyOptions += `<option value='${ballot.dDx1AttorneyId}'>${ballot.dDx1Attorney}</option>`;
		attorneyOptions += `<option value='${ballot.dDx2AttorneyId}'>${ballot.dDx2Attorney}</option>`;
		attorneyOptions += `<option value='${ballot.dDx3AttorneyId}'>${ballot.dDx3Attorney}</option>`;
		let witnessOptions = "<option value='0'>---</option>";
		witnessOptions += `<option value='${ballot.pWitness1Id}'>${ballot.pWDx1Student} (${ballot.witness1})</option>`;
		witnessOptions += `<option value='${ballot.pWitness2Id}'>${ballot.pWDx2Student} (${ballot.witness2})</option>`;
		witnessOptions += `<option value='${ballot.pWitness3Id}'>${ballot.pWDx3Student} (${ballot.witness3})</option>`;
		witnessOptions += `<option value='${ballot.dWitness1Id}'>${ballot.dWDx1Student} (${ballot.witness4})</option>`;
		witnessOptions += `<option value='${ballot.dWitness2Id}'>${ballot.dWDx2Student} (${ballot.witness5})</option>`;
		witnessOptions += `<option value='${ballot.dWitness3Id}'>${ballot.dWDx3Student} (${ballot.witness6})</option>`;
		$("#aty1").html(attorneyOptions);
		$("#aty2").html(attorneyOptions);
		$("#aty3").html(attorneyOptions);
		$("#aty4").html(attorneyOptions);
		$("#wit1").html(witnessOptions);
		$("#wit2").html(witnessOptions);
		$("#wit3").html(witnessOptions);
		$("#wit4").html(witnessOptions);
		$("#aty1").val(ballot.aty1);
		$("#aty2").val(ballot.aty2);
		$("#aty3").val(ballot.aty3);
		$("#aty4").val(ballot.aty4);
		$("#wit1").val(ballot.wit1);
		$("#wit2").val(ballot.wit2);
		$("#wit3").val(ballot.wit3);
		$("#wit4").val(ballot.wit4);
	}, "json");
}

$("#judgeName").change(function () {
	let name = $(this).val();
	updateName(name);
});

$("select").on("change", function () {
	let part = $(this).attr("id");
	let score = $(this).val();
	updateScore(part, score);
});

$("textarea").on("change", function () {
	let part = $(this).attr("id");
	let comment = $(this).val();
	updateComment(part, comment);
});

function validateBallot() {
	let scoresValid = true;
	let scores = $(".score");
	for (let a = 0; a < scores.length; a++) {
		if ($(scores[a]).val() == -1) {
			scoresValid = false;
		}
	}

	let atyIndexes = []
	let witIndexes = [];
	for (let a = 1; a <= 4; a++) {
		atyIndexes.push($(`#aty${a}`).val());
		witIndexes.push($(`#wit${a}`).val());
		if ($(`#aty${a}`).val() == 0) {
			scoresValid = false;
		}
		if ($(`#wit${a}`).val() == 0) {
			scoresValid = false;
		}
	}
	let attorneyCount = new Set(atyIndexes).size;
	let witnessCount = new Set(witIndexes).size;
	if (attorneyCount != 4) {
		scoresValid = false;
	}
	if (witnessCount != 4) {
		scoresValid = false;
	}

	return scoresValid;
}

function updateName(name) {
	let data = {
		ballot: ballotId,
		name: name
	}
	$.post("api/ballots/updateName.php", data, () => { }, "json");
}

function updateScore(part, score) {
	let data = {
		ballot: ballotId,
		part: part,
		score: score
	}
	$.post("api/ballots/updateScore.php", data, () => { }, "json");
}

function updateComment(part, comment) {
	let data = {
		ballot: ballotId,
		part: part,
		comment: comment
	}
	$.post("api/ballots/updateComment.php", data, () => { }, "json");
}