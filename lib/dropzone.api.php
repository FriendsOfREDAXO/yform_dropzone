<?php

// Diese Klasse nimmt unter index.php?rex-api-call=yform_dropzone&func=upload den Upload der Dateien vor Absenden des Formulars entgegen.

class rex_api_yform_dropzone extends rex_api_function
{
	protected $published = true;

	public function getPath() {
		// Todo: Subpath für jede Datei, um Doubletten zu verhindern.
		return rex_path::pluginData('yform', 'manager', 'upload/dropzone/'.session_id().'/');
	} 

	public function getAllowedExtensions() {
		return explode(",",rex_session('yform_dropzone_types','string',''));
	} 


    function execute()
    {
		$func = rex_request('func','string','');
		
		if($func == 'upload'){
			self::executeUpload();
		}
		else if($func == 'delete'){
			self::executeDelete();
		}
    }
	
	public static function executeDelete(){
		
		$file = rex_request('file','string','');
				
		if($file != '' && file_exists(self::getPath().$file) ){
			unlink(self::getPath().$file);
			header( 'HTTP/1.1 200 OK' );
			exit();
		}
		else{
			self::httpError(self::getPath().$file);
		}
	}
		
	public static function executeUpload(){
		
		
		rex_dir::create(self::getPath());

		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$targetFile =  $_FILES['file']['name'];
			$fileSize =  $_FILES['file']['size'];
			
			$ext = pathinfo($targetFile, PATHINFO_EXTENSION);
			
			if(in_array(".".$ext,self::getAllowedExtensions()) && $fileSize < 5000000 && !file_exists(self::getPath().$targetFile) ) {
				move_uploaded_file($tempFile,self::getPath().$targetFile);

				// Upload success
				header('Content-type: text/json');
				header('Content-type: application/json');
				exit(json_encode( [ 'name' => $targetFile, 'size' => $fileSize ] ) );
			}
			
			self::httpError($fileSize);
		}
		else {                                                           
			$result  = array();
		 
			$files = scandir(self::getPath());
			if ( false !== $files ) {
				foreach ( $files as $file ) {
					if ( '.' != $file && '..' != $file) {
						$obj['name'] = $file;
						$obj['size'] = filesize(self::getPath().$file);
						$result[] = $obj;
					}
				}
			}
			 
			header('Content-type: text/json');
			header('Content-type: application/json');
			exit( json_encode( $result ) );
		}
	}

    public static function httpError( $result )
    {
        header( 'HTTP/1.1 500 Internal Server Error' );
        header('Content-Type: application/json; charset=UTF-8');
        exit( json_encode( $result ) );
    }
}

?>