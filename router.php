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

renderListing();
