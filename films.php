<?php

	require("functions.php");
	
	if (!isset ($_SESSION["userId"])) {
		
		header("Location: login.php");
		exit();
	}
	
	//kas ?logout on aadressireal
	if (isset($_GET["logout"])) {
		
		session_destroy ();
		header ("Location: login.php");
		exit();
	}
	
	$title = "";
	$titleError = "";
	$filmtime = "";
	$filmtimeError = "";
	$price = "";
	$priceError = "";
	
	if (isset ($_POST["title"]) ) {
	
		if (empty ($_POST["title"]) ) { 
			$titleError = "Palun täitke see väli!";
		} else {
			$title = $_POST["title"];
		}
	}
	
	
	if (isset ($_POST["filmtime"]) ) {
	
		if (empty ($_POST["filmtime"]) ) { 
			$filmtimeError = "Palun täitke see väli!";
		} else {
			$filmtime = $_POST["filmtime"];
		}
	}
	
	if (isset ($_POST["price"]) ) {
	
		if (empty ($_POST["price"]) ) { 
			$priceError = "Palun täitke see väli!";
		} else {
			$price = $_POST["price"];
		}
	}
	
	if(isset($_POST["title"]) &&
			isset($_POST["filmtime"]) &&
			isset($_POST["price"]) &&
			!empty($_POST["title"]) &&
			!empty($_POST["filmtime"]) &&
			!empty($_POST["price"])
		) {
			Films($_POST["title"], $_POST["filmtime"], $_POST["price"]);
			
		}
	
	$people = AllFilms();
	
?>


<h1>Filmide rentimine</h1>
<a href="user.php"> Mine enda lemmikfilmide lehele &rarr;</a> 
<p> 
	Tere tulemast <?=$_SESSION["email"];?>!
	<a href="?logout=1">Logi välja</a>
</p>
<form method="POST"> 
<label>Filmi nimi</label><br>
			
		<input type="text" name="title" value="<?=$title;?>"> <?php echo $titleError;?> <br><br>
	
<label>Filmi kestus</label><br>
		
		<input type="text" name="filmtime" value="<?=$filmtime;?>"> <?php echo $filmtimeError;?> <br><br>
	
<label>Rendi hind</label><br>
		
		<input type="text" name="price" value="<?=$price;?>"> <?php echo $priceError;?> <br><br>
	
	<input type="submit" value="Salvesta">	
</form>

<h2>Filmid</h2>
<?php

	$html = "<table>";
	
		$html .= "<tr>";
			$html .= "<th>Filmi nimi</th>";
			$html .= "<th>Filmi kestus</th>";
			$html .= "<th>Rendi hind</th>";
		$html .= "</tr>";

	foreach($people as $p) {
		$html .= "<tr>";
			$html .= "<td>".$p->title."</td>";
			$html .= "<td>".$p->filmtime."</td>";
			$html .= "<td>".$p->price."</td>";
		$html .= "</tr>";	
	}

	$html .= "</table>";
	echo $html;
	
?>