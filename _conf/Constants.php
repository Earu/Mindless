<?php
//if rebuild is false, mindless won't reload .html files
$REBUILD = TRUE;
//if the environment is set to production then
//files won't be reloaded each time, a page is refreshed
$ENV_PRODUCTION = FALSE;

//Errors relative to forms you can create with Mindless
$FORM_ERRORS = [
0 =>  ["message" => "Too Short", "code" => 0],
1 =>  ["message" => "Too Long", "code" => 1],
2 =>  ["message" => "Invalid Pattern", "code" => 2],
3 =>  ["message" => "Doesn't have required value", "code" => 3],
4 =>  ["message" => "Cannot connect to database", "code" => 4],
5 =>  ["message" => "Cannot insert data in database", "code" => 5]
];

//Types of account associated with permissions system
$ACCOUNT_TYPE = [
	0 => "Committee",
	1 => "Club",
	2 => "Reporter",
	3 => "Captain"
];

//services that need to be imported automatically on pages
$SERVICE_AUTO_IMPORT = [
	"Auth"
]
 ?>
