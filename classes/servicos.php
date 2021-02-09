<?php
	class Servico 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getPromocoes($id){
			$stmt = $this->conection->prepare("
				SELECT 
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio,'/',  f.imagem) as url,
				f.*
				FROM tab_clientes_fotos f
				inner join tab_clientes c on c.Id = f.id_cliente
				WHERE c.Id = {$id} and promocao = 'on'

			");
			$stmt->execute();

			$promocoes = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$promocoes[] =  $row;
			}			

			return $promocoes;
		}

		public function getServicos($id){

			$stmt = $this->conection->prepare("
				SELECT 
				concat('http://acheiaquiali.com.br/sistema/arquivos/clientes/', c.diretorio,'/',  f.imagem) as url,
				f.*
				FROM tab_clientes_fotos f
				inner join tab_clientes c on c.Id = f.id_cliente
				WHERE c.Id = {$id}

			");
			$stmt->execute();

			$servicos = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$servicos[] =  $row;
			}			

			return $servicos;
		}
	}
?>