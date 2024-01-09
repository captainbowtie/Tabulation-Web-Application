let ballotURL = window.location.protocol + "//" + window.location.host + "/b.php?p=" + pairingURL;
new QRCode(document.getElementById("qr"), ballotURL);
