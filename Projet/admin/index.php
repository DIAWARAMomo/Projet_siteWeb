<!DOCTYPE html>
<html>
	<head>
		<title> Admin </title>		
		<link rel="stylesheet" type="text/css" href="./css/style.css"> 
		<link rel="stylesheet" type="text/css" href="./css/stylespe.css"> 
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	</head>
	<body>
	<header>
		<nav id="nav">
			<ul>
				<li><a href="#aff1"> Supprimer des utilisateurs </a></li>
				<li><a href="#aff2"> Supprimer des travaux </a> </li>
				<li><a href="#mat"> Ajout Matière </a></li>
				<li><a href="#uni"> Ajout Université </a></li>
				<li><a href="#fil"> Ajout Filière </a> </li>
			</ul>
		</nav>
	</header>
<?php
	require_once("../bases.php");
	require_once("modif.php");
	$connexion=connexion();
	if($connexion) {
		del($connexion,"users",$_POST["del1"]);
		del($connexion,"travaux",$_POST["del2"]);
		add($connexion,'matières',$_POST["add1"]);
		add($connexion,'universités',$_POST["add2"]);
		add($connexion,'filières',$_POST["add3"]);
		echo '<br id="aff1">';
		aff1($connexion);
		echo '<a href="#nav" id="aff2"> Retour </a>';
		aff2($connexion);
	}
	mysqli_close($connexion);
	
?>
	<a href="#nav"> Retour </a>
	<h1 id="mat"> Ajout Matière </h1>
	<form method="POST" action="#nav">
		<input type="text" placeholder="Nom Matière" name="add1">
		<input type="submit" value="Ajouter la matière">
	</form>
	<h1 id="uni"> Ajout Université </h1>
	<form method="POST" action="#nav">
		<input type="text" placeholder="Nom Université" name="add2">
		<input type="submit" value="Ajouter l'université">
	</form>
	<h1 id="fil"> Ajout Filière </h1>
	<form method="POST" action="#nav">
		<input type="text" placeholder="Nom Filière" name="add3">
		<input type="submit" value="Ajouter la filière">
	</form>
	</body>
</html>