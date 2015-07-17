require_once(__DIR__.'/classes/class.jQueryValidator.php');

// Create instance of validator
$jQValidate	=	new jQueryValidator();
// This is just for saving the file. Not required.
$validator = __DIR__.'/temp/validation.js';
	if(!is_file($validator)) {
			ob_start(); ?>function(response) {
						
						var Fetched	=	(response)? JSON.parse(response):{ action: { fail: { err : "unknown" } } };
						
						if(Fetched.action != undefined) {
							
							var DisplaySpot	=	$('#pref_cust');
							
							if(Fetched.action != 'success') {
									var ErrorType	=	Fetched.action.fail.err;
									
									if(ErrorType == 'payment')
										DisplaySpot.html('<div class="ajax_error ered">PAYMENT IS REQUIRED</div><input type="hidden" name="PAY_CNT" value="1" /><input type="hidden" name="PAY_PTY_1" value="3" /><select name="CC_TYPE_1"><option value="VS">Visa</option><option value="MS">MasterCard</option><option value="AE">American Express</option></select>');
									else if(ErrorType == 'cart')
										DisplaySpot.html('<div style="ajax_error ered">CART CAN NOT BE EMPTY</div>');
								}
							else
								DisplaySpot.html('<div style="ajax_error egreen">UPDATE COMPLETE</div>');
							
							return true;
						}
				}
		<?php
			$script		=	ob_get_contents();
			ob_end_clean();
			
			$txt_req	=	"This field is required";
			$jQValidate->UseForm(array("name"=>"create_dist","debug"=>false));
			
			$jQValidate->SetAttr(	"PHN1,BILL_PHONE_1",
									array("required"=>true,"minlength"=>10,"digits"=>true),
									array("required"=>$txt_req,"minlength"=>"Requires more characters","digits"=>"Must be a valid phone number")
								);
									
			$jQValidate->SetAttr(	"ZIP,BILL_ZIP_1",
									array("required"=>true,"minlength"=>5),
									array("required"=>$txt_req,"minlength"=>"Requires more characters")
								);
									
			$jQValidate->SetAttr(	"name_f,name_l,ADDR1,CITY,BILL_ADDR1_1,BILL_CITY_1,BILL_FIRST_NAME_1,BILL_LAST_NAME_1",
									array("required"=>true,"minlength"=>2),
									array("required"=>$txt_req,"minlength"=>"Requires more characters")
								);
								
			$jQValidate->SetAttr(	"EMAIL",
									array("required"=>true,"email"=>true),
									array("required"=>$txt_req,"email"=>"This is not a valid email address")
								);

			// submitHandler
			$submission["event"]			=	"form";
			$submission["ajax"]["url"]		=	"/client_assets/apps/infotrax/core.processor/calculate.pricing.php";
			$submission["ajax"]["data"]		=	'$(form).serialize()';
			$submission["ajax"]["type"]		=	"post";
			$submission["ajax"]["success"]	=	'function(response) {
				
				$("#pref_cust").html(response);
			}';
			// Creation
			$jQValidate->SubmitHandler($submission);
			$jQValidate->InvalidHandler(array("script"=>file_get_contents(__DIR__."/../../jsprefs/invalid.js")),jQueryValidator::ERR_INVALID,jQueryValidator::ERR_ARGS);
			
			echo $jQValidate	->AddLibraries()
								->Compile(array("add_tags"=>true,"add_ready"=>true,"validate_hidden"=>true));
			
		//	$jQValidate->SaveFile(CLIENT_DIR.'/apps/infotrax/js/validate.js');
		}
	else
		echo $jQValidate	->AddLibraries(array("/client_assets/apps/infotrax/js/validate.js"))
							->Write();
