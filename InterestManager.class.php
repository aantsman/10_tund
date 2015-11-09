<?php
class InterestManager {
	
	private $connection;
	private $user_id;
	
	function __construct($mysqli, $user_id){
		
		$this->connection = $mysqli;
		$this->user_id = $user_id; 
		
	}
	
	function addInterest($name){
		
		//objekt kus tagastame errori (id, message) või successi (message)
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
			$error->message = "Huviala '".$name."' on juba olemas!";
			$response->error=$error;
			//pärast return käsku ei vaadata funki edasi
			return $response;
		}
		
		$stmt->close();
		
		//kui pole, lisad uue 
		
        $stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
        $stmt->bind_param("s", $name);
		
        if ($stmt->execute()){
			//sisestamine õnnestus
			$success=new StdClass();
			$success->message= "Huviala edukalt lisatud";
			$response->success=$success;
			
		}
		else{
			//ei õnnestunud
			$error=new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki.";
			$response->error=$error;			
		}
        $stmt->close();
		
		return $response;
	}
	
	
	function createDropdown(){
		
		$html='<select name="dropdown_interest">';
		
		//liidan eelmisele juurde
		$html .='<select>';
			
			$stmt= $this->connection->prepare("SELECT id, name FROM interests");
			$stmt->bind_result($id, $name);
			$stmt->execute();
			
			
			//iga rea kohta
			while($stmt->fetch()){
				$html .='<option>'.$name.'</option>';
			}
			
			$stmt->close();
			
			//$html .='<option>Test 2</option>';
		$html .='</select>';
		
		return $html;
	}
	
}?>