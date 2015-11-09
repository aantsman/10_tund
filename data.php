<?php
    // kõik mis seotud andmetabeliga, lisamine ja tabeli kujul esitamine
    require_once("functions.php");
    require_once("InterestManager.class.php");
	
	
    //kui kasutaja ei ole sisse logitud, suuna teisele lehele
    //kontrollin kas sessiooni muutuja olemas
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
		//ära enne suunamist midagi rohkem tee
		exit();
    }
    
    // aadressireale tekkis ?logout=1
    if(isset($_GET["logout"])){
        //kustutame sessiooni muutujad
        session_destroy();
        header("Location: login.php");
		exit();
    }
	
	/***************
	*****HALDUS*****
	***************/
	
	$InterestManager = new InterestManager($mysqli,
	$_SESSION["user_id"]);
	
	if(isset($_POST["new_interest"])){
		
		$add_interest_response=$InterestManager->addInterest($_POST["new_interest"]);
	}
	
?> 

<p>
Tere, <?=$_SESSION['user_email'];?> <a href="?logout=1">Logi välja</a>
</p>

<h2> Lisa huviala </h2>

  <form action="data.php" method="post" >
  	<input name="new_interest"<br>
  	<input type="submit" name="interest" value="Lisa huviala">
  </form>

    <?php if(isset($add_interest_response->success)): ?>
	
	<p style="color:green;">
	<?=$add_interest_response->success->message;?>
	</p>
  
  <?php elseif(isset($add_interest_response->error)): ?>
  
	<p style="color:red;">
	<?=$add_interest_response->error->message;?>
	</p>
  
  <?php endif; ?>
  
<h2> Minu huvialad </h2>

<?=$InterestManager->createDropdown();?>
