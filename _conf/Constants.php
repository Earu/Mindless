<?php
  //if rebuild is false, mindless won't reload .html files
  $REBUILD = TRUE;
  //if the environment is set to production then
  //files won't be reloaded each time, a page is refreshed
  $ENV_PRODUCTION = FALSE;
   
  //example of a constant table
  $FORM_ERRORS = [
    0 =>  ["message" => "Too Short", "code" => 0],
    1 =>  ["message" => "Too Long", "code" => 1],
    2 =>  ["message" => "Invalid Pattern", "code" => 2],
    3 =>  ["message" => "Doesn't have required value", "code" => 3],
    4 =>  ["message" => "Cannot connect to database", "code" => 4],
    5 =>  ["message" => "Cannot insert data in database", "code" => 5]
  ];
	
  //you can create constants here
  $ACCOUNT_TYPE = [
  ];
  
  //services that need to be imported automatically on pages
  $SERVICE_AUTO_IMPORT = [
  ]
 ?>
