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
include_once '../application/controllers/helpers/viewsFunctions.php';

//select action.
switch ($action){
	case 'insert':
		if($_POST){
			insertUser($config,$_POST);
			header('Location: /users.php');
			exit;
		}
		else {//entrada en insert, leo y pongo los datos
			ob_start();//buffer
			
			//$pets=array();
			//$sports=array();
			$user=initUser();
			include_once('../application/views/forms/user.php');
			
			$content=ob_get_clean();
			ob_end_flush();
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
			ob_start();//buffer
			
			$user=initUser();
			$user=readUser($config,$_GET['id']);
			//$pets=$user[8];
			//$sports=$user[9];	
			//$pets=commaToArray($user[8]);
			//$sports=commaToArray($user[9]);
			//debug('user',$user,TRUE);
			include_once('../application/views/forms/user.php');
			
			$content=ob_get_clean();
			ob_end_flush();
		}
		
	break;
	
	case 'delete':
		if(!$_POST){ //inicio, pregunta Si o no 
			//ob_start();//buffer
		
			//include_once('../application/views/users/delete.php');
			
			//$content=ob_get_clean();
			//ob_end_flush();
			$content = renderView($config,'users/delete.php');
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
		//ob_start();//buffer 
		//$users=readUsers($config);
		//include_once('../application/views/users/select.php');
		//$content=ob_get_clean();
		//ob_end_flush();
		$users=readUsers($config);
		$viewVars=array('users'=>$users,
						'title'=>"usuarios");
		$content = renderView($config,'users/select.php',$viewVars);
	break;
		
	default:
		echo('this is default');
	break;		
}

//render layout (postdispatch)

//include_once '../application/layout/layout.php';
$layoutVars=array('content'=>$content,
				  'title'=>"Mi aplicacion");
$layout = renderlayout($config,'layout.php',$layoutVars);
echo $layout;