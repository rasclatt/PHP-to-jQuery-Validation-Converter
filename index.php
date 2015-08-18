<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
<?php
// Include class
require_once(__DIR__.'/classes/class.jQueryValidator.php');
// Create instance of validator
$jQValidate	=	new jQueryValidator();
// This is just for saving the file. Not required.
$validator = __DIR__.'/temp/validation.js';
if(!is_file($validator)) {
		// General message
		$txt_req	=	"This field is required";
		$jQValidate->UseForm(array("name"=>"create_user","debug"=>false));
		
		$jQValidate->SetAttr(	"phone,cel",
								array("required"=>true,"minlength"=>10,"digits"=>true),
								array("required"=>$txt_req,"minlength"=>"Requires more characters","digits"=>"Must be a valid phone number")
							);
								
		$jQValidate->SetAttr(	"zip",
								array("required"=>true,"minlength"=>5),
								array("required"=>$txt_req,"minlength"=>"Requires more characters")
							);
								
		$jQValidate->SetAttr(	"name_f,name_l,address,city,country,state",
								array("required"=>true,"minlength"=>2),
								array("required"=>$txt_req,"minlength"=>"Requires more characters")
							);
							
		$jQValidate->SetAttr(	"email",
								array("required"=>true,"email"=>true),
								array("required"=>$txt_req,"email"=>"This is not a valid email address")
							);
	
		// submitHandler
		$submission["event"]			=	"form";
		$submission["ajax"]["url"]		=	"test.php";
		$submission["ajax"]["data"]		=	'$(form).serialize()';
		$submission["ajax"]["type"]		=	"post";
		$submission["ajax"]["success"]	=	'function(response) {
			
			$("#loadtext").html(response);
		}';
		// Creation
		$jQValidate->SubmitHandler($submission);
		$jQValidate->InvalidHandler(array("script"=>"alert('This is an alert.');"),jQueryValidator::ERR_INVALID,jQueryValidator::ERR_ARGS);
		
		echo $jQValidate	->AddLibraries(array('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js','https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js'))
							->Compile(array("add_tags"=>true,"add_ready"=>true,"validate_hidden"=>true));
		
	//	$jQValidate->SaveFile(array("save_dir"=>__DIR__.'/js/',"save_name"=>'validate.js'));
	}
else
	echo $jQValidate	->AddLibraries(array("/client_assets/apps/infotrax/js/validate.js"))
						->Write();
?>
<style>
* {
	font-family: Arial, Helvetica, sans-serif;
	margin: 0;
}
label	{
	float: left;
	clear: both;
	font-size: 14px;
}
label input	{
	font-size: 20px;
	border: 1px solid #CCC;
	box-shadow: inset 0 0 5px #CCC;
	padding: 10px 20px;
	clear: both;
	float: left;
	color: #333;
	margin-top: 10px;
}
label div	{
	clear: both;
	float: left;
	margin-top: 15px;
}
#wrap	{
	text-align: center;
}
div.container	{
	max-width: 1000px;
	width: 100%;
	display: inline-block;
	margin: 0 auto;
	text-align: left;
	padding: 20px;
	background-color: #EBEBEB;
}
.error	{
	color: red;
	text-shadow: 1px 1px 2px #FFF;
}
body table td	{
	padding: 10px;
	vertical-align: top;
}
#submitter input	{
	background-color: #333;
	color: #CCC;
	text-shadow: 1px 1px 3px #000;
	font-size: 24px;
	border: 2px solid #666;
	padding: 10px 15px;
	cursor: pointer;
	display: inline-block;
}
#submitter	{
	display: inline-block;
}
#submitter:hover input	{
	background-color: #888;
	color: #FFF;
}
</style>
</head>

<body>
<div id="wrap">
	<div class="container">
		<form id="CreateUser" name="create_user">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					<label><div>First Name</div>
					<input type="text" name="name_f" />
					</label>
					<label><div>Last Name</div>
					<input type="text" name="name_l" />
					</label>
					<label><div>Email</div>
					<input type="text" name="email" />
					</label>
					<label><div>Phone</div>
					<input type="text" name="phone" />
					</label>
					<label><div>Cel</div>
					<input type="text" name="cel" />
					</label>
				</td>
				<td>	
					<label><div>Address</div>
					<input type="text" name="address" />
					</label>
					<label><div>Country</div>
					<input type="text" name="country" />
					</label>
					<label><div>State/Prov</div>
					<input type="text" name="state" />
					</label>
					<label><div>ZIP/Postal</div>
					<input type="text" name="zip" />
					</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" id="submitter">
					<input type="submit" value="SUBMIT" />
				</td>
			</tr>
		</table>
		</form>
	</div>
	<div class="container">
		<h1>AJAX</h1>
		<div id="loadtext">Test</div>
	</div>
</div>
</body>
</html>
