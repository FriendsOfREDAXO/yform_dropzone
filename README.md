# Dropzone Multi-Upload-Feld mit Drag & Drop für YForm 4

## Übersicht 

Dieses Addon bindet [dropzone.js](https://github.com/enyo/dropzone/) ein und fügt YForm die Value `dropzone` hinzu. Diese bietet eine Möglichkeit, mehrere Dateien auf einmal hochzuladen. 


## Features

* Mehrere Dateien in ein YForm-Value hochladen
* Drag & Drop Upload im Frontend und im Table Manager
* Download der Dateien direkt aus der Tabellenübersicht des Table Managers
* Einschränkung maximale Dateigröße je Datei
* Einschränkung des Dateityps (Dateiendungen) sowohl clientseitig (Datei-Auswahl-Dialog des Betriebssystems), als auch serverseitig (Validierung)

> **Hinweis:** Es sollte sichergestellt sein, dass der Webspace genügend Speicherplatz hat, um größere Mengen an Uploads problemlos abzuspeichern.

## Erste Schritte

### Setup

Nach der Installation ist das Feld in YForm verfügbar.

### Feld hinzufügen (Table Manager)

1. In REDAXO auf `YForm` > `Table Manager` klicken
2. In der gewünschten Tabelle die Felddefinition editieren
3. Das Feld `dropzone` hinzufügen und den Instruktionen folgen
4. Lang-Parameter hinzufügen:

````
{
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
}
```

Anschließend kann das Feld verwendet werden.

## Links und Hilfe

### Hinweise und bekannte Probleme in dieser Version

> **ACHTUNG:** Im Moment muss eine hidden-Value namens `order_id` im Formular vorhanden sein, das einen einmaligen Key generiert. Beteilige dich am Addon, um dieses Problem zu lösen. z.B.: `$yform->setValueField('hidden', array('order_id',bin2hex(openssl_random_pseudo_bytes(16))));`

> Es wird jQuery benötigt.

> Die Einstellung "Pflichtfeld" ist ohne Funktion

## Debugging

Wenn was mit der Dropzone clientseitig schief läuft, ist das Problem entweder in der mitgelieferten JS-Datei in `/assets/addons/yform_dropzone/js/`, oder serverseitig in der API unter `/redaxo/src/addons/yform_dropzone/lib/`.

Erster Ansatz: Browser-Developer-Console liefert beim Fehler zusätzliche Infos als JSON in der Response, z.B. bei der Validierung (Dateigröße, Formate, oder, ob die Datei bereits vorhanden war).

Meist stimmt einfach was mit den Parametern der Validierung nicht.

## Bugmeldungen Hilfe und Links

* Auf Github: https://github.com/FriendsOfREDAXO/yform_dropzone/issues
* im Forum: https://www.REDAXO.org/forum/
* im Slack-Channel: https://friendsofREDAXO.slack.com/
