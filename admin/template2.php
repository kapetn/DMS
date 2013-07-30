<?php 
session_start(); 
$session_id = session_id();

include_once("../classes/allinclude.php");
//GET variables

//Variables
$testmode = $_GET["testmode"];
if ($testmode==1){
	echo "GET superblobal array <br>";
	var_dump($_GET);
	echo "<br><br>";
	echo "POST superblobal array <br>";
	var_dump($_POST);
	echo "<br><br>";
	echo "SESSION superblobal array <br>";
	var_dump($_SESSION);
	echo "<br><br>";
	echo "SERVER superblobal array <br>";
	var_dump($_SERVER);
	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>HOTEL RMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-darkness/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/cupertino/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/humanity/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/pepper-grinder/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/overcast/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../css/admin2.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
  <script>
  $(document).ready(function() {
    $("button").button();
  });
  </script>


<script>
	$(function() {
		$( "#tabs" ).tabs({ selected: 0 });
	});
</script>

<script>
  $(document).ready(function() {
    $("#accordion").accordion();
  });
</script>

<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true
		});
	});
</script>	

<script>
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
		$( "#dialog-modal" ).dialog({
			height: 140,
			modal: true
		});
	});
</script>
	
	
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#D9D9D9;" >
<table cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
	<td class="left_col" valign="top" align="left" height="100%">
		<h1 style="color:#b9b9b9; font-size:38px;">HOTEL RMS</h1>
		<br />
		Hello User, you have 3 Messages
		<br /><br />
		<a href="#">Sign out</a>
		
		<br /><br />
	<div id="accordion">
	<h1><a href="#">Hotels</a></h1>
	<div>
		<a href="">Add a Hotel</a>
		<br />
		<a href="">View all</a>
		<br />
	</div>
	<h3><a href="#">Rooms</a></h3>
	<div>
		<a href="">Add a Room</a>
		<br />
		<a href="">View all</a>
		<br />
	</div>
	<h3><a href="#">Customers</a></h3>
	<div>
		<a href="">Add a Customer</a>
		<br />
		<a href="">View all</a>
		<br />
	</div>
	<h3><a href="#">Users</a></h3>
	<div>
		<a href="">Add a User</a>
		<br />
		<a href="">View all</a>
		<br />
	</div>
