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

if ($DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$action = isset($_GET['export'])?'export': (isset($_GET['import'])?'import':'');

switch ($action) {
	case 'export': // Device to serrver
        if (!isset($_POST['dataString'])) {
            echo prepareJsonError('001');
            die;
        }

        $db = connectDb();
        if ($db == null) {
            echo prepareJsonError('002');
            die;
        }

        cleanDb($db);

        $version = isset($_POST['version'])?intval($_POST['version']):0;
        $dataString = $_POST['dataString'];
        /*if (!isJsonOk($dataString)) {
            echo prepareJsonError('004');
            die;
        }*/

        // Generate random password
        $pswd = rand_password();

        $query = $db->prepare('INSERT INTO importexport (version, exportDate, password, dataString)
                VALUES (:version, NOW(), :password, :dataString)');
        $res = $query->execute(
            array(
                'version' => $version,
                'password' => $pswd,
                'dataString' => $dataString
            )
        );

        if ($res) {
            echo prepareJsonSuccess_export($pswd);
        } else {
            echo prepareJsonError('005');
            die;
        }

	break;
	case 'import': // Server to device
        $db = connectDb();
        if ($db == null) {
            echo prepareJsonError('002');
            die;
        }

        cleanDb($db);

        $pswd = isset($_POST['pswd']) ? $_POST['pswd'] : '';

        if ($pswd == '') {
            echo prepareJsonError('001');
            die;
        }

        $sql = 'SELECT id, version, exportDate, dataString FROM importexport where password=:pswd';
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':pswd' => $pswd));
        $lines = $sth->fetchAll();

        if (count($lines) == 0) {
            echo prepareJsonError('006');
            die;
        }

        $line = $lines[0];
        $jsonString = $line['dataString'];
        $jsonObj = json_decode(stripslashes("[$jsonString]"), 1);

        echo prepareJsonSuccess_import($jsonObj);

        // Delete row (since it won't be used anymore)
        clearRow($line['id'], $db);
	break;
	default:
		echo prepareJsonError('003');
		die;
	break;
}


function cleanDb($db) {
    $db->exec('DELETE FROM importexport WHERE `exportDate` < CURDATE( ) - INTERVAL 1 DAY');
}

function clearRow($id, $db) {
    $db->exec('DELETE FROM importexport WHERE id = ' . $id);
}

/* FUNCTIONS */
function prepareJsonError($errorCode) {
    return json_encode(
        array(
            'success' => false,
            'error' => $errorCode
        )
    );
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

/**
 * Generates a random hexadecimal password
 */
function rand_password() {
    return str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function connectDb() {
    function getConnection() {
        try {
            return new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DBNAME, MYSQL_USER, MYSQL_PASSWORD);
        }
        catch(Exception $e) {
            return null;
        }
    }
}

function isJsonOk($jsonString) {
    return is_string($jsonString) && is_object(json_decode($jsonString)) ? true : false;
}
