# PHP to jQuery - Easy Validation Class
**PHPtojQuery-EasyValidation**

>This class creates (and saves if required) to a jQuery validation script. The class accepts array values to create the jQuery parameters. When complete, you can save your file to disk using the `SaveFile()` method or run it as is via PHP.

**Example of use:**

`    $jQValidate	=	new jQueryValidator();
    
    // Find form using the "name" attribute
    $jQValidate->UseForm(array("name"=>"create_user","debug"=>false));
    
    // Validate mulitple same-type fields. Let php write the validation code for you
    $jQValidate->SetAttr(	"name_f,name_l,address,city,country,state",
    						// Create the validation rules
							array("required"=>true,"minlength"=>2),
							// Create the validation message(s)
							array("required"=>'This is a required field!',"minlength"=>"Requires more characters")
						);
							
		// Create a submitHandler to handle successful submissions.
		$submission["event"]			=	"form";
		$submission["ajax"]["url"]		=	"test.php";
		$submission["ajax"]["data"]		=	'$(form).serialize()';
		$submission["ajax"]["type"]		=	"post";
		$submission["ajax"]["success"]	=	'function(response) {
			
			$("#loadtext").html(response);
		}';
		
		// Create submitHandler
		$jQValidate->SubmitHandler($submission);
		
		// You can also add jQuery library links
		echo $jQValidate	->AddLibraries(array('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js','https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js'))
		          			// Compile with or without <script> wrappers
		          			->Compile(array("add_tags"=>true,"add_ready"=>true,"validate_hidden"=>true));

		// Optional save to disk
		$jQValidate->SaveFile('/js/myjavascript.js');
`

**GIVES YOU:**

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script>
    $.validator.setDefaults({ ignore: [] });
    $().ready(function() {
	

    var ThisForm	=	$("form[name='create_user']");ThisForm.validate({
	rules: {
		name_f: { 
					required: true,
					minlength: 2
				} ,
		name_l: { 
					required: true,
					minlength: 2
				} ,
		address: { 
					required: true,
					minlength: 2
				} ,
		city: { 
					required: true,
					minlength: 2
				} ,
		country: { 
					required: true,
					minlength: 2
				} ,
		state: { 
					required: true,
					minlength: 2
				} 		},
	 messages: {
		name_f: { 
					minlength: 'Requires more characters'
				} ,
		name_l: { 
					minlength: 'Requires more characters'
				} ,
		address: { 
					minlength: 'Requires more characters'
				} ,
		city: { 
					minlength: 'Requires more characters'
				} ,
		country: { 
					minlength: 'Requires more characters'
				} ,
		state: { 
					minlength: 'Requires more characters'
				} 
		},
	submitHandler: function (form) {
				// Scripts
									
					$.ajax({
							url: 'test.php',
							data: $(form).serialize(),
							type: 'post',
							success: function(response) {

        $("#loadtext").html(response);
    }
					});
					
					return false;
							}



        });
    });

    </script>
