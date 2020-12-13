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
				select c.* , v.*,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', f.imagem) as fachada,
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio ,'/', l.imagem) as logo, cs.nome
				from tab_clientes  c
				inner join  tab_clientes_fachada f on c.Id = f.id_cliente 
				left join  tab_clientes_videos v on c.Id = v.id_cliente 
				left join  tab_cidades cs on cs.Id = c.end_cidade 
				inner join tab_clientes_logotipo l on c.Id = l.id_cliente
				where c.Id = :id and v.destaque = 'on' limit 1");

			
			$stmtEmpresas->bindParam(":id", $id);
			$stmtEmpresas->execute();

			$resultEmpresa = null;

			while($row = $stmtEmpresas->fetch(PDO::FETCH_ASSOC)){
				$resultEmpresa = $row;
			}			

			return $resultEmpresa;
		}

		public function getClienteVideos($id = null)
		{
			$stmt = $this->conection->prepare("
				SELECT 
				*
				FROM tab_clientes_videos
				where id_cliente = {$id}
			");
			$stmt->execute();

			$videos = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$videos[] =  $row;
			}			

			return $videos;
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