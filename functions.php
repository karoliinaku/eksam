<?php

	
	require("../../config.php");

	session_start();
	
	$database = "if16_karoku";
	
	function signup ($email, $password, $username, $gender) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
	
	function login($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");
		
		echo $mysqli->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDatabase, $passwordFromDatabase, $created);
		$stmt->execute();
		
		//küsin rea andmeid
		if($stmt->fetch()) {
			//oli rida
			
			//võrdlen paroole
			$hash = hash ("sha512", $password);
			if($hash == $passwordFromDatabase) {
				echo "kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDatabase;
				
				//suunaks uuele lehele
				header("Location: films.php");
				exit();
				
			} else {
				$error = "Parool on vale";
			}
			
		} else {
			//ei olnud
			$error = "Sellise emailiga ".$email."kasutajat ei olnud";
			
		}
		
		return $error;
		
	}
		
	function Films ($title, $filmtime, $price) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO Films (title, filmtime, price, user) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("sssi", $title, $filmtime, $price, $_SESSION["userId"]);
		
		if ($stmt->execute()) {
			//echo "Salvestamine õnnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}
		

	function AllFilms() {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("SELECT id, title, filmtime, price FROM Films");
		
		echo $mysqli->error;
		//$stmt->bind_param("i",$_SESSION["userId"]);
		$stmt->bind_result($id, $title, $filmtime, $price);
		$stmt->execute();
		
		$result = array();
		
		while ($stmt->fetch()) {
			
			$person = new StdClass();
			$person->id = $id;
			$person->title = $title;
			$person->filmtime = $filmtime;
			$person->price = $price;
			
			//echo $color."<br>";
			array_push($result, $person);
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $result;
	}
	
	function cleanInput($input) {
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
	function saveUserFavorite ($favorite_id) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id FROM user_filmfavorites WHERE user_id=? AND favorite_id=?");
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			//oli olemas
			echo "Juba olemas";
			
			//ei jätka salvestamisega
			return;
		}
		$stmt->close();
		//jätkan salvestamisega..
		

		$stmt = $mysqli->prepare("INSERT INTO user_filmfavorites (user_id, favorite_id) VALUES (?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"],$favorite_id);
		
		if($stmt->execute()) {
			//echo "Salvestamine õnnestus";
		} else {
		 	//echo "ERROR ".$stmt->error;*/ //proovimiseks, kas töötab
		}
		$stmt->close();
		
	}
	
	function getAllFilms() {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
			SELECT id, title
			FROM Films
		");
		echo $mysqli->error;
		
		$stmt->bind_result($id, $title);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->title = $title;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		
		return $result;
	}
	
	function getUserFavorites() {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		SELECT id, (SELECT title FROM Films WHERE Films.id = user_filmfavorites.favorite_id)FilmName FROM user_filmfavorites
		WHERE user_filmfavorites.user_id = ?");
		
		echo $mysqli->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($id, $favorite);
		$stmt->execute();
	
		$result = array();

		while ($stmt->fetch()) {
			
			$i = new StdClass();
	
			$i->favorite = $favorite;
		
			array_push($result, $i);
		}
		
		$stmt->close();
	
		return $result;
	}

	
?>