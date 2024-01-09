<!--
Copyright (C) 2024 allen

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width = device-width, initial-scale = 1.0">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<!--Latest compiled and minified CSS-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

	<!--Optional theme-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<link rel="stylesheet" href="editBallot.css">

	<title>Ballot</title>
</head>

<div>
	<p id='roundInfo'>Round #, v.</p>
	<p><input id='judgeName' placeholder='judgeName'></p>
</div>

<div id="scoresDiv">
	<div class="accordion" id="scores">
		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#openingScores" aria-expanded="true" aria-controls="openingScores">
						Opening
					</button>
				</h2>
			</div>

			<div id="openingScores" class="collapse show" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body speech">
					<label for="pOpen" class="pSpeechLabel" id="pOpenAttorney">π Open ():</label>
					<select class="pSpeech score" id="pOpen" name="pOpen">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dOpen" class="dSpeechLabel" id="dOpenAttorney">∆ Open ():</label>
					<select class="dSpeech  score" id="dOpen" name="dOpen">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pSpeechComments" id="pOpenComments"></textarea>
					<textarea class="dSpeechComments" id="dOpenComments"></textarea>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p1Scores" aria-expanded="true" aria-controls="p1Scores" id="witness1">
						Plaintiff Witness 1 ()
					</button>
				</h2>
			</div>

			<div id="p1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body plaintiffWitness">
					<label for="pDx1" class="pDxLabel" id="pDx1Label">Atty Dx ():</label>
					<select class="pDx  score" id="pDx1" name="pDx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dCx1" class="dCxLabel" id="dCx1Label">Atty Cx ():</label>
					<select class="dCx  score" id="dCx1" name="dCx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pDxComments" id="pDx1Comments"></textarea>
					<textarea class="dCxComments" id="dCx1Comments"></textarea>

					<label for="pWDx1" class="pWDxLabel" id="pWDx1Label">Wit Dx ():</label>
					<select class="pWDx  score" id="pWDx1" name="pWDx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pWDxComments" id="pWDx1Comments"></textarea>

					<label for="pWCx1" class="pWCxLabel" id="pWCx1Label">Wit Cx ():</label>
					<select class="pWCx score" id="pWCx1" name="pWCx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pWCxComments" id="pWCx1Comments"></textarea>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p2Scores" aria-expanded="true" aria-controls="p2Scores" id="witness2">
						Plaintiff Witness 2 ()
					</button>
				</h2>
			</div>

			<div id="p2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body plaintiffWitness">
					<label for="pDx2" class="pDxLabel" id="pDx2Label">Atty Dx ():</label>
					<select class="pDx score" id="pDx2" name="pDx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dCx2" class="dCxLabel" id="dCx2Label">Atty Cx ():</label>
					<select class="dCx score" id="dCx2" name="dCx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pDxComments" id="pDx2Comments"></textarea>
					<textarea class="dCxComments" id="dCx2Comments"></textarea>

					<label for="pWDx2" class="pWDxLabel" id="pWDx2Label">Wit Dx ():</label>
					<select class="pWDx score" id="pWDx2" name="pWDx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pWDxComments" id="pWDx2Comments"></textarea>
					<label for="pWCx2" class="pWCxLabel" id="pWCx2Label">Wit Cx ():</label>
					<select class="pWCx score" id="pWCx2" name="pWCx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pWCxComments" id="pWCx2Comments"></textarea>
				</div>


			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#p3Scores" aria-expanded="true" aria-controls="p3Scores" id="witness3">
						Plaintiff Witness 3 ()
					</button>
				</h2>
			</div>

			<div id="p3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body plaintiffWitness">
					<label for="pDx3" class="pDxLabel" id="pDx3Label">Atty Dx ():</label>
					<select class="pDx score" id="pDx3" name="pDx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dCx3" class="dCxLabel" id="dCx3Label">Atty Cx ():</label>
					<select class="dCx score" id="dCx3" name="dCx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pDxComments" id="pDx3Comments"></textarea>
					<textarea class="dCxComments" id="dCx3Comments"></textarea>

					<label for="pWDx3" class="pWDxLabel" id="pWDx3Label">Wit Dx ():</label>
					<select class="pWDx score" id="pWDx3" name="pWDx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pWDxComments" id="pWDx3Comments"></textarea>

					<label for="pWCx3" class="pWCxLabel" id="pWCx3Label">Wit Cx ():</label>
					<select class="pWCx score" id="pWCx3" name="pWCx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pWCxComments" id="pWCx3Comments"></textarea>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d1Scores" aria-expanded="true" aria-controls="d1Scores" id="witness4">
						Defense Witness 1 ()
					</button>
				</h2>
			</div>

			<div id="d1Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body defenseWitness">
					<label for="pCx1" class="pCxLabel" id="pCx1Label">Atty Cx ():</label>
					<select class="pCx score" id="pCx1" name="pDx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dDx1" class="dDxLabel" id="dDx1Label">Atty Dx ():</label>
					<select class="dDx score" id="dDx1" name="dCx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pCxComments" id="pCx1Comments"></textarea>
					<textarea class="dDxComments" id="dDx1Comments"></textarea>


					<label for="dWDx1" class="dWDxLabel" id="dWDx1Label">Wit Dx ():</label>
					<select class="dWDx score" id="dWDx1" name="dWDx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWDxComments" id="dWDx1Comments"></textarea>

					<label for="dWCx1" class="dWCxLabel" id="dWCx1Label">Wit Cx ():</label>
					<select class="dWCx score" id="dWCx1" name="dWCx1">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWCxComments" id="dWCx1Comments"></textarea>

				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d2Scores" aria-expanded="true" aria-controls="d2Scores" id="witness5">
						Defense Witness 2 ()
					</button>
				</h2>
			</div>

			<div id="d2Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body defenseWitness">
					<label for="pCx2" class="pCxLabel" id="pCx2Label">Aty Cx ():</label>
					<select class="pCx score" id="pCx2" name="pDx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dDx2" class="dDxLabel" id="dDx2Label">Aty Dx ():</label>
					<select class="dDx score" id="dDx2" name="dCx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pCxComments" id="pCx2Comments"></textarea>
					<textarea class="dDxComments" id="dDx2Comments"></textarea>

					<label for="dWDx2" class="dWDxLabel" id="dWDx2Label">Wit Dx ():</label>
					<select class="dWDx score" id="dWDx2" name="dWDx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWDxComments" id="dWDx2Comments"></textarea>

					<label for="dWCx2" class="dWCxLabel" id="dWCx2Label">Wit Cx ():</label>
					<select class="dWCx score" id="dWCx2" name="dWCx2">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWCxComments" id="dWCx2Comments"></textarea>

				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#d3Scores" aria-expanded="true" aria-controls="d3Scores" id="witness6">
						Defense Witness 3 ()
					</button>
				</h2>
			</div>

			<div id="d3Scores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body defenseWitness">
					<label for="pCx3" class="pCxLabel" id="pCx3Label">Atty Cx ():</label>
					<select class="pCx score" id="pCx3" name="pDx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dDx3" class="dDxLabel" id="dDx3Label">Atty Dx ():</label>
					<select class="dDx score" id="dDx3" name="dCx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="pCxComments" id="pCx3Comments"></textarea>
					<textarea class="dDxComments" id="dDx3Comments"></textarea>

					<label for="dWDx3" class="dWDxLabel" id="dWDx3Label">Wit Dx ():</label>
					<select class="dWDx score" id="dWDx3" name="dWDx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWDxComments" id="dWDx3Comments"></textarea>

					<label for="dWCx3" class="dWCxLabel" id="dWCx3Label">Wit Cx ():</label>
					<select class="dWCx score" id="dWCx3" name="dWCx3">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>

					<textarea class="dWCxComments" id="dWCx3Comments"></textarea>

				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#closingScores" aria-expanded="true" aria-controls="closingScores">
						Closing
					</button>
				</h2>
			</div>

			<div id="closingScores" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
				<div class="card-body speech">
					<label for="pClose" class="pSpeechLabel" id="pCloseAttorney">π Close ():</label>
					<select class="pSpeech score" id="pClose">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<label for="dClose" class="dSpeechLabel" id="dCloseAttorney">∆ Close ():</label>
					<select class="dSpeech score" id="dClose">
						<option value='-1' selected>---</option>
						<option value='0'>0</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						<option value='5'>5</option>
						<option value='6'>6</option>
						<option value='7'>7</option>
						<option value='8'>8</option>
						<option value='9'>9</option>
						<option value='10'>10</option>
					</select>
					<textarea class="pSpeechComments" id="pCloseComments"></textarea>
					<textarea class="dSpeechComments" id="dCloseComments"></textarea>

				</div>
			</div>
		</div>
	</div>


	<div class="card">
		<div class="card-header">
			<h2 class="mb-0">
				<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#individualAwards" aria-expanded="true" aria-controls="individualAwards">
					Individual Awards
				</button>
			</h2>
		</div>

		<div id="individualAwards" class="collapse" aria-labelledby="headingOne" data-parent="#scores">
			<div class="card-body" id="awards">
				<p id="attyLabel">Outstanding Attorneys</p>
				<label for="aty1" class="attySelectLabel" id="atty1Label">Atty 1:</label>
				<select id="aty1">

				</select>
				<label for="aty2" class="attySelectLabel" id="atty2Label">Atty 2:</label>
				<select id="aty2">

				</select>
				<label for="aty3" class="attySelectLabel" id="atty3Label">Atty 3:</label>
				<select id="aty3">

				</select>
				<label for="aty4" class="attySelectLabel" id="atty4Label">Atty 4:</label>
				<select id="aty4">

				</select>
				<p id="witLabel">Outstanding Witnesses</p>
				<label for="wit1" class="witSelectLabel" id="wit1Label">Wit 1:</label>
				<select id="wit1">

				</select>
				<label for="wit2" class="witSelectLabel" id="wit2Label">Wit 2:</label>
				<select id="wit2">

				</select>
				<label for="wit3" class="witSelectLabel" id="wit3Label">Wit 3:</label>
				<select id="wit3">

				</select>
				<label for="wit4" class="witSelectLabel" id="wit4Label">Wit 4:</label>
				<select id="wit4">

				</select>
			</div>
		</div>
	</div>
</div>

<!-- Lock Modal -->
<div id="lockModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" id="lockModalText">
				Locking the ballot will prevent further changes. If you need to modify the ballot, you will have to contact the tournament's tabulation director at. Would you like to lock the ballot?
			</div>
			<div class="modal-footer">
				<button type="button" id="lockButton" class="btn btn-default" data-dismiss="modal">Lock Ballot</button>
			</div>
		</div>
	</div>
</div>

<script>
	let ballotId;
	<?php
	if (isset($_GET["ballot"])) {
		echo "ballotId = '" . $_GET["ballot"] . "'";
	}
	?>
</script>
<script src="editBallot.js"></script>
<script src="config.js"></script>

</html>