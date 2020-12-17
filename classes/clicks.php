<?php 
	class Click
	{
		private $conection;

		function __construct($db)
		{
			$this->conection = $db;
		}

		public function click($id = null, $tipo){
		
			$query = "INSERT INTO tab_cliques ( dhcad, quemcriou, $tipo) VALUES (now(), 'Aplicativo Mobile', :id)";
			$stmt = $this->conection->prepare($query);
			$stmt->bindParam(":id", $id);

			if($stmt->execute()){
				return true;
			}
			return false;
		}
	}
 ?>