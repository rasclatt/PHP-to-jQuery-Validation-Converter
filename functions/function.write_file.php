<?php
/*
 * @type (array)
 * @param ['content'] => The content to write to disk => DEFAULT => false
 * @param ['save_name'] => The filename => DEFAULT => false
 * @param ['save_to'] => The directory to save the file in => DEFAULT => false
 * @param ['type'] => This is the way the file is to be saved. DEFAULT => 'a'
*/
	function write_file($settings = false)
		{
			$content	=	(!empty($settings['content']))? $settings['content']: false;
			$dir		=	(!empty($settings['save_dir']))? $settings['save_dir']: false;
			$filename	=	(!empty($settings['save_name']))? $settings['save_name']: false;
			$type		=	(!empty($settings['type']))? $settings['type']: 'a';
			// Set default write to false
			$write		=	false;
			
			if(!$dir || !$filename)
				return;
			
			// Create folder if not exists
			if(!is_dir($dir))
				$write	=	(mkdir($dir,0755,true))? true : false;
			else
				$write	=	true;
				
			// If all is good, write file
			if(!$write)
				return;
			$file	=	str_replace("//","/",$dir.$filename);
			$fh		=	fopen($file, $type);
			fwrite($fh, $content);
			fclose($fh);
			
			return (is_file($file))? true:false;
		}
?>
