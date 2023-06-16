<?php

require 'vendor/autoload.php';

function settingGlobalVariables()
{

    $GLOBALS['connection'] = new MongoDB\Client('mongodb://localhost:27017');
    $GLOBALS['collection'] = $GLOBALS['connection']->basididati->logs;
}

settingGlobalVariables();

class ConnMongoDB
{


    public static function insertDocumentInLogs($eventText)
    {

        $collection = $GLOBALS['collection'];

        $data= date('m-d-Y h:i:s a', time());

        $collection->insertOne([

            'Data' => $data,
            'Evento' => $eventText
        ]);

    }

    public static function echoDocumentsInLogs()
    {

        $collection = $GLOBALS['collection'];

        $cursor = $collection->find([]);

        foreach ($cursor as $document) {

            echo " Campo Data :" . $document["Data"] . " Campo Evento : " . $document["Evento"] . "<br>";
            echo "<br>";
        }
    }


}

// $insertDocument = ConnMongoDB::insertDocumentInLogs("E' stato inserito una nuova conferenza");
// $printDocuments =ConnMongoDB::echoDocumentsInLogs();

?>
