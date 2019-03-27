<?php

/**
 * yform.
 *
 * @author Alexander Walther
 */

class rex_yform_value_dropzone extends rex_yform_value_abstract
{
    public static $getListValues = [];

    public function enterObject()
    {
        rex_login::startSession();
        

        $unique = self::_upload_getUniqueKey();

        // Backend Download
        // Wenn im Backend ein Download angefordert wurde, dann den Download ausführen
        if (rex::isBackend() && rex_request('dropzone_download', 'string', false) && in_array(rex_request('dropzone_download', 'string'), explode(",",$this->getValue()))) {
            $this->dropzone_download(rex_request('dropzone_download', 'string'));
        }
        // / Backend Download

        // Wir müssen die festgelegten Limits pro Formular als SESSION-Variable speichern, damit die Upload-API serverseitig validieren kann. 
        $session[$this->getFieldId()]["allowedExtensions"] = $this->getElement('types');
        $session[$this->getFieldId()]["maxFileSize"] = $this->getElement('size_single');
        $session[$this->getFieldId()]["unique_key"] = $unique;
		rex_set_session('rex_yform_dropzone', $session);

        // Dropzone ausgeben
		$this->params['form_output'][$this->getId()] = $this->parse('value.dropzone.tpl.php');

        // Dateien / Anhänge in E-Mail verfügbar machen
        $server_upload_path = rex_path::pluginData('yform', 'manager', 'upload/dropzone/'.$unique.'/');

        // Nur Dateien, keine Ordner
        // https://stackoverflow.com/questions/14680121/include-just-files-in-scandir-array
        if(is_dir($server_upload_path)) {
            $uploaded_files = array_filter(scandir($server_upload_path), function($item) {
                $file = $server_upload_path . $item;
                return !is_dir($file);
            });

            $value = $unique. "/".implode(",".$unique."/",$uploaded_files); 
        };
        
        $this->params['value_pool']['email'][$this->getName()] = $value;

        if ($this->saveInDb()) {
            $this->params['value_pool']['sql'][$this->getName()] = $value;
        }

        // Todo: foreach file in folder add to  value_pool files
        if ($filepath != '') {
            $this->params['value_pool']['files'][$this->getName()] = [$filename, $filepath, $real_filepath];
        }

        $errors = [];
        // Todo: if empty folder add error
        if ($this->params['send'] && $this->getElement('required') == 1 && $filename == '') {
            $errors[] = $error_messages['empty_error'];
        }

        // Todo: if error make form invalid
        if ($this->params['send'] && count($errors) > 0) {
            $this->params['warning'][$this->getId()] = $this->params['error_class'];
            $this->params['warning_messages'][$this->getId()] = implode(', ', $errors);
        }


    }

    public function getDescription()
    {
        return 'dropzone|name|label|Maximale Größe in Kb oder Range 100,500 oder leer lassen| endungenmitpunktmitkommasepariert oder *| pflicht=1 | min_err,max_err,type_err,empty_err,delete_file_msg ';
    }

    public function getDefinitions()
    {
        return [
            'type' => 'value',
            'name' => 'dropzone',
            'values' => [
				'name' => ['type' => 'name', 'label' => rex_i18n::msg('yform_values_defaults_name')],
					'label' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_label')],
					'types' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_types'), 'notice' => rex_i18n::msg('yform_values_dropzone_types_notice')],
					'types_error' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_types_error')],
					'required' => ['type' => 'boolean', 'label' => rex_i18n::msg('yform_values_dropzone_required')],
					'notice' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_defaults_notice')],
					'size_single' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_size_single')],
					'size_single_error' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_size_single_error')],
					'size_all' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_size_all')],
					'size_all_error' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_size_all_error')],
					'label_dropzone_file_info' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_file_info') , 'notice' => 'z.B. <code>Dateien zum Hochladen auf dieses Feld ziehen</code>'],
					'label_dropzone_file_button' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_file_button')],
					'label_dropzone_modal_error' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_modal_error') , 'notice' => 'z.B. <code>Dateien zum Hochladen auf dieses Feld ziehen</code>'],
					'label_dropzone_modal_button' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_modal_button')],
					'label_dropzone_file_button_start' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_modal_button')],
					'label_dropzone_file_button_cancle' => ['type' => 'text', 'label' => rex_i18n::msg('yform_values_dropzone_label_dropzone_modal_button')],
				],
				'description' => rex_i18n::msg('yform_values_dropzone_description'),
            'dbtype' => 'text',
        ];
	}
	
	
    public static function getListValue($params)
    {
        $files = explode(",",$params['subject']);

        $downloads = [];
        if (rex::isBackend()) {
			foreach ($files as $file) {
				$field = new rex_yform_manager_field($params['params']['field']);
				if ($files != []) {
                    $file_name = array_pop(explode("/",$file));
                    $downloads[] = '<a href="/redaxo/index.php?page=yform/manager/data_edit&table_name='.$field->getElement('table_name').'&data_id='.$params['list']->getValue('id').'&func=edit&dropzone_download='.urlencode($file).'" title="'.rex_escape($file_name).'">'.rex_escape($file_name).'</a>';
                    $return = implode("<br />", $downloads);
				}
			}
        } else {
            $return = $params['subject']; // Todo: In der Editieransicht am Feld lassen
        }

        return $return;
	}
	
    public static function getSearchField($params)
    {
        $params['searchForm']->setValueField('text', ['name' => $params['field']->getName(), 'label' => $params['field']->getLabel()]);
    }

    public static function getSearchFilter($params)
    {
        $sql = rex_sql::factory();
        $value = $params['value'];
        $field = $params['field']->getName();

        if ($value == '(empty)') {
            return ' (' . $sql->escapeIdentifier($field) . ' = "" or ' . $sql->escapeIdentifier($field) . ' IS NULL) ';
        }
        if ($value == '!(empty)') {
            return ' (' . $sql->escapeIdentifier($field) . ' <> "" and ' . $sql->escapeIdentifier($field) . ' IS NOT NULL) ';
        }

        $pos = strpos($value, '*');
        if ($pos !== false) {
            $value = str_replace('%', '\%', $value);
            $value = str_replace('*', '%', $value);
            return $sql->escapeIdentifier($field) . ' LIKE ' . $sql->escape($value);
        }
        return $sql->escapeIdentifier($field) . ' = ' . $sql->escape($value);
    }

    // Analog zum upload-Feld in YForm 3.0 - Datei herunterladen
    public static function dropzone_download($file)
    {
        $filename = array_pop(explode(",", $file));
        $filepath = rex_path::pluginData('yform', 'manager', 'upload/dropzone/'.$file);
        
        if (file_exists($filepath)) {
            ob_end_clean();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    }

    private function _upload_getUniqueKey()
    {
        return bin2hex(openssl_random_pseudo_bytes(32, $cstrong));
    }

}
