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

$("#submitCaptains").click(() => {
	if (captainsVerified()) {
		let portaIsDefendant = 0;
		let count1 = 0;
		let count2 = 0;
		let count3 = 0;
		let count4 = 0;
		let count5 = 0;
		if ($(":checked.dlp").length > 0) {
			portaIsDefendant = 1;
			if ($(":checked#dlp1").prop("checked")) {
				count1 = 1;
			}
			if ($(":checked#dlp2").prop("checked")) {
				count2 = 1;
			}
			if ($(":checked#dlp3").prop("checked")) {
				count3 = 1;
			}
			if ($(":checked#dlp4").prop("checked")) {
				count4 = 1;
			}
			if ($(":checked#dlp5").prop("checked")) {
				count5 = 1;
			}
		} else {
			if ($(":checked#pc1").prop("checked")) {
				count1 = 1;
			}
			if ($(":checked#pc2").prop("checked")) {
				count2 = 1;
			}
			if ($(":checked#pc3").prop("checked")) {
				count3 = 1;
			}
			if ($(":checked#pc4").prop("checked")) {
				count4 = 1;
			}
			if ($(":checked#pc5").prop("checked")) {
				count5 = 1;
			}
		}
		let duress = 0;
		if ($("input[name='duress']:checked").val() == "duress" && !portaIsDefendant) {
			duress = 1;
		}
		let pWitness1 = $("#pWit1").val();
		let pWitness2 = $("#pWit2").val();
		let pWitness3 = $("#pWit3").val();
		let dWitness1 = $("#dWit1").val();
		let dWitness2 = $("#dWit2").val();
		let dWitness3 = $("#dWit3").val();

		let pWitCalls = [];
		let dWitCalls = [];
		for (let a = 1; a <= 3; a++) {

			switch (parseInt($(`#pWit${a}Order`).val())) {
				case 1:
					pWitCalls[1] = $(`#pWit${a}`).val();
					break;
				case 2:
					pWitCalls[2] = $(`#pWit${a}`).val();
					break;
				case 3:
					pWitCalls[3] = $(`#pWit${a}`).val();
					break;
				default:
					break;
			}
			switch (parseInt($(`#dWit${a}Order`).val())) {
				case 1:
					dWitCalls[1] = $(`#dWit${a}`).val();
					break;
				case 2:
					dWitCalls[2] = $(`#dWit${a}`).val();
					break;
				case 3:
					dWitCalls[3] = $(`#dWit${a}`).val();
					break;
			}
		}
		submitCaptains(portaIsDefendant, count1, count2, count3, count4, count5, duress, pWitness1, pWitness2, pWitness3, dWitness1, dWitness2, dWitness3, pWitCalls[1], pWitCalls[2], pWitCalls[3], dWitCalls[1], dWitCalls[2], dWitCalls[3]);
	} else {
		console.log("error");
	}
});

function submitCaptains(portaIsDefendant, count1, count2, count3, count4, count5, duress, pWitness1, pWitness2, pWitness3, dWitness1, dWitness2, dWitness3, pCall1, pCall2, pCall3, dCall1, dCall2, dCall3) {
	//determine defendant
	let data = {
		portaIsDefendant: portaIsDefendant,
		count1: count1,
		count2: count2,
		count3: count3,
		count4: count4,
		count5: count5,
		duress: duress,
		pWitness1: pWitness1,
		pWitness2: pWitness2,
		pWitness3: pWitness3,
		dWitness1: dWitness1,
		dWitness2: dWitness2,
		dWitness3: dWitness3,
		pCall1: pCall1,
		pCall2: pCall2,
		pCall3: pCall3,
		dCall1: dCall1,
		dCall2: dCall2,
		dCall3: dCall3
	}
	$.post("api/captains/submit.php",
		data,
		function () { },
		"json");
}

function captainsVerified() {
	let verified = true;
	//check that only one defendant is indicted
	if ($(":checked.dlp").length > 0 && $(":checked.pc").length > 0) {
		verified = false;
	}

	//check that BDP has at least one charge if indicted
	if ($(":checked.dlp").length == 0 && $(":checked.pc").length == 0) {
		verified = false;
	}

	//check that PC has at least two charges if indicted
	if ($(":checked.dlp").length == 0 && $(":checked.pc").length < 2) {
		verified = false;
	}

	//check that prosecution is calling three different witnesses
	let pWit1 = $("#pWit1").val();
	let pWit2 = $("#pWit2").val();
	let pWit3 = $("#pWit3").val();
	if (pWit1 == pWit2 || pWit1 == pWit3 || pWit2 == pWit3 || pWit1 == 0 || pWit2 == 0 || pWit3 == 0) {
		verified = false;
	}

	//check that defense is calling three different witnesses
	let dWit1 = $("#dWit1").val();
	let dWit2 = $("#dWit2").val();
	let dWit3 = $("#dWit3").val();
	if (dWit1 == dWit2 || dWit1 == dWit3 || dWit2 == dWit3 || dWit1 == 0 || dWit2 == 0 || dWit3 == 0) {
		verified = false;
	}

	//check that defense is not calling unindicted defendant
	if ($(":checked.dlp").length > 0 && (dWit1 == "cameron" || dWit2 == "cameron" || dWit3 == "cameron")) {
		verified = false;
	}
	if ($(":checked.pc").length > 0 && (dWit1 == "dlp" || dWit2 == "dlp" || dWit3 == "dlp")) {
		verified = false;
	}

	//check that prosecution order as 1, 2, and 3
	let pWit1Order = $("#pWit1Order").val();
	let pWit2Order = $("#pWit2Order").val();
	let pWit3Order = $("#pWit3Order").val();
	if (pWit1Order == pWit2Order || pWit1Order == pWit3Order || pWit2Order == pWit3Order || pWit1Order == 0 || pWit2Order == 0 || pWit3Order == 0) {
		verified = false;
	}

	//check that defense order has 1,2, and 3
	let dWit1Order = $("#dWit1Order").val();
	let dWit2Order = $("#dWit2Order").val();
	let dWit3Order = $("#dWit3Order").val();
	if (dWit1Order == dWit2Order || dWit1Order == dWit3Order || dWit2Order == dWit3Order || dWit1Order == 0 || dWit2Order == 0 || dWit3Order == 0) {
		verified = false;
	}

	//check that defense is not calling any witnesses called by prosecution
	if (pWit1 == dWit1 || pWit1 == dWit2 || pWit1 == dWit3 || pWit2 == dWit1 || pWit2 == dWit2 || pWit2 == dWit3 || pWit3 == dWit1 || pWit3 == dWit2 || pWit3 == dWit3) {
		verified = false;
	}

	return verified;
}