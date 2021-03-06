<?php   //tmp in the public folder

/**
 * User controller
 * @version 1.0
 * 
 */

if(isset($_GET['action']))
	$action=$_GET['action'];
else
	$action='select';	



//read configuration 
include_once '../application/controllers/helpers/actionHelpersFunctions.php';
$config = readConfig('../application/configs/config.ini','mysql');
$typeDataSave=$config['typeDataSave']; //production/development/googlepru/mysql]

//include DataGateways
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

//include Models
include_once '../application/models/users/usersFunctions.php';

include_once '../application/views/helpers/helpersFunctions.php';


//select action.
switch ($action){
	case 'insert':
		if($_POST){
			insertUser($config,$_POST);
			header('Location: /users.php');
			exit;
		}
		else {//entrada en insert, leo y pongo los datos
			//$pets=array();
			//$sports=array();
			$user=initUser();
			include_once('../application/views/forms/user.php');
		}		
	break;
	
	case 'update':
		if($_POST){
			//debug('user',$_POST,TRUE);
			updateUser($config,$_POST['id'],$_POST);
			header('Location: /users.php');
			exit;
		}
		else {//entrada en update, leo y pongo los datos
			$user=initUser();
			$user=readUser($config,$_GET['id']);
			//$pets=$user[8];
			//$sports=$user[9];	
			//$pets=commaToArray($user[8]);
			//$sports=commaToArray($user[9]);
			//debug('user',$user,TRUE);
			include_once('../application/views/forms/user.php');
		}
		
	break;
	
	case 'delete':
		if(!$_POST){ //inicio, pregunta Si o no 
			include_once('../application/views/users/delete.php');
		}
		else{
			if($_POST['submit']=='Si'){
				deleteUser($config,$_GET['id']);
			}
			header('Location: /users.php');
			exit;
		}
	break;
	
	case 'select':
		$users=readUsers($config);
		include_once('../application/views/users/select.php');
	break;
		
	default:
		echo('this is default');
	break;		
}