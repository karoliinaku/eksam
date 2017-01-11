<?php

	require("functions.php");

	
	if ( isset($_POST["favorite"]) && 
		!empty($_POST["favorite"])
	  ) {
		  
		saveFavorite(cleanInput($_POST["favorite"]));
		
	}
	
	if ( isset($_POST["userFavorite"]) && 
		!empty($_POST["userFavorite"])
	  ) {
		  
		saveUserFavorite(cleanInput($_POST["userFavorite"]));
		
	}
	
    $favorites = getUserFavorites();
	$titles = getAllFilms();
?>

<a href="films.php"> &larr; Tagasi filmide lehele</a> 
<h2>Minu lemmikfilmid:</h2>

<?php
    
    $listHtml = "<ul>";
	
	foreach($favorites as $i){
		
		$listHtml .= "<li>".$i->favorite."</li>";
	}
    
    $listHtml .= "</ul>";
	echo $listHtml;
    
?>

<form method="POST">
<br>
<h3><label>Vali film:</label></h3>
<select name="userFavorite" type="text">

<?php
					
	$listHtml = "";
					
	foreach($titles as $t){		
		$listHtml .= "<option value='".$t->id."'>".$t->title."</option>";}
	echo $listHtml;   
?>
</select>
				
<input type="submit" value="Salvesta lemmikuks">
			
</form>