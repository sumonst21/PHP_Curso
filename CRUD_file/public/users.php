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

//include Models
include_once '../application/models/functions.php';
include_once '../application/models/filesFunctions.php';
include_once '../application/models/users/usersFunctions.php';

//read configuration
$config = readConfig('../application/configs/config.ini','production');
//$config = parse_ini_file('../application/configs/config.ini',true);
$userFilename = $config['userFilename'];
$pathUpload = $config['uploadDirectory'];

//select action.
switch ($action){
	case 'insert':
		if($_POST){
			$name=updatePhoto('', $pathUpload);
			$_POST[]=$name;
			writeDataToFile($userFilename, $_POST, FALSE);
			header('Location: /users.php');
			exit;
		}
		else {//entrada en insert, leo y pongo los datos
			$pets=array();
			$sports=array();
			include_once('../application/views/forms/user.php');
		}		
		include_once('../application/views/forms/user.php');
	break;
	
	case 'update':
		if($_POST){
			$dataFileArray=readDataFromFile($userFilename);
			$user=$dataFileArray[$_POST['id']];					
			$name=updatePhoto($user[11], $pathUpload);			
			$_POST[]=$name;
			$dataFileArray[$_POST['id']]=$_POST;			
			writeDataToFile($userFilename, $dataFileArray, TRUE);			
			header('Location: /users.php');
			exit;
		}
		else {//entrada en update, leo y pongo los datos
			$dataArray=readDataFromFile($userFilename);
			$usuario=$dataArray[$_GET['id']];		
			$pets=commaToArray($usuario[8]);
			$sports=commaToArray($usuario[9]);		
			include_once('../application/views/forms/user.php');
		}
	break;
	
	case 'delete':
		if(!$_POST){ //ininio pregunta Si o no 
			$arrayDatos=readDataFromFile($userFilename);
			$usuario = $arrayDatos[$_GET['id']];
			include_once('../application/views/users/delete.php');
		}
		else{
			if($_POST['submit']=='Si'){
				$dataFileArray=readDataFromFile($userFilename);	
				$userdata=$dataFileArray[$_GET['id']];
				deleteFile($userdata[11],$pathUpload);
				unset($dataFileArray[$_GET[id]]);
				writeDataToFile($userFilename, $dataFileArray, TRUE);				
			}
			header('Location: /users.php');
			exit;
		}
	break;
	
	case 'select':
		$arrayLine=readDataFromFile($userFilename);
		include_once('../application/views/users/select.php');
	break;
		
	default:
		echo('this is default');
	break;		
}