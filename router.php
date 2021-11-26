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
			'path' => dirname($pn .'/DUMMY.txt') .'/',
			'pathU' => rawurlencode_path(dirname($pn .'/DUMMY.txt') .'/'),
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

function resolveAndProcessResource(string $dir, string $subdir, array $upa)
{
	if ($upa === [ '' ])
		$upa = [ 'index.php' ];

	if (in_array('..', $upa))
		throw new \Exception('unsupported: dot-dot');

	$wd = $dir .$subdir;
	array_unshift($upa, $wd);
	$pn = implode('/', $upa);

	if (strrpos($pn, '.php') === (strlen($pn)-4)) {
		if (!chdir($wd))
			throw new \Exception('could not set wd');
			/* note: do *not* return the result of the require, to avoid returning false */
		(function() { require func_get_arg(0); })(($pn)); }
	else if (file_exists($pn))
		readfile($pn);
	else
		renderNotFound();
}

function renderSite($url, $upa)
{
	$site = array_shift($upa);
	foreach (sitesA() as $rcd)
		if ($rcd['id'] === $site)
			return resolveAndProcessResource($rcd['path'], 'public_html', $upa);
	renderNotFound();
}

$url = parse_url($_SERVER['REQUEST_URI']);

if (($url['path']??null) === '/favicon.ico') {
	if (file_exists('favicon.ico')) {
		header('Content-Type: image/vnd.microsoft.icon');
		readfile('favicon.ico'); return true; } }

$upa = explode('/', $url['path']);

if ($upa[0] === '')
	array_shift($upa);
else
	throw new \Exception(sprintf('unexpected url path format: "%s"', $url['path']));


if ($upa === [ '' ])
	renderListing();
else if ($upa[0] === 'sites') {
	array_shift($upa);
	renderSite($url, $upa); }
else
	renderNotFound();

	# indicate to the built-in server that we've handled the request - one way or another.
return true;
