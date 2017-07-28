<?php
//include helper functions
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/helpers/strings.php');

//split out the URI to determine view
$url = parse_url($_SERVER['REQUEST_URI']);
$path = explode("/", $url['path']);

$path = array_filter($path, function($value) { return $value !== ''; }); //remove empty values
$path = array_values($path); //rekey the array

switch ($path[count($path)-1]):
	case "list":
	default:
		include ("views/list.php");
		break;
	case "create":
		include ("views/edit.php");
		break;
	case "edit":
		include ("views/edit.php");
		break;
endswitch;