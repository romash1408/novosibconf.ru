<?php
define('PROJECT_NAME', 'jail2017');

return [
	"db" => [
		"server" => "localhost",
		"db" => "novosibconf",
		"user" => "root",
		"password" => ""
	],
	"project_info" => include("projects/" . PROJECT_NAME . ".php"),
	"password" => "test",
	"mail_getters" => [
		"jail2017" => ["orehov19@gmail.com"],
	],
	"sendmail" => [
		"type" => "mail",
		"from" => "system@novosibconf.ru",
		"debug" => false,
	],
];
