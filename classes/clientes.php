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

			$stmtEmpresas = $this->conection->prepare("select * from tab_clientes where id_categorias like CONCAT('%', :categorias, '%') ");
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
			$stmtEmpresas = $this->conection->prepare("
				select c.* ,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', f.imagem) as fachada,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', l.imagem) as logo
				from tab_clientes  c
				inner join  tab_clientes_fachada f on c.Id = f.id_cliente 
				inner join tab_clientes_logotipo l on c.Id = l.id_cliente
				where c.Id = :id");

			
			$stmtEmpresas->bindParam(":id", $id);
			$stmtEmpresas->execute();

			$resultEmpresa = null;

			while($row = $stmtEmpresas->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresa = $row;
			}			

			return $resultEmpresa;
		}

		public function clickCliente($id = null){
		
			$query = "INSERT INTO tab_cliques ( dhcad, quemcriou, id_empresa) VALUES (now(), 'Aplicativo Mobile', :id)";
			$stmt = $this->conection->prepare($query);
			$stmt->bindParam(":id", $id);

			if($stmt->execute()){
				return true;
			}
			return false;
		}
	}
 ?>