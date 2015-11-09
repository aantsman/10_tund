<?php
class InterestManager {
	
	private $connection;
	private $user_id;
	
	function __construct($mysqli, $user_id){
		
		$this->connection = $mysqli;
		$this->user_id = $user_id; 
		
	}
	
	function addInterest($name){
		
		//objekt kus tagastame errori (id, message) v�i successi (message)
		$response= new StdClass();
		
		//kontrollid et sellist huviala veel ei ole
		$stmt = $this->connection->prepare("SELECT id FROM interests WHERE name=?");
		$stmt->bind_param("s", $name);
		$stmt->bind_result($id);
        $stmt->execute();	
        
		//kas saime rea andmeid
		if($stmt->fetch()){
			//huviala on juba olemas
			$error=new StdClass();
			$error->id = 0;
			$error->message = "Selline huviala juba olemas!";
			$response->error=$error;
			//p�rast return k�sku ei vaadata funki edasi
			return $response;
		}
		
		$stmt->close();
		
		//kui pole, lisad uue 
		
        $stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
        $stmt->bind_param("s", $name);
		
        if ($stmt->execute()){
			//sisestamine �nnestus
			$success=new StdClass();
			$success->message= "Huviala edukalt lisatud";
			$response->success=$success;
			
		}
		else{
			//ei �nnestunud
			$error=new StdClass();
			$error->id = 1;
			$error->message = "Midagi l�ks katki.";
			$response->error=$error;			
		}
        $stmt->close();
		
		return $response;
		
		
	}
	
}?>