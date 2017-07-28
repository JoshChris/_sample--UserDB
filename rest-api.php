<?php
//include helpers
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/helpers/strings.php');

//include controllers
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/controllers/user.php');

try {
    $API = new TonicApp\UserAPI($_REQUEST['request']);
    echo $API->process();
} catch (\Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}