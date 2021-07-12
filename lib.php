<?php

function H($v) { return htmlspecialchars($v); }
function U($v) { return rawurlencode($v); }
function HU($v) { return htmlspecialchars(rawurlencode($v)); }

function td(...$a)
{
	echo '<pre>';
	foreach ($a as $v) {
		ob_start();
		var_dump($v);
		echo H(ob_get_clean()); }

	echo "--\ntd()";
	die(1);
}
