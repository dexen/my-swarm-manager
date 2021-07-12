<?php

namespace dexen\MySwarm;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require 'lib.php';

function sitesA() : array
{
	$a = glob('sites/*/',  GLOB_MARK | GLOB_ONLYDIR | GLOB_ERR);
	return array_map(
		fn($pn) => [
			'id' => basename($pn),
			'name' => basename($pn),
			'path' => dirname($pn .'/DUMMY.txt'),
			'pathU' => rawurlencode_path(dirname($pn .'/DUMMY.txt')),
		],
		$a );
}

function renderListing()
{
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<head><title>My Swarm</title>';
	echo '<body>';

	echo '<ul>';
		foreach (sitesA() as $rcd)
			echo '<li><a href="', H($rcd['pathU']), '">', H($rcd['name']) .'</a></li>';
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

function renderSite($url, $upa)
{
	$site = $upa[0];
	foreach (sitesA() as $rcd)
		if ($rcd['id'] === $site) {
			if (!chdir($rcd['path'] .'/public_html'))
				throw new \Exception(sprintf('chdir("%s")', $rcd['path'] .'/public_html'));
			return (function() { require func_get_arg(0); })($rcd['path'] .'/public_html/index.php'); }
	renderNotFound();
}

$url = parse_url($_SERVER['REQUEST_URI']);

$upa = explode('/', $url['path']);

if ($upa[0] === '')
	array_shift($upa);
else
	throw new \Exception(sprintf('unexpected url path format: "%s"', $url['path']));


if ($upa === [ '' ])
	renderListing();
else if ($upa[0] === 'sites') {
	array_shift($upa);
	renderSite($url, $upa);
}
else
	renderNotFound();
