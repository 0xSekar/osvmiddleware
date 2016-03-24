<?php

function connect()
{
	//database connection
	$db_host = "localhost";
	$db_user = "wsuser";
	$db_pass = "jjun0366";
	$db_name = "jjun0366_osvmember";

	mysql_connect($db_host, $db_user , $db_pass) or die(mysql_error());
	mysql_select_db($db_name) or die(mysql_error());


}

function connectfe()
{
        //database connection
        $db_host = "localhost";
        $db_user = "wsuser";
        $db_pass = "jjun0366";
        $db_name = "jjun0366_frontend";

        mysql_connect($db_host, $db_user , $db_pass) or die(mysql_error());
        mysql_select_db($db_name) or die(mysql_error());


}

function connectli()
{

	$mysqli = new mysqli("localhost", "wsuser", "jjun0366", "jjun0366_osvmember");
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	return $mysqli;
}

function getrealip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
