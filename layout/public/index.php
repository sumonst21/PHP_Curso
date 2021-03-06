<?php

//Read configuration
include_once '../application/controllers/helpers/actionHelpersFunctions.php';
$config = readConfig('../application/configs/config.ini','mysql');
$typeDataSave=$config['typeDataSave']; //production/development/googlepru/mysql]

define ('NO_ACTION', 'no_action');
define ('NO_CONTROLLER', 'no_controller');

// Include DataGateways
// Include actionHelpers
// Include viewHelpers
// Include Models
if($typeDataSave=='google'){
	include_once '../application/models/dataGatewayGoogle.php';
	include_once '../application/models/files/functions.php';
	include_once '../application/models/files/filesFunctions.php';
} else if($typeDataSave=='mysql'){
	include_once '../application/models/dataGatewayMysql.php';
	include_once '../application/models/mysql/functions.php';
	include_once '../application/models/mysql/filesFunctions.php';
} else {
	include_once '../application/models/dataGatewayFiles.php';
	include_once '../application/models/files/functions.php';
	include_once '../application/models/files/filesFunctions.php';
}
include_once '../application/views/helpers/helpersFunctions.php';
include_once '../application/controllers/helpers/viewsFunctions.php';

// Include Front functions
include_once('../application/frontFunctions.php');

session_start();
$route=router($config);
$route=acl($route);



//debug('', $_SESSION);

switch ($route['controller']){

	case 'users':
		include ($config['path.controllers']."/users.php");
	break;

	case 'author': //access control list
		include ($config['path.controllers']."/author.php");
	break;
	
	case 'error':
		debug('',$route);
		echo "ERROR";
	break;

	case 'index':
		include ($config['path.controllers']."/index.php");
	break;	

}