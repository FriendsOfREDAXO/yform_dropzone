<?php

// Diese Klasse nimmt unter index.php?rex-api-call=yform_dropzone&func=upload den Upload der Dateien vor Absenden des Formulars entgegen.

class rex_api_yform_dropzone extends rex_api_function
{
	protected $published = true;

	public function getPath() {
		// Todo: Subpath für jede Datei, um Doubletten zu verhindern.
		return rex_path::pluginData('yform', 'manager', 'upload/dropzone/'.rex_request("formId", 'string', 'public')."/".rex_request("fieldId", 'string', 'all')."/".rex_request("uniqueKey", 'string', 'public').'/');
	} 

	public function getAllowedExtensions() {
		return explode(",",rex_session('rex_yform_dropzone')[rex_request("formId")][rex_request("fieldId")][rex_request("uniqueKey")]["allowed_types"]);
	} 

	public function getAllowedSizePerFile() {
		return rex_session('rex_yform_dropzone')[rex_request("formId")][rex_request("fieldId")][rex_request("uniqueKey")]["allowed_filesize"];
	} 


    function execute()
    {
		$func = rex_request('func','string','');
		$file = rex_request('file','string','');

		if($func == 'upload'){

			self::executeUpload();
		}
		else if($func == 'delete'){
			self::executeDelete($file);
		}
    }
	
	public static function executeDelete($file){
						
		if($file != '' && file_exists(self::getPath().strtolower($file)) ){
			unlink(self::getPath().strtolower($file));
			header( 'HTTP/1.1 200 OK' );
			exit();
		}
		else{
			self::httpError("Datei nicht vorhanden.");
		}
	}
		
	public static function executeUpload(){

		// Todo: https://www.redaxo.org/api/master/class-rex_request.html files

		if (rex_request::files('file', "array", false)) {

			rex_dir::create(self::getPath());

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
					exit(json_encode( [ 'name' => $targetFile, 'tempFile' => $tempFile, 'size' => $fileSize ] ) );
				} else {				
					$return["tempFile"] = $tempFile;
					$return["targetFile"] = $targetFile;
					$return["allowedExtensions"] = self::getAllowedExtensions();
					$return["ext"] = $ext;
					$return["maxFileSize"] = self::getAllowedSizePerFile();
					$return["fileSize"] = $fileSize;
					$return['exists'] = file_exists(self::getPath().$targetFile);
					self::httpError($return);
				}
		} else {                  
			
			// Ausgabe bisher hochgeladener Dateien
			$result  = array();
		 
		 	if(file_exists(self::getPath())) {
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