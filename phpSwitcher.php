#!/usr/bin/php
<?php

include "PhpSwitch.class.php";

$user = `whoami`;
if ($user != "root\n") {
	echo "Le script necessite l'uitlisateur root\n"; die();
}

if ($argc == 2) {
	$argv[2] = '';
} else if ($argc != 3) {
	echo "USAGE : ".$argv[0]." VERSION [XAMPP_ARGUMENTS]\n"; die();
}

try {
	$app = new PhpSwitch($argv[1], $argv[2]);
	$app->execute();
} catch (Exception $e) {
	echo 'Erreur : ',  $e->getMessage(), "\n";
}