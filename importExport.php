<?php
/*
 * Import / Export script used on Munin for Android.
 * Database row TTL = 1 day
 * Usage :
 *	- Export : calling importExport.php?export with POST data (data string)
 *	- Import : calling importExport.php?import with POST data (pswd)
 * Database :
 *	- id (int, AI)
 *	- version (int)
 *	- exportDate (datetime)
 *	- password (varchar 16)
 *	- dataString (text)
 *
 *
 * Errors :
 *  000 : OK
 *  001 : Missing POST data
 *  002 : DB connection error
 *  003 : Bad request
 *  004 : Invalid JSON
 *  005 : Insert fail
 *  006 : Select fail
 *
 * Response :
 *  JSON encoded string
 *  success : bool : if succeeded
 *  error : if any, contains the error code
 */

require_once('config.php');
require_once('importExport.lib.php');

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$action = isset($_GET['export']) ? 'export' : (isset($_GET['import']) ? 'import' : '');

switch ($action) {
	case 'export': // Device to server
        if (!isset($_POST['dataString']))
            dieOnError('001');

        $db = getDb();
        if ($db == null)
            dieOnError('002');

        cleanDb($db);

        $version = isset($_POST['version']) ? intval($_POST['version']) : 0;
        $dataString = $_POST['dataString'];
        $dataType = isset($_POST['dataType']) ? $_POST['dataType'] : 'masters';

        // Generate random password
        $pswd = rand_password();

        $query = $db->prepare('INSERT INTO importexport (version, exportDate, password, dataString, dataType)
                VALUES (:version, NOW(), :password, :dataString, :dataType)');
        $res = $query->execute(
            array(
                'version' => $version,
                'password' => $pswd,
                'dataString' => $dataString,
                'dataType' => $dataType
            )
        );

        if ($res)
            echo prepareJsonSuccess_export($pswd);
        else
            dieOnError('005');

	break;
	case 'import': // Server to device
        $db = getDb();
        if ($db == null)
            dieOnError('002');

        cleanDb($db);

        $pswd = isset($_POST['pswd']) ? $_POST['pswd'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : 'masters';
        $dataType = isset($_POST['dataType']) ? $_POST['dataType'] : 'masters';

        if ($pswd == '')
            dieOnError('001');

        $sql = 'SELECT id, version, exportDate, dataString FROM importexport where password=:pswd AND dataType=:dataType';
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute([
            ':pswd' => $pswd,
            'dataType' => $dataType
        ]);
        $lines = $sth->fetchAll();

        if (count($lines) == 0)
            dieOnError('006');

        $line = $lines[0];
        $jsonString = $line['dataString'];
        $jsonObj = json_decode(stripslashes("[$jsonString]"), 1);

        echo prepareJsonSuccess_import($jsonObj);

        // Delete row (since it won't be used anymore)
        clearRow($line['id'], $db);
	break;
	default:
		dieOnError('003');
	break;
}


function cleanDb(PDO $db) {
    $db->exec('DELETE FROM importexport WHERE `exportDate` < CURDATE( ) - INTERVAL 1 DAY');
}

function clearRow($id, PDO $db) {
    $db->exec('DELETE FROM importexport WHERE id = ' . $id);
}

/* FUNCTIONS */
function dieOnError($errorCode) {
    echo json_encode(
        array(
            'success' => false,
            'error' => $errorCode
        )
    );
    die();
}

function prepareJsonSuccess_export($pswd) {
    return json_encode(
        array(
            'success' => true,
            'error' => '000',
            'password' => $pswd
        )
    );
}

function prepareJsonSuccess_import($jsonObj) {
    return json_encode(
        array(
            'success' => true,
            'error' => '000',
            'data' => ($jsonObj)
        )
    );
}
