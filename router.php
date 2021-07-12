<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require 'lib.php';

function renderListing()
{
	echo '<!DOCTYPE html>';
	echo '<html>';
	echo '<body>';

	td($_SERVER);

	phpinfo();
}

renderListing();
