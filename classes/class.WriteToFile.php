<?php
	class WriteToFile
		{
			protected	$data;
			
			public	function __construct()
				{
					// Include the file writing function
					include_once(__DIR__.'/../functions/function.write_file.php');
				}
			
			public	function AddInput($payload =  array())
				{
					if(!empty($payload)) {
							if(!empty($payload['content']) && !empty($payload['save_to'])) {
									$this->data['content'][]	=	$payload['content'];
									$this->data['save_name'][]	=	$payload['save_name'];
									$this->data['save_dir'][]	=	$payload['save_dir'];
									$this->data['type'][]		=	(!empty($payload['type']))? $payload['type']:"a";
								}
						}
						
					return $this;
				}
			
			public	function SaveDocument()
				{
					if(isset($this->data) && !empty($this->data)) {
							foreach($this->data['content'] as $key => $value) {
									write_file(array("content"=>$value,
											"type"=>$this->data['type'][$key],
											"save_dir"=>$this->data['save_dir'][$key],
											"save_name"=>$this->data['save_name'][$key]
											));
								}
						}
						
					return $this;
				}
		}
?>
