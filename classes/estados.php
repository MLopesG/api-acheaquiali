<?php
	class Estado 
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function getEstados(){

			$stmt = $this->conection->prepare("SELECT Id,nome,uf from  tab_estados where ativo = 'on'");
			$stmt->execute();

			$estados = [];

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$estados[] =  $row;
			}			

			return $estados;
		}
	}
?>