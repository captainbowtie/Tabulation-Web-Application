let ballots;
let roundBallots;
let pairingBallots;
let displayedBallot;

$(document).ready(() => {
	getBallotData().then((ballotData) => {
		ballots = ballotData;
		$("#round").val(1);
		fillPairingSelect(1);
	});
})

function fillPairingSelect(roundNumber) {
	roundBallots = [];
	ballots.forEach((ballot) => {
		if (ballot.round == roundNumber) {
			roundBallots.push(ballot)
		}
	})
	let pairingOptionsHTML = "";
	roundBallots.forEach((ballot) => {
		pairingOptionsHTML += `<option value='${ballot.pTeamNumber}'>${ballot.pTeamNumber + " v. " + ballot.dTeamNumber}</option>`;
	})
	$("#pairing").html(pairingOptionsHTML);
	$("#pairing").val(roundBallots[0].pTeamNumber);
	fillBallotSelect(roundBallots[0].pTeamNumber);
}

function fillBallotSelect(pTeamNumber) {
	pairingBallots = [];
	roundBallots.forEach((ballot) => {
		if (ballot.pTeamNumber == pTeamNumber) {
			pairingBallots.push(ballot)
		}
	});
	let ballotOptionsHTML = "";
	pairingBallots.forEach((ballot) => {
		ballotOptionsHTML += `<option value='${ballot.judge}'>${ballot.judge}</option>`;
	});
	$("#ballot").html(ballotOptionsHTML);
	$("#ballot").val(pairingBallots[0].judge);
	fillBallot(pairingBallots[0]);
}

function fillBallot(ballot) {
	$("#roundInfo").html(ballot.pTeamName + " v. " + ballot.dTeamName);
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
}

function getBallotData() {
	return new Promise((resolve, reject) => {
		$.get(`api/teamBallots/getBallots.php?user=${user}`, (ballotData) => {
			resolve(ballotData);
		}, "json");
	});
}