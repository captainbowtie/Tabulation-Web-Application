window.addEventListener("load", () => {
	var qrc = new QRCode(
		document.getElementById("qrcode"),
		window.location.host
	);
});

function getBallotURL() {
	return new Promise((resolve, reject) => {
		$.get("api/captains/getBallotURL.php", () => { }, "json");
	});

}