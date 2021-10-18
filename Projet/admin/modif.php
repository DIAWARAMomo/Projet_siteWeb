<?php
	/*
		Affiche les utilisateurs
	*/
	function aff1($co) {
		$req='SELECT * FROM users;';
		$querry=mysqli_query($co,$req);
		echo '<h1> Supprimer des utilisateurs </h1>';
		echo '<form action="#nav" method="POST"><table><tr><th> Selection </th><th> ID </th><th> Nom : </th><th> Prenom : </th><th> Pseudo : </th></tr>';
		while($i=mysqli_fetch_assoc($querry)) {
			echo '<tr><td><input type="checkbox" name="del1[]" value="'.$i["id"].'"></td>';
			echo '<td>'.$i["id"]."</td><td>".$i["prenom"]."</td><td>".$i["nom"]."</td><td>".$i["pseudo"]."</td></tr>";
		}
		echo '</table><input type="submit" value="Supprimer"></form>';
	}
	/*
		Affiche les travaux
	*/
	function aff2($co) {
		$req='SELECT * FROM travaux;';
		$querry=mysqli_query($co,$req);
		echo '<h1> Supprimer des travaux </h1><form action="#nav" method="POST" class="travaux"><table><tr><th> Selection </th><th> ID </th><th> Nom : </th><th colspan="3"> Contenu : </th><th> Images : </th><th> Documents : </th><th> Pdfs : </th> </tr>';
		while($i=mysqli_fetch_assoc($querry)) {
			echo '<tr><td><div><input type="checkbox" name="del2[]" value="'.$i["id"].'"></td>';
			echo "<td>".$i["id"]."</td><td>".$i["nom"].'</td>';
			echo '<td  colspan="3">';
			if($i["text"]!=null)
				echo '<textarea name="contenu" id="contenu" rows="70" spellcheck="false" readonly="1" class="text">'.$i["text"]."</textarea>";
			else
				echo "none";
			echo '</td>';
			/* Caractere interdit pour fichier */
			$interdit=array("<",">","|"," ",":","*","?",'"',"\\","/");
			/* nom du repertoir ou seront mis les fichiers */
			$rep=str_replace($interdit,"-",substr($i["id"].$i["nom"],0,6));
			echo "<td>";
			if($i["image"]) {
				voir($rep,"images_projet");
			}
			else {
				echo "none";
			}
			echo "</td>";
			echo "<td>";
			if($i["document"]) {
				echo '<a href="../documents/'.$rep.'.zip"> Télécharger </a>';
			}
			else {
				echo "none";
			}
			echo "</td>";
			echo "<td>";
			if($i["pdf"]) {
				voir($rep,"pdf");
			}
			else {
				echo "none";
			}
			echo "</td>";
			echo '</tr>';
		}
		echo '</table><input type="submit" value="Supprimer"></form>';
	}
	/* 
		Supprime les éléments selectionnés 
	*/
	function del ($connexion,$table,$inf) {
		if(isset($inf) && sizeof($inf)>0 ) {
			foreach($inf as $i) {
				$req='SELECT * FROM '.mysqli_real_escape_string($connexion,$table).' WHERE id='.mysqli_real_escape_string($connexion,$i).';';
				$querry=mysqli_query($connexion,$req);
				$info=mysqli_fetch_assoc($querry);
				if($table=="users") {
					$req2='SELECT id FROM travaux WHERE author='.$info["id"].';';
					$querry2=mysqli_query($connexion,$req2);
					$array=array();
					while($delete=mysqli_fetch_assoc($querry2)) {
						$array[]=$delete["id"];
					}
					del($connexion,"travaux",$array);
				}
				if($table=="travaux") {
					/* Caractere interdit pour fichier */
					$interdit=array("<",">","|"," ",":","*","?",'"',"\\","/");
					/* nom du repertoir ou seront mis les fichiers */
					$rep=str_replace($interdit,"-",substr($s["id"].$s["nom"],0,6));
					if(isset($s["document"]) && $s["document"]) {
						unlink("../documents/".$rep.'.zip');
					}
					if(isset($s["image"]) && $s["image"]) {
						supprimedir('../images_projet/'.$rep);
					}
					if(isset($s["pdf"]) && $s["pdf"]) {
						supprimedir('../pdf/'.$rep);
					}
				}
				$req='DELETE FROM '.mysqli_real_escape_string($connexion,$table).' WHERE id='.mysqli_real_escape_string($connexion,$i).';';
				$qe=mysqli_query($connexion,$req);
				if($qe) {
					echo "Ligne d'id ".htmlspecialchars($i).' de la table '.htmlspecialchars($table).' a bien été supprimée <br/>';
				}
				else {
					echo "Erreur lors de la ligne d'id ".htmlspecialchars($i).' de la table '.htmlspecialchars($table).'<br/>';
				}
			}
		}
	}
	
	/*
		supprime repertoire et son contenu
	*/
	function supprimedir($detruit) {
		if($dir=opendir($detruit)) {
			while($fichier = readdir($dir)) {
				if($fichier!="." && $fichier!="..") {
					unlink($detruit.'/'.$fichier);
				}
			}
			closedir($dir);	
			rmdir($detruit);
		}
	}
	/*
		Ajoute un element dans une table
	*/
	function add($connexion,$table,$inf) {
		if(isset($inf) && strlen($inf)>0) {
			$req='INSERT INTO '.mysqli_real_escape_string($connexion,$table).'(nom) VALUES("'.mysqli_real_escape_string($connexion,$inf).'");';
			$qe=mysqli_query($connexion,$req);
			if($qe) {
				echo htmlspecialchars($inf)." ajouté à la table ".htmlspecialchars($table).'<br/>';
			}
			else {
				echo 'erreur lors de l\'ajout de '.htmlspecialchars($inf)." dans la table ".htmlspecialchars($table).'<br/>';
			}
		}
	}
	function voir ($rep,$type) {
		if($doss = opendir("../".$type."/".$rep)) {
			$compter=1;
			while($d = readdir($doss)){
				if($d != '.' && $d != '..'){
					echo '<a href="../'.$type.'/'.$rep.'/'.$d.'" target="_blank"> Voir '.$compter.' </a><br/>';
					$compter++;
				}
			}
			closedir($doss);
		}
		else {
			echo "erreur";
		}
	}
?>