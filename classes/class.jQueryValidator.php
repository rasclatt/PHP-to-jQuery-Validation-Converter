<?php
	class	jQueryValidator
		{
			protected	$jObject;
			protected	$libraries;
			protected	$content;
			protected	$validator;
			protected	$event;
			protected	$debug;
			protected	$library_def;
			
			const		ERR_ARGS	=	"
					var ThisForm	=	$(this).serialize();
					var	KeyPairs	=	ThisForm.split('&');
					var result		=	{};
					
					for (var i = 0; i < KeyPairs.length; i++) {
							var	splitval		=	KeyPairs[i].split('=');
	
							result[decodeURIComponent(splitval[0])]	=	decodeURIComponent(splitval[1] || '');
					}
					
					console.log(result);";
			
			const	ERR_INVALID		=	"
					if(typeof this.errorList !== 'undefined')
						var useErrorList	=	this.errorList;
					else if(typeof errorList !== 'undefined')
						var useErrorList	=	errorList;
					else if(typeof validator.errorList !== 'undefined')
						var useErrorList	=	validator.errorList;
					
					if(typeof useErrorList == 'undefined')
						return;
					
					var error_count		=	useErrorList.length;
					
					this.Errors	=	[];
					
					for (var i = 0; i < error_count; i++) {
						this.Errors[i]	=	{ name: useErrorList[i].element.name, method: useErrorList[i].method };
					}
					
					console.log(error_count);
					console.log(this.Errors);";
			
			public	function __construct()
				{
					$this->jObject['rule']			=	array();
					$this->jObject['msg']			=	array();
					$this->jObject['submitHandler']	=	"";
					$this->library_def			=	'<script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.14.0/jquery.validate.js"></script>';
					
					$this->uselibs					=	false;
					$this->validator				=	"validator";
					$this->event					=	"form";
					$this->debug					=	true;
				}
			
			public	function SetAttr($name = false, $rule = false, $message = false)
				{
					// Set default as required if no rules supplied
					$rule	=	($rule != false)? $rule : array("required"=>true);
					
					if(strpos($name,",") !== false) {
							$same		=	explode(",",$name);
							foreach($same as $nameval) {
									$this->SetAttr($nameval,$rule,$message);
								}
							
							return $this;
						}
					
					if(is_array($rule) && !empty($rule)) {
							$multi		=	(count($rule) > 1)? true:false;
							$this->jObject['rule'][$name]	=	 "\t\t$name: { ";
							
							foreach($rule as $fname => $rInst) {
									if(is_bool($rInst))
										$rInst	=	($rInst == true)? 'true':'false';
									elseif(!is_numeric($rInst))
										$rInst	=	"'$rInst'";
									elseif(is_numeric($rInst) && strpos($rInst,'.') !== false)
										$rInst	=	"'$rInst'";
									
									$this->jObject['rule'][$name]		.=	PHP_EOL."\t\t".$fname.": ".$rInst.",";
	
									if(!empty($message[$fname])) {
											if(!isset($msg_save['msg'][$name]))
												$msg_save['msg'][$name]	=	PHP_EOL."\t\t\t\t".$fname.": '".$message[$fname]."',";
											else
												$msg_save['msg'][$name]	.=	PHP_EOL."\t\t\t\t".$fname.": '".$message[$fname]."',";
										}
								}
								
							if(isset($this->jObject['rule'][$name]))
								$this->jObject['rule'][$name]	=	rtrim($this->jObject['rule'][$name],",");
							
							$this->jObject['rule'][$name]	.=	PHP_EOL."\t\t\t\t} ";
								
							if(isset($msg_save['msg'][$name])) {
									$this->jObject['msg'][$name]	=	"\t\t$name: { ";
									$this->jObject['msg'][$name]	.=	rtrim($msg_save['msg'][$name],",");
									$this->jObject['msg'][$name]	.=	PHP_EOL."\t\t\t\t} ";
								}
						}
						
					return $this;
				}
			
			public	function UseForm($settings = false)
				{
					$find['id']		=	(!empty($settings['id']))? $settings['id']:false;
					$find['name']	=	(!empty($settings['name']))? $settings['name']:false;
					$find['class']	=	(!empty($settings['class']))? $settings['class']:false;
					$this->debug	=	(!empty($settings['debug']) && $settings['debug'] == true)? $settings['debug']:false;
					
					$debugger		=	'
// Count ids from SO->http://stackoverflow.com/questions/482763/
$(\'[id]\').each(function(){
	var ids = $(\'[id="\'+this.id+\'"]\');
  
	if(ids.length > 1 && ids[0] == this) {
		if(this.id == "~[value]~") {
			alert(\'ERROR: 100. You already have an element name as "\'+this.id+\'." Your form will not validate properly\');
    		return false;
		}
	}
});
';
					
					foreach($find as $use => $value) {
							if($use == 'id' && $value != false) {
									$script	=	'
var	ThisForm	=	$("#'.$value.'");';
							
									if($this->debug === true)
											$script	.=	preg_replace('/(~\[value\]~)/',$value,$debugger);
										
									break;
								}
							elseif($use == 'name' && $value != false) {
									$script	=	'
var ThisForm	=	$("form[name=\''.$value.'\']");';

									if($this->debug === true)
											$script	.=	preg_replace('/(~\[value\]~)/',$value,str_replace('[id','[name',$debugger));
									break;
								}
							elseif($use == 'class' && $value != false) {
									$script	=	'
var ThisForm	=	$(".'.$value.'");';

									if($this->debug === true)
											$script	.=	preg_replace('/(~\[value\]~)/',$value,str_replace('[id','[class',$debugger));
									break;
								}
							else {
									$script	=	'

// This relies on being wrapped in this block						
var ThisForm	=	$("body").find("form");';
								}
						}
					
					$this->jObject	=	array();
	
					if($this->debug === true)
						$script			.=	PHP_EOL.'if(ThisForm.length > 1)
	alert("There are too many forms in the body to validate without specifying. Please supply a class, id, or name value that correlates to your form.");'.PHP_EOL;
					$this->jObject['fconstruct']	=	$script.'ThisForm.validate({'.PHP_EOL;
					$this->jObject['econstruct']	=	'});';
					return $this;
				}
			
			public	function SubmitHandler()
				{
					$settings	=	array();
					$addscr		=	"";
					$args		=	func_num_args();
					
					if($args > 0) {
							$get_args	=	func_get_args();
							
							for($i = 0; $i < $args; $i++) {
									if(is_array($get_args[$i]))
										$settings	=	$get_args[$i];
									else
										$addscr		.=	$get_args[$i];
								}
						}
					
					$event	=	(!empty($settings['event']))? $settings['event']:"form";
					$script	=	(!empty($settings['script']))? $settings['script']:"";
					$ajax	=	(!empty($settings['ajax']))? $settings['ajax']:false;
					
					if(!empty($settings)) {
						ob_start(); ?>submitHandler: function (<?php echo $event; ?>) {
		<?php echo $addscr; ?>
		// Scripts
		<?php echo $script; ?>
		<?php
			if(is_array($ajax) && !empty($ajax))
				$this->Ajax($ajax);
		?>
		}
						<?php 
						$data	=	ob_get_contents();
						ob_end_clean();
						
						}
					
					$this->jObject['submitHandler']	=	trim($data);
					
					return $this;
				}
			
			public	function InvalidHandler()
				{
					$settings	=	array();
					$addscr		=	"";
					$args		=	func_num_args();
					
					if($args > 0) {
							$get_args	=	func_get_args();
							
							for($i = 0; $i < $args; $i++) {
									if(is_array($get_args[$i]))
										$settings	=	$get_args[$i];
									else
										$addscr		.=	$get_args[$i];
								}
						}
						
					$event				=	(!empty($settings['event']))? $settings['event']:"form";
					$this->validator	=	(!empty($settings['validator']))? $settings['validator']:$this->validator;
					$script				=	(!empty($settings['script']))? $settings['script']:"";
					
					ob_start(); ?>invalidHandler: function (<?php echo $event; ?>,<?php echo $this->validator; ?>) {
		// Validator form errors
		var errors = <?php echo $this->validator; ?>.numberOfInvalids();
		
		<?php echo $addscr; ?>
		// Error Scripts
		<?php echo $script; ?>
		}
						<?php 
					$data	=	ob_get_contents();
					ob_end_clean();
					
					$this->jObject['invalidHandler']	=	trim($data);
					
					return $this;
				}
			
			public	function ShowErrors()
				{
					$settings	=	array();
					$addscr		=	"";
					$args		=	func_num_args();
					
					if($args > 0) {
							$get_args	=	func_get_args();
							
							for($i = 0; $i < $args; $i++) {
									if(is_array($get_args[$i]))
										$settings	=	$get_args[$i];
									else
										$addscr		.=	$get_args[$i];
								}
						}
		
					$default	=	(!empty($settings['default']) && $settings['default'] == true)? $settings['default']:false;
					$show		=	(!empty($settings['show']) && $settings['show'] == true)? "this.defaultShowErrors();":"";
					$script		=	(!empty($settings['script']))? $settings['script']:"";
					
					ob_start(); ?>showErrors: function(errorMap, errorList) {
			// Validator form errors
			var error_count = this.numberOfInvalids();
			// Error Scripts
			<?php echo $addscr; ?>
			<?php echo $script; ?>
			<?php echo $show; ?>
		}
						<?php 
					$data	=	ob_get_contents();
					ob_end_clean();
						
					$this->jObject['showErrors']	=	trim($data);
					
					return $this;
				}
			
			public	function AddHandler($script = false)
				{
					$handler	=	(!empty($settings['handler']))? $settings['handler']:false;
					$args		=	(!empty($settings['args']))? $settings['args']:false;
					$args		=	(!empty($settings['script']))? $settings['script']:"";
					
					if($handler == false)
						return $this;
					
					ob_start(); echo $handler; ?>: function(<?php echo (is_array($args))? implode(",",$args):$args; ?>) {
					
			// Scripts
			<?php echo $script; ?>
		}
					<?php 
					$data	=	ob_get_contents();
					ob_end_clean();
					
					$this->jObject['AddHandler'][$handler]	=	trim($data);
					
					return $this;
				}
				
			public	function AddLibraries($libs = false)
				{
					$this->uselibs		=	true;
					
					if(is_array($libs) && !empty($libs)) {
							foreach($libs as $link) {
									$this->libraries[]	=	'<script type="text/javascript" src="'.$link.'"></script>';
								}
						}
						
					return $this;
				}
			
			public	function Compile($settings = false)
				{
					$tags	=	(!empty($settings['add_tags']) && $settings['add_tags'] == true)? true : false;
					$lib	=	(!empty($settings['use_lib']))? $settings['use_lib'] : false;
					$ssl	=	(!empty($settings['use_ssl']) && $settings['use_ssl'] == true)? "s" : "";
					$compat	=	(!empty($settings['is_compatable']) && $settings['is_compatable'] == true)? true : false;
					$ready	=	(!empty($settings['add_ready']))? true : false;
					$script	=	(!empty($settings['script']))? $settings['script']: false;
					$hidden	=	(!empty($settings['validate_hidden']))? $settings['validate_hidden']: false;
					$append	=	(!empty($settings['append']))? $settings['append']: "";
					
					$front	=	array();
					$back	=	array();
					$final	=	array();
					
					if($lib != false)
						$front[]	=	($ssl == true)? str_replace("http://","https://",$lib):$lib;
					
					if($this->uselibs) {
							$this->libraries[]	=	$this->library_def;
							$front[]	=	implode(PHP_EOL,$this->libraries).PHP_EOL;
						}
					
					if($tags)
						$front[]	=	"<script>".PHP_EOL;
						
					if($compat)
						$final[]	=	"//<![CDATA[".PHP_EOL;
					
					if($hidden)
						$final[]	=	"$.validator.setDefaults({ ignore: [] });".PHP_EOL;
					
					if($ready)
						$final[]	=	"$().ready(function() {".PHP_EOL;
						
					if(!$script)
						$final[]	=	"\t".$script.PHP_EOL;
					
					if((is_array($this->jObject) && !empty($this->jObject)) && isset($this->jObject['fconstruct'])) {
							
							$creturn	=	",".PHP_EOL;
							$final[]	=	PHP_EOL.$this->jObject['fconstruct'];
							
							if(isset($this->jObject['rule']) && !empty($this->jObject['rule'])) {
									$final[]	=	"\t"."rules: {".PHP_EOL;
									$final[]	=	implode($creturn,$this->jObject['rule']);
									$final[]	=	"\t\t"."}";
								}
							
							if(isset($this->jObject['msg']) && !empty($this->jObject['msg'])) {
									$final[]	=	$creturn."\t messages: {".PHP_EOL;
									$final[]	=	implode($creturn,$this->jObject['msg']).PHP_EOL;
									$final[]	=	"\t\t"."}";
								}
							
							$handler['submit']	=	(isset($this->jObject['submitHandler']) && !empty($this->jObject['submitHandler']))? true:false;
							$handler['invalid']	=	(isset($this->jObject['invalidHandler']) && !empty($this->jObject['invalidHandler']))? true:false;
							$handler['errors']	=	(isset($this->jObject['showErrors']) && !empty($this->jObject['showErrors']))? true:false;
							$handler['custom']	=	(isset($this->jObject['AddHandler']) && !empty($this->jObject['AddHandler']))? true:false;
							
							$formathandler = function($value) {
									return "\t".$value.PHP_EOL;
								};
							
							$final[]	=	($handler['submit'])? $creturn:PHP_EOL;
							
							if($handler['submit'])
								$final[]	=	$formathandler($this->jObject['submitHandler']);
							
							$final[]	=	($handler['invalid'])? $creturn:PHP_EOL;
							if($handler['invalid'])
								$final[]	=	$formathandler($this->jObject['invalidHandler']);
							
							$final[]	=	($handler['errors'])? $creturn:PHP_EOL;
							if($handler['errors'])
								$final[]	=	$formathandler($this->jObject['showErrors']);
							
							$final[]	=	($handler['custom'])? $creturn:PHP_EOL;
							if($handler['custom'])
								$final[]	=	$formathandler(implode($creturn."\t",$this->jObject['AddHandler']));
							
							$final[]	=	$this->jObject['econstruct'];
						}
					
					if($ready)
						$final[]	=	PHP_EOL."});".PHP_EOL;
					
					$final[]		=	$append;
					
					if($compat)
						$final[]	=	"//]]>".PHP_EOL;
						
					if($tags)
						$back[]	=	PHP_EOL."</script>".PHP_EOL;
					
					$this->content	=	(!empty($final))? implode("",$final):false;
					
					return implode("",$front).$this->content.implode("",$back);
				}
			
			public	function SaveFile($saveto = false)
				{
					$this->content	=	preg_replace('!<script [.*]{1,}></script>!',"",$this->content);
					$write	=	new WriteToFile();
					$write	->AddInput(array("content"=>$this->content,"save_to"=>$saveto))
							->SaveDocument();
				}
			
			public	function Write()
				{
					if($this->uselibs && is_array($this->libraries))
						echo implode(PHP_EOL,$this->libraries).PHP_EOL;
				}
			
			public	function Ajax($settings = false)
				{
					$event		=	(isset($this->event) && !empty($this->event))? $this->event:"form";
					$use_url	=	(!empty($settings['url']))? $settings['url']:false;
					$use_data	=	(!empty($settings['data']))? $settings['data']:'$('.$event.').serialize()';
					$use_type	=	(!empty($settings['type']))? $settings['type']:'post';
					$use_succ	=	(!empty($settings['success']))? $settings['success']:"function(resonse) { console.log(response); }";
					$use_opts	=	(!empty($settings['other']))? $settings['other']:""; ?>
					
					$.ajax({
							<?php if($use_url != false) { ?>url: '<?php echo $use_url; ?>',<?php echo PHP_EOL; } ?>
							<?php if($use_data != false) { ?>data: <?php echo $use_data; ?>,<?php echo PHP_EOL; } ?>
							<?php if($use_type != false) { ?>type: '<?php echo $use_type; ?>',<?php echo PHP_EOL; } ?>
							<?php if(is_array($use_opts)) { foreach($use_opts as $obj => $val) { echo $obj; ?>: '<?php echo $val; ?>',<?php echo PHP_EOL; } } ?>
							<?php if($use_succ != false) { ?>success: <?php echo $use_succ.PHP_EOL; } ?>
					});
					
					return false;
					<?php
					return $this;
				}
		}
?>
