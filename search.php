<?php
/**
 * Search
 *
 * Execute a search query on Elasticsearch and return the results
 */

// load config
require_once('config.php');

$filters = array();

// Block xxx-content, feel free to remove this
$filters[] = array(
	'not' => array(
		'term' => array(
			'subcatd' => 'd75'
		)
	)
);

// Built filter for the category (video, music, games, software)
if (isset($_GET['filterType']))
{
	$filters[] = array(
		'term' => array(
			'category' => $_GET['filterType']
		)
	);
}

// Built filter for the types (eg. HD, DivX, Series...)
// Subcategories in spotweb are a,b,c,d,z
// A type like HD, exists of 'a4','a6','a7','a8','a9'
// Any of these subcategories means it's an HD spot (like X264 etc.)
// Theoretically a filter can have multiple subcategories, like z0, a0, a2 etc.
// We now built a filter per type, that requieres for any subcategory
// to have at least one member present, like:
// z0 AND (a0 OR a1)
if (isset($_GET['subcats']))
{
	$subcats = json_decode($_GET['subcats'], TRUE);
	
	$sub_filters = array();
	
	foreach($subcats as $subcat => $sublist)
	{
		$subsub_filters = array();
		
		foreach($sublist as $subkey)
		{
			$subsub_filters[] = array(
				'term' => array(
					$subcat => $subkey
				)
			);
		}
		
		if (!empty($subsub_filters))
		{
			$sub_filters[] = array(
				'or' => $subsub_filters
			);
		}
	}
	
	if (!empty($sub_filters))
	{
		$filters[] = array(
			'and' => $sub_filters
		);
	}
}

// Built the search query, if no search text is present
// match everything
if (isset($_GET['q']) AND $_GET['q'] !== '')
{
	$query = array(
		'query' => array(
			'query_string' => array(
				'query' => $_GET['q']
			)
		),
		'sort' => array(
			'_score',
			array('stamp' => array('order'=>'desc'))
		),
		'filter' => array(
			'and' => $filters
		)
	);
}
else
{
	$query = array(
		'query' => array(
			'match_all' => array()
		),
		'sort' => array(
			array('stamp' => array('order'=>'desc'))
		),
		'filter' => array(
			'and' => $filters
		)
	);
}

// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, rtrim($elasticsearch_url, '/') . '/spotweb/spots/_search');

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// give the query as post field
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));

// $output contains the output string
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

// return the Elasticsearch response to the client
die($output);
