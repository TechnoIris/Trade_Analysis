<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="dtsel.js"></script>
	<link rel="stylesheet" href="./style.css">
	<link href="https://fonts.googleapis.com/css?family=Monoton" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet">
	<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
	<!-- <link rel="stylesheet" href="https://uicdn.toast.com/chart/latest/toastui-chart.min.css" /> -->
	<!-- <script src="https://uicdn.toast.com/chart/latest/toastui-chart.min.js"></script> -->
	<!-- <link rel="stylesheet" href="jquery-toastchart.css" type="text/css" /> -->
	<!-- <script type="text/javascript" data-main="jquery-toastchart" src="http://requirejs.org/docs/release/2.1.1/minified/require.js"></script> -->
</head>
<body>
	<div class="header">
		<h1>Joe's &nbsp;&nbsp;Stock</h1>
	</div>
	<div class="container">
		<div class="leftdiv">
			<div class="upload_div">
	     <form id="form" method="POST" action="upload.php" enctype="multipart/form-data">
	       <!-- <div> -->
	         <input id="uploadbtn" type="file" name="uploadedFile" hidden/>
	         <label for="uploadbtn">Upload CSV</label>
				 <!-- </div> -->
	       <button class="button" type="submit" value="Upload" style="vertical-align:middle">
	   			<span>submit</span>
	   		</button>
	     </form>
	 		<div id="message"></div>
	   </div>
     <hr>
	 		<div id="checkStock">
	 			<form id="stockval" method="POST" action="findStock.php" enctype="multipart/form-data">

	 			<span>find stock of</span>&nbsp;&nbsp;

				<select name="stocks" id="stocks" class="lists">
	 				<option value="None">None</option>
	 			</select>

	 			<input id="from" name="dateTimePickerFrom" placeholder="FROM" >
	 			<input id="to" name="dateTimePickerTo" placeholder="TO" >

 				<button id="srchbtn" name="searchbtn" type="submit">Search</button>
			</form>
	 	</div>
		<div id="output"></div>
		<hr>
		<div class="chartContainer">
		</div>
		</div>
		<div class="rightdiv">
      <div id="maths">
        <span>MOT:&nbsp;&nbsp;</span>
        </span id="profit"></span>
        <!-- &nbsp;&nbsp; -->
        <hr>
        <span>Mean Cost:&nbsp;&nbsp;</span>
        <!-- &nbsp;&nbsp; -->
        <span id="mean"></span>
        <hr>
        <span>SD:&nbsp;&nbsp;</span>
        <span id="sd"></span>
      </div>
      <div id="headin">
        <span>price</span><hr>
        <span>action</span><hr>
        <span>shares</span>
      </div>
      <div id="tradeview">
      </div>
      <!-- put here trade kanban, standard dev, mean stack -->
		</div>
	</div>
</body>
</html>
 <script src="scri.js" type="text/javascript"></script>
