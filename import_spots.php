<?php
/**
 * Import Spots
 *
 * Get the spots from the Spotweb DB that are not present in Elasticsearch, yet.
 */

// load config
require_once('config.php');

// make a connection with the mysql database to retreive the spots
$mysqli = new mysqli($mysql_host, $mysql_spotweb_user, $mysql_spotweb_password, $mysql_spotweb_db);
if ($mysqli->connect_errno) {
    print("Failed to connect to MySQL: ({$mysqli->connect_errno}) {$mysqli->connect_error}\n");
    die(1);
}
$mysqli->set_charset("utf8");

// create curl resource
$ch = curl_init();

// elasticsearch query to get the highest mysql_id in elasticsearch
$query = array(
	'query' => array(
		'match_all' => array()
	),
	'sort' => array(
		'mysql_id' => array('order' => 'desc')
	)
);

// set url
curl_setopt($ch, CURLOPT_URL, rtrim($elasticsearch_url, '/') . '/spotweb/spots/_search');

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// give the query as post field
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));

// response and request info
$response = json_decode(curl_exec($ch), TRUE);
$info = curl_getinfo($ch);

if ($info['http_code'] === 0)
{
	print("Elasticsearch server unreachable!\n");
	die(2);
}

if (!isset($response['error']))
{
	$last_mysql_id = $response['hits']['hits'][0]['sort'][0];
}
else
{
	$last_mysql_id = 0;
}

// Get all spots not present in Elasticsearch yet
$mysqli->real_query("SELECT messageid AS _id, id as mysql_id, poster, title, tag, category, subcata, subcatb, subcatc, subcatd, subcatz, filesize, stamp
	FROM spots
	WHERE id > " . intval($last_mysql_id));
$res = $mysqli->use_result();

// Loop over the records and put them to Elasticsearch
while ($row = $res->fetch_assoc())
{
	$row['subcata'] = explode('|', trim($row['subcata'],'|'));
	$row['subcatb'] = explode('|', trim($row['subcatb'],'|'));
	$row['subcatc'] = explode('|', trim($row['subcatc'],'|'));
	$row['subcatd'] = explode('|', trim($row['subcatd'],'|'));
	$row['subcatz'] = explode('|', trim($row['subcatz'],'|'));
    $row['stamp'] = date('Y-m-d', $row['stamp']).'T'.date('H:i:s', $row['stamp']);
    $row['mysql_id'] = intval($row['mysql_id']);
    
	// set url
	curl_setopt($ch, CURLOPT_URL, rtrim($elasticsearch_url, '/') . '/spotweb/spots/' . $row['_id']);

	// give the query as post field
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($row));

	curl_exec($ch);
}

// close curl resource to free up system resources
curl_close($ch);