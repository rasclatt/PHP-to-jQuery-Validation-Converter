<?php
	class WriteToFile
		{
			protected	$data;
			
			public	function __construct()
				{
					
				}
			
			public	function AddInput($payload =  array())
				{
					if(!empty($payload)) {
							if(!empty($payload['content']) && !empty($payload['save_to'])) {
									$this->data['content'][]	=	$payload['content'];
									$this->data['save_to'][]	=	$payload['save_to'];
									$this->data['type'][]		=	(!empty($payload['type']))? $payload['type']:"a";
								}
						}
						
					return $this;
				}
			
			public	function SaveDocument()
				{
					if(isset($this->data) && !empty($this->data)) {
							foreach($this->data['content'] as $key => $value) {
									write_file(array("content"=>$value,"type"=>$this->data['type'][$key],"save_to"=>$this->data['save_to'][$key]));
								}
						}
						
					return $this;
				}
		}
?>
