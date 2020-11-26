<?php 

	include_once './config/cors.php';
	include_once './config/database.php';
	include_once './classes/servicos.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classServicos = new Servico($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$id = isset($_GET['id']) ? $_GET['id'] : null;
			$result['servicos'] = $classServicos->getServicos($id);
			break;
		default:
			 $result['success'] = false;
			 $result['message'] = 'Ação não foi encontrado!';
			break;
	}	

	echo json_encode($result);
 ?>