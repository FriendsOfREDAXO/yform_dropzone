# dropzone für YForm 3.0

## Übersicht 

Dieses Addon bindet [dropzone.js](https://github.com/enyo/dropzone/) ein und fügt YForm 3.0 die Value `dropzone` hinzu. Diese bietet eine Möglichkeit, mehrere Dateien auf einmal hochzuladen. 


## Features

* Mehrere Dateien in ein YForm-Value hochladen
* Drag & Drop Upload im Frontend und im Table Manager
* Download der Dateien direkt aus der Tabellenübersicht des Table Managers
* Einschränkung maximale Dateigröße je Datei
* Einschränkung des Dateityps (Dateiendungen) sowohl clientseitig (Datei-Auswahl-Dialog des Betriebssystems), als auch serverseitig (Validierung)

> **Hinweis:** Es sollte sichergestellt sein, dass der Webspace genügend Speicherplatz hat, um größere Mengen an Uploads problemlos abzuspeichern.

## Installation

Voraussetzung für die aktuelle Version von `yform_dropzone`: REDAXO >= 5.7, YForm >= 3.0

* Über das REDAXO-Backend installieren und aktivieren
* Setup ausführen

# Erste Schritte

## Setup

Nach der Installation ist das Feld in YForm verfügbar.

## Feld hinzufügen (Table Manager)

1. In REDAXO auf `YForm` > `Table Manager` klicken
2. In der gewünschten Tabelle die Felddefinition editieren
3. Das Feld `dropzone` hinzufügen und den Instruktionen folgen

Anschließend kann das Feld verwendet werden.

# Links und Hilfe

## Hinweise und bekannte Probleme in dieser Version

> **ACHTUNG:** Im Moment muss eine hidden-Value namens `order_id` im Formular vorhanden sein, das einen einmaligen Key generiert. Beteilige dich am Addon, um dieses Problem zu lösen. z.B.: `$yform->setValueField('hidden', array('order_id',bin2hex(openssl_random_pseudo_bytes(16))));`

> Es wird jQuery benötigt.

> Die Einstellung "Pflichtfeld" ist ohne Funktion

## Bugmeldungen Hilfe und Links

* Auf Github: https://github.com/FriendsOfREDAXO/yform_dropzone/issues
* im Forum: https://www.REDAXO.org/forum/
* im Slack-Channel: https://friendsofREDAXO.slack.com/
