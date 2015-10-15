<?php

/**
 * @return null|PDO
 */
function getDb() {
    try {
        return new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DBNAME, MYSQL_USER, MYSQL_PASSWORD);
    }
    catch(Exception $e) {
        return null;
    }
}

/**
 * Generates a random hexadecimal password
 */
function rand_password() {
    return str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

/**
 * Checks if a string is valid Json
 */
function isJsonOk($jsonString) {
    return is_string($jsonString) && is_object(json_decode($jsonString)) ? true : false;
}
