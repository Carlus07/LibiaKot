<?php

$test = "00011";

$test = intval($test);
echo $test;
echo floor(log10($test) + 1);
for ($i = 0; $i < 5-floor(log10($test) + 1); $i++)
{
	$test = '0'.$test;
}
echo $test;