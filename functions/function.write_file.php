<?php
/*
 * @type (array)
 * @param ['content'] => The content to write to disk => DEFAULT => false
 * @param ['save_to'] => The filename including directory => DEFAULT => false
 * @param ['type'] => This is the way the file is to be saved. DEFAULT => 'a'
*/
	function write_file($settings = false)
		{
			$settings['content']	=	(!empty($settings['content']))? $settings['content']: false;
			$settings['save_to']	=	(!empty($settings['save_to']))? $settings['save_to']: false;
			$settings['type']		=	(!empty($settings['type']))? $settings['type']: 'a';
			
			if($settings['save_to'] == false)
				return;
				
			/***********************************/
			/***** BREAK OUT THE DIRECTORY *****/
			/***********************************/
			
			$dir_exp	=	explode("/",$settings['save_to']);
			$dir_exp	=	array_filter($dir_exp);
			array_pop($dir_exp);
			$dir		=	implode("/",$dir_exp);
			
			/***********************************/
			/***********************************/
			
			// Set default write to false
			$write		=	false;
			// Create folder if not exists
			if(!is_dir($dir)) {
					if(mkdir($dir,0755,true))
						$write	=	true;
				}
			else
				$write	=	true;
				
			// If all is good, write file
			if($write != true)
				return;
			
			$fh	=	fopen($settings['save_to'], $settings['type']);
			fwrite($fh, $settings['content']);
			fclose($fh);
			
			return (is_file($settings['save_to']))? true:false;
		}
?>
