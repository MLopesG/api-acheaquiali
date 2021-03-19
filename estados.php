<?php 

	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/estados.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classEstados = new Estado($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$result['estados'] = $classEstados->getEstados();
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>