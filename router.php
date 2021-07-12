<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require 'lib.php';

function renderListing()
{
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<body>';

	$a = glob('sites/*/',  GLOB_MARK | GLOB_ONLYDIR | GLOB_ERR);
	$b = array_map(
		fn($pn) => [ basename($pn), dirname($pn .'/DUMMY.txt'), $pn .'public_html/index.php' ],
		$a );

	echo '<ul>';
		foreach ($b as $rcd)
			echo '<li><a href="', HU($rcd[1]), '">', H($rcd[0]) .'</a></li>';
	echo '</ul>';
}

function renderNotFound()
{
	header('HTTP/1.1 404 Not Found');

	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<body>';

	echo '<h1>HTTP 404 Not found</h1>';

	echo '<p>Go to <a href="/">the main page?</a></p>';
}

$url = parse_url($_SERVER['REQUEST_URI']);

$upa = explode('/', $url['path']);

if ($upa[0] === '')
	array_shift($upa);
else
	throw new \Exception(sprintf('unexpected url path format: "%s"', $url['path']));


if ($upa === [ '' ])
	renderListing();
else if ($upa[0] === 'sites')
	renderSite($upa[1]);
else
	renderNotFound();