</div>
		
	</td>
	<td class="right_col" width="100%" valign="top" align="left">
		<h1>Welcome User</h2>
		<!--<br />
		<button>Button label</button>
		-->
		<br />		
		<br />		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-0">Records List</a></li>
				<li><a href="#tabs-1">Web Form</a></li>
				<li><a href="#tabs-2">Proin dolor</a></li>
				<li><a href="#tabs-3">Aenean lacinia</a></li>
			</ul>
			<div id="tabs-0">
					<div class="sidebarBox supportPlanTable">
						<table id="supportPlans">
							<thead>
								<tr>
									<th class="name">ID</th>
									<th class="name">Hotel</th>
									<th class="supportHours">Owner</th>
									<th class="responseHours">Address</th>
									<th class="responseHours">Management</th>
								</tr>
							</thead>
							<tbody>
								<tr class="alt-row" onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" data-id="1" tabindex="0">
									<td>1</td>
									<td>Hilton 5 stars</td>
									<td>Hilton Family</td>
									<td>Vasileos Georgiou Avenue 102, Athens, Greece</td>
									<td>
										<a href="#"><img src="../images/pencil.png"></a>
										<a href="#"><img src="../images/edit.png"></a>
										<a href="#"><img src="../images/cross.png"></a>
									</td>
								</tr>
								<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" data-id="2" tabindex="0">
									<td>2</td>
									<td>Hilton 5 stars</td>
									<td>Hilton Family</td>
									<td>Vasileos Georgiou Avenue 102, Athens, Greece</td>
									<td>
										<a href="#"><img src="../images/pencil.png"></a>
										<a href="#"><img src="../images/edit.png"></a>
										<a href="#"><img src="../images/cross.png"></a>
									</td>
								</tr>
								<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" class="alt-row" data-id="3" tabindex="0">
									<td>3</td>
									<td>Hilton 5 stars</td>
									<td>Hilton Family</td>
									<td>Vasileos Georgiou Avenue 102, Athens, Greece</td>
									<td>
										<a href="#"><img src="../images/pencil.png"></a>
										<a href="#"><img src="../images/edit.png"></a>
										<a href="#"><img src="../images/cross.png"></a>
									</td>
								</tr>
								<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" data-id="4" tabindex="0">
									<td>4</td>
									<td>Hilton 5 stars</td>
									<td>Hilton Family</td>
									<td>Vasileos Georgiou Avenue 102, Athens, Greece</td>
									<td>
										<a href="#"><img src="../images/pencil.png"></a>
										<a href="#"><img src="../images/edit.png"></a>
										<a href="#"><img src="../images/cross.png"></a>
									</td>	
								</tr>
								<tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'" class="alt-row" data-id="5" tabindex="0">
									<td>5</td>
									<td>Hilton 5 stars</td>
									<td>Hilton Family</td>
									<td>Vasileos Georgiou Avenue 102, Athens, Greece</td>
									<td>
										<a href="#"><img src="../images/pencil.png"></a>
										<a href="#"><img src="../images/edit.png"></a>
										<a href="#"><img src="../images/cross.png"></a>
									</td>
								</tr>
										</tbody>
						</table>
					</div>
			</div>
			<div id="tabs-1">
				<form name="" action="" method="">
					<table cellpadding="2" cellspacing="2" width="100%">
					<tr>
						<td width="160">
							<label>First Name:</label>
						</td>
						<td>
							<input type="text" name="first_name" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Last Name:</label>
						</td>
						<td>
							<input type="text" name="first_name" />
						</td>
					</tr>
					<tr>		
						<td>
							<label>Address:</label>
						</td>
						<td>
							<input type="text" name="first_name" />
						</td>
					</tr>	
					<tr>		
						<td>
							<label>City:</label>
						</td>
						<td>
							<select style="margin-left:5px;" class="ui-state-default ui-corner-all" name="city">
								<option value="">Athens</option>
								<option value="">Thessaloniki</option>
								<option value="">Larisa</option>
								<option value="">Patra</option>
							</select>
						</td>
					</tr>	
					<tr>		
						<td>
							<label>Receive Newsletter?</label>
						</td>
						<td>
							<input type="checkbox" name="newsletter" value="Yes" />
						</td>
					</tr>
					<tr>		
						<td>
							<label>Gender:</label>
						</td>
						<td>
							<input type="radio" name="gender" value="male" /> Male
							<input type="radio" name="gender" value="female" /> Female
						</td>
					</tr>						
					<tr>		
						<td>
							<label>Date: </label>
						</td>
						<td>
							<input type="text" id="datepicker">		
						</td>
					</tr>	
					
					<tr>		
						<td>
							<label>Description: </label>
						</td>
						<td>
							<textarea style="margin-left:5px;" rows="10" cols="45">The cat was playing in the garden.</textarea>
						</td>
					</tr>	
										
					</table>
					<br />
					<input class="ui-button ui-widget ui-state-default ui-corner-all" role="button" type="submit" name="btn_submit" value="Save" />	
					<input class="ui-button ui-widget ui-state-default ui-corner-all" role="button" type="reset" name="btn_reset" value="Reset" />	
				</form>	
			</div>
			<div id="tabs-2">
				<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
			</div>
			<div id="tabs-3">
				<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
				<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
			</div>
		</div>

		<br />
		<!--<p>Date: <input type="text" id="datepicker"></p>-->
			
		<br />	
		<!--<div id="dialog-modal" title="Basic modal dialog">
			<p>Adding the modal overlay screen makes the dialog look more prominent because it dims out the page content.</p>
		</div>-->
		<!-- Sample page content to illustrate the layering of the dialog -->
			
		
	</td>
</tr>
<tr>
	<td valign="middle" align="center" colspan="2" style="height:45px; color:#000; font-weight:bold;">Hotel RMS - developed by <a style="text-decoration:none; color:#000;" href="http://www.brainbox.gr" target="_blank">Brainbox</a> &copy;</td>
</tr>
</table>	
</body>
</html>