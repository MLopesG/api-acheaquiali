<?php 

	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/clicks.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classClick = new Click($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'register':
			$id = isset($_GET['id']) ? $_GET['id'] : null;
			$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

			$clickVerify = $classClick->click($id, $tipo);

			if($clickVerify){
				$result['message'] = 'Click registrado!';
			}else{
				$result['success'] = false;
			 	$result['message'] = 'Não foi possivel registrar click';
			}

			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>