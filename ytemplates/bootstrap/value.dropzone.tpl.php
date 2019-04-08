<?php
$class       = $this->getElement('required') ? 'form-is-required ' : '';
$class_group = trim('form-group  ' . $class . $this->getWarningClass());


$dzDictDefault = (json_decode('{
    "add":"Dateien hinzufügen",
    "start":"Upload starten",
    "clear":"zurücksetzen",
    "dictDefaultMessage":"Dateien auf dieses Feld ziehen",
    "dictFallbackMessage":"Ihr Browser untersützt leider keine Drag\'n\'Drop Datei Uploads",
    "dictFallbackText":"",
    "dictFileTooBig":"Datei ist zu groß",
    "dictInvalidFileType":"Dateityp wird nicht unterstützt",
    "dictResponseError":"Ein Fehler ist aufgetreten. Ein oder mehrere Dateien konnten nicht hochgeladen werden.",
    "dictCancelUpload":"abbrechen",
    "dictUploadCanceled":"Upload wurde abgebrochen",
    "dictCancelUploadConfirmation":"Upload wird abgebrochen",
    "dictRemoveFile":"entfernen",
    "dictRemoveFileConfirmation":"Datei wird entfernt",
    "dictMaxFilesExceeded":"Zuviele Dateien",
    "dictFileSizeUnits":"mb"
}', true));

$dzDict = array_merge($dzDictDefault, json_decode($this->getElement('dropzone_dict'), true));

$dataDzDict = "";

foreach($dzDict as $key => $value) {  
    $dataDzDict .= "data-dz-".$k."=\"".$v."\" ";
}

?>

<div class="<?php echo $class_group; ?>" id="<?php echo $this->getHTMLId(); ?>">

    <label for="<?= $this->getFieldName() ?>"><?= $this->getElement('label') ?></label>
    <input type="hidden" id="<?= $this->getFieldId() ?>" name="<?= $this->getFieldName() ?>" value="<?= $this->getValue() ?>"/>

    <!-- Dropzone-Code -->
    <div class="dropzone dropzone-upload panel-default panel" data-dz-form-id="<?= $this->params['form_wrap_id'] ?>" data-dz-id="<?= $this->getFieldId() ?>"  data-dz-unique-key="<?= $uniqueKey ?>" id="dz-<?= $this->getFieldId() ?>" 
    data-dz-types="<?= $this->getElement('allowed_types') ?>" data-dz-max-files="<?= $this->getElement('allowed_max_files') ?>" data-dz-file-size="<?= $this->getElement('size_single') ?>" data-dz-thumbnail-width="80" data-dz-thumbnail-height="80" data-dz-parallel-uploads="4" <?= $dataDzDict ?>>


    <div class="row">
        <div class="col col-md-12">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span type="button" class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i><span><?= $dzDict['add'] ?></span>
            </span>
            <button type="button" class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i><span><?= $dzDict['start'] ?></span>
            </button>
            <button type="reset" class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i><span><?= $dzDict['clear'] ?></span>
            </button>
        </div>
    </div>

        <!-- Upload-Target für Drag & Drop -->
        <div class="upload-container panel-body">
            <style>
                .upload-container.dz-drag-hover {
                    background: green;
                }
                </style>
                <p><?= $dzDict['dictDefaultMessage'] ?></p>
        </div>
        <!-- / Upload-Target für Drag & Drop -->



        <!-- Preview-Container -->
        <div class="table table-striped table-hover dz-files">

            <!-- Preview-Element -->
            <div class="dz-preview dz-file-preview file-row">
                <div><span class="preview"><img data-dz-thumbnail /></span></div>

                <div>
                    <p class="name" data-dz-name></p>
                    <div class="error text-danger" data-dz-errormessage style="display: block;">
                        <div class="dz-success-mark"><span>✔</span></div>
                        <div class="dz-error-mark"><span>✘</span></div>
                        <p><?= $dzDict['dictResponseError'] ?></p>
                    </div>
                </div>

                <div>
                    <p class="size" data-dz-size></p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                    </div>
                </div>

            
                <div class="actions">
                    <button type="button" class="btn btn-primary start"><i class="glyphicon glyphicon-upload"></i> <span><?= "TODO: Start" ?></span></button>
                    <button type="button" data-dz-remove class="btn btn-warning cancel"><i class="glyphicon glyphicon-ban-circle"></i> <span><?= $dzDict['dictCancelUpload'] ?></span></button>
                    <button type="button" data-dz-remove class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i> <span><?= $dzDict['dictRemoveFile'] ?></span></button>
                </div>
            </div>
            <!-- / Preview-Element -->

        </div>
            
        <div class="row">
            <div class="error text-danger" data-dz-errormessage style="display: none;">
                <p><?= $dzDict['dictResponseError'] ?></p>
            </div>

            <div class="col col-md-12">
                <!-- The global file processing state -->
                <span class="fileupload-process">
                <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
                </span>
            </div>
        </div>
        
    </div>

    <!-- Notice-Feld -->
    <?php
        $notice = [];

        if ($this->getElement('notice') != '') {
            $notice[] = rex_i18n::translate($this->getElement('notice'), false);
        }

        if (isset($this->dropzone_params['warning_messages'][$this->getId()]) && !$this->dropzone_params['hide_field_warning_messages']) {
            $notice[] = '<span class="text-warning">' . rex_i18n::translate($this->dropzone_params['warning_messages'][$this->getId()], false) . '</span>';
        }

        if (count($notice) > 0) {
            $notice = '<p class="help-block">' . implode('<br />', $notice) . '</p>';
        } else {
            $notice = '';
        }
        echo $notice; 
    ?>
</div>
<script src="/assets/addons/yuploader/js/dropzone.js" defer></script>
<!-- <script src="/assets/js/dropzone.min.js"></script> -->
<script src="/assets/addons/yuploader/js/dropzone-yform.js" defer></script>
<link rel="stylesheet" href="/assets/addons/yuploader/css/dropzone.css">
