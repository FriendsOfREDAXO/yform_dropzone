<?php

/**
 * yform.
 *
 * @author jan.kristinus[at]redaxo[dot]org Jan Kristinus
 * @author <a href="http://www.yakamara.de">www.yakamara.de</a>
 */

class rex_yform_value_dropzone extends rex_yform_value_abstract
{
    public static $getListValues = [];

    public function enterObject()
    {
		rex_login::startSession();

        // Wir müssen die festgelegten Limits pro Formular als SESSION-Variable speichern, damit die Upload-API serverseitig validieren kann. 
        $session[$this->getFieldId()]["allowedExtensions"] = $this->getElement('types');
        $session[$this->getFieldId()]["maxFileSize"] = $this->getElement('size_single');
		rex_set_session('rex_yform_dropzone', $session);

		$this->params['form_output'][$this->getId()] = $this->parse('value.dropzone.tpl.php');

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
				],
				'description' => rex_i18n::msg('yform_values_dropzone_description'),
            'dbtype' => 'text',
        ];
	}
	
	
    public static function getListValue($params)
    {
        $value = $params['subject'];
        $length = strlen($value);
        $files = explode(",",$value);

        $return = $value;
        if (rex::isBackend()) {
			foreach ($files as $file) {
				$field = new rex_yform_manager_field($params['params']['field']);
				if ($value != '') {
					$return .= '<a href="/redaxo/index.php?page=yform/manager/data_edit&table_name='.$field->getElement('table_name').'&data_id='.$params['list']->getValue('id').'&func=edit&rex_upload_downloadfile='.urlencode($file).'" title="'.rex_escape($file).'">'.rex_escape($file).'</a>';
				}
			}
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
}
