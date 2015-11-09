<?php
class InterestManager {
	
	private $connection;
	private $user_id
	
	function __construct($mysqli, $user_id){
		
		$this->connection = $mysqli;
		$this->user_id = $user_id; 
		
		
		
	}
	
	
}?>