<?php 
	class Cliente
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getClientes($categorias = null){

			$concatIdCategoria = $categorias . '-';

			$stmtEmpresas = $this->conection->prepare("select * from tab_clientes where id_categorias like CONCAT('%', :categorias, '%')");
			$stmtEmpresas->bindParam(":categorias", $concatIdCategoria);
			$stmtEmpresas->execute();

			$resultEmpresas = [];

			while($row = $stmtEmpresas->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresas[] = $row;
			}			

			return $resultEmpresas;
		}

		public function getCliente($id = null)
		{
			$stmtEmpresas = $this->conection->prepare("select * from tab_clientes where Id = :id");
			$stmtEmpresas->bindParam(":id", $id);
			$stmtEmpresas->execute();

			$resultEmpresa = null;

			while($row = $stmtEmpresas->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresa = $row;
			}			

			return $resultEmpresa;
		}

		public function clickCliente($id = null){
		
			$query = 'update tab_clientes set cliquesapp = cliquesapp + 1 where Id = :id';
			$stmt = $this->conection->prepare($query);
			$stmt->bindParam(":id", $id);

			if($stmt->execute()){
				return true;
			}
			return false;
		}
	}
 ?>