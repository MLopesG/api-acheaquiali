<?php
	class Cidade 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getCidades(){

			$stmt = $this->conection->prepare("SELECT Id,nome from  tab_cidades where ativo = 'on' order by nome asc");
			$stmt->execute();

			$cidades = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$cidades[] =  $row;
			}			

			return $cidades;
		}

		public function getCidade($cidade = 1518){

			$stmt = $this->conection->prepare("
				SELECT 
				concat('http://acheiaquiali.com.br/sistema/arquivos/cidades/', imagem) as url,
				c.Id,c.nome 
				FROM tab_cidades c
                left join tab_cidades_fotos on  id_cidade = c.Id
				WHERE c.Id = {$cidade}	and  ativo = 'on'
			");
			$stmt->execute();

			$cidade = null;

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$cidade =  $row;
			}			

			return $cidade;
		}

		public function getCidadeEstados($estado){

			$stmt = $this->conection->prepare("
				SELECT 
				c.Id,c.nome 
				FROM tab_cidades c
				WHERE c.estado = {$estado}	and  ativo = 'on'
			");
			$stmt->execute();

			$cidades = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$cidades[] =  $row;
			}			

			return $cidades;
		}
	}
?>