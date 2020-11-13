<?php 

	include_once '../config/cors.php';
	include_once '../config/database.php';
	include_once '../classes/clientes.php';

	$database = new Database();
	$connection = $database->getConnection();

	$classClientes = new Cliente($connection);
	
	$action = isset($_GET['action']) ? $_GET['action'] : NULL;

	$result = [
		'success' => true,
		'message' => ''
	];

	switch ($action) {
		case 'all':
			$categorias = isset($_GET['categorias']) ? $_GET['categorias'] : NULL;
			$result['clientes'] = $classClientes->getClientes($categorias);
			break;
		case 'single':
			$id = isset($_GET['id']) ? $_GET['id'] : NULL;
			$result['clientes'] = $classClientes->getCliente($id);
			break;
		case 'click':
			$action = isset($_GET['id']) ? $_GET['id'] : NULL;
			$clickVerify = $classClientes->clickCliente($action);

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