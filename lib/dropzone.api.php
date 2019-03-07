<?php

// Diese Klasse nimmt unter index.php?rex-api-call=yform_dropzone&func=upload den Upload der Dateien vor Absenden des Formulars entgegen.

class rex_api_yform_dropzone extends rex_api_function
{
	protected $published = true;

	public function getPath() {
		// Todo: Subpath für jede Datei, um Doubletten zu verhindern.
		return rex_path::pluginData('yform', 'manager', 'upload/dropzone/'.rex_string::normalize(rex_post("unique")).'/');
	} 

	public function getAllowedExtensions() {
		return explode(",",rex_session('rex_yform_dropzone')[rex_string::normalize(rex_post("unique"))]["allowedExtensions"]);
	} 

	public function getAllowedSizePerFile() {
		return rex_session('rex_yform_dropzone')[rex_string::normalize(rex_post("unique"))]["maxFileSize"] * 1024;
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

		// Todo: https://www.redaxo.org/api/master/class-rex_request.html files

		if (rex_request::files('file', "array", false)) {
			$tempFile = rex_request::files('file')['tmp_name'];
			$targetFile =  strtolower(rex_request::files('file')['name']);
			$fileSize =  rex_request::files('file')['size'];

			$ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
			

			if(in_array(".".$ext,self::getAllowedExtensions()) && $fileSize < self::getAllowedSizePerFile() && !file_exists(self::getPath().$targetFile) ) {

				// Todo: Timestamp beifügen, damit derselbe Dateiname mehrfach hochgeladen werden kann.
			// Todo: auf https://www.redaxo.org/api/master/class-rex_file.html umsteigen
			move_uploaded_file($tempFile,self::getPath().$targetFile);

				// Upload success
				header('Content-type: text/json');
				header('Content-type: application/json');
				exit(json_encode( [ 'name' => $targetFile, 'size' => $fileSize ] ) );
			}
			
			$return["allowedExtensions"] = self::getAllowedExtensions();
			$return["ext"] = $ext;
			$return["maxFileSize"] = self::getAllowedSizePerFile();
			$return["fileSize"] = $fileSize;
			$return['exists'] = file_exists(self::getPath().$targetFile);
			self::httpError($return);
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