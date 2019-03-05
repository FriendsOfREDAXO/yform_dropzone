# YUploader

## Übersicht 

Dieses Addon bindet [dropzone.js](https://github.com/enyo/dropzone/) ein und fügt YForm 3.0 die Value `dropzone` hinzu. Diese bietet eine Möglichkeit, mehrere Dateien auf einmal hochzuladen. 

## Features

* Mehrere Dateien in ein YForm-Value hochladen
* Drag & Drop im Frontend und im Table Manager
* Download der Dateien direkt aus der Tabellenübersicht des Table Managers
* Einschränkung maximale Dateigröße je Datei
* Einschränkung des Dateityps (Dateiendungen) sowohl clientseitig (Datei-Auswahl-Dialog des Betriebssystems), als auch serverseitig (Validierung)

> **Hinweis:** Es sollte sichergestellt sein, dass der Webspace genügend Speicherplatz hat, um größere Mengen an Uploads problemlos abzuspeichern.

## Installation

Voraussetzung für die aktuelle Version von YUploader: REDAXO >= 5.6, YForm >= 3.0

* Über das REDAXO-Backend installieren und aktivieren
* Setup ausführen

# Erste Schritte

## Setup

Nach der Installation steht das Feld direkt zur Verfügung.

## Feld hinzufügen (Table Manager)

1. In REDAXO auf `YForm` > `Table Manager` klicken
2. In der gewünschten Tabelle die Felddefinition editieren
3. Das Feld `dropzone` hinzufügen und den Instruktionen folgen

Anschließend kann das Feld verwendet werden.

## Feld hinzufügen (YForm Formbuilder)

TODO: Hier Felddefinition einfügen.

# Weitere Informationen / FAQ

## Wo werden die Daten gespeichert?

Die Dateien werden in `/data/addons/yform/plugins/manager/upload/dropzone/` abgelegt.

## Werden die Dateien automatisch gelöscht?

Nein. Deshalb sollte ausreichend Speicherplatz vorhanden sein.

# Links und Hilfe

## Bugmeldungen Hilfe und Links

* Auf Github: https://github.com/skerbis/yuploader/issues/
* im Forum: https://www.REDAXO.org/forum/
* im Slack-Channel: https://friendsofREDAXO.slack.com/
