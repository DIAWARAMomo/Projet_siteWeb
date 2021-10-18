<!DOCTYPE html>
<html>
	<head>
		<?php 
		session_start();
		require_once("bases.php"); 
		require_once("options.php"); 
		require_once("validation.php");
		require_once("connection.php");			
		options($_POST); 
		?>
		<title> Accueil </title>
		<link rel="stylesheet" type="text/css" href="./css/stylesheet.css"> 
		<link rel="stylesheet" type="text/css" href="./css/speciaux.css"> 
		<link rel="stylesheet" type="text/css" href="./css/erreurs.css"> 
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<!--
			name="viewport" -> affichage par rapport à l'ecran de l'appareil - 'zoom initial' (pour eviter d'avoir des textes tres petit sur telephone par exemple)
			content="width=device-width -> largeur de la fenetre par rapport a l'ecran de l'appareil (ici prend tout l'ecran)
					initial-scale=1.0 -> niveau de zoom initial (1 -> voit toute la page)
					minimum-scale=1.0 -> zoom minimal (empeche dezoom ici)
		-->
	</head>
	<body>
		<?php $connexion=connexion(); 
		if(isset($_GET["re"])) {
			if(file_exists($_GET["re"]) && !preg_match("#\.[\.\/]#",$_GET["re"])) {
				$_SESSION["re"]=$_GET["re"];
				$er=1;
			}
			unset($_GET["re"]);
		}
		if($connexion) {
			//connexion
			if(connex($connexion,$_POST,$_SESSION)) {
				reussi("connexion reussite",1);
				unset($_POST["psw"]);
				unset($_POST["pseudo"]);
				if(isset($_SESSION["re"]) && file_exists($_SESSION["re"])) {
					$redi=$_SESSION["re"];
					unset($_SESSION["re"]);
					header("Location:".$redi."#contenu");
					exit;
				}
			}
			//activation compte ( test : .../accueil.php?id=1&code_activation=0 )
			if (isset($_GET["id"]) && isset($_GET["code_activation"])) {
				validation($connexion,$_GET["id"],$_GET["code_activation"]);
				unset($_GET["id"]);
				unset($_GET["code_activation"]);
			}
				
		}
		if(isset($er)) {
			erreur("connectez vous pour acceder à cette page",1);
		}
		?>
		<header>
			<!-- barre de navigation haute -->
			<nav class="nav">
				<!-- bar de recherche haute : disparait sur mobile -->
				<div class="petit">
					<form action="accueil.php#contenu" method="GET">
						<input type="text" placeholder="Rechercher"  name="search" <?php if(isset($_GET["search"])) { echo 'value="'.htmlspecialchars($_GET["search"]).'"'; } ?>>
						<input type="submit" value="OK">
					</form>
				</div>
				<!-- formulaire de connection/mes infos -->
				<?php infos($connexion,$_SESSION); ?>
			</nav>
			<div class="imgs">
			</div>
			<!-- bar de navigation avec accueil -->
			<nav style="position: relative; top: -5px;"> <!-- evite espacement -->
				<a href="accueil.php"> accueil </a>
				<div class="petit2 button">
					<!-- bar de recherche:apparait sur mobile -->
					<form action="accueil.php#contenu" method="GET">
						<input type="text" placeholder="Rechercher" name="search" style="border-style:hidden;" <?php if(isset($_GET["search"])) { echo 'value="'.htmlspecialchars($_GET["search"]).'"'; } ?>>
						<input type="submit" value="OK">
					</form>
				</div>
			</nav>
		</header>
		<main id="contenu">
			<!-- bar de navigation basse : information de recherche -->
			<nav>
				<form method="POST" action="#contenu">
					<div>
						<div class="type">
							<label for="td" class="pointer"> TD </label>
							<input type="checkbox" name="TD" id="td" class="button" <?php if( !isset($_COOKIE["td"])) { echo "checked"; } ?> >
							<label for="td" class="pointer"><img alt="~" src="images/valide.png"></label>
						</div>
						<div class="type">
							<label for="tp" class="pointer"> TP </label>
							<input type="checkbox" id="tp" name="TP" class="button" <?php if( !isset($_COOKIE["tp"])) { echo "checked"; } ?> > 
							<label for="tp" class="pointer"><img alt="~" src="images/valide.png"></label>
						</div>
					</div>
					<div class="select">
						<label for="matières"> Matières : </label>
						<?php selector("matières",$connexion,1); ?>
					</div>
					<div class="select">
						<label for="filières"> Filières : </label>
						<?php selector("filières",$connexion,1); ?>
					</div>
					<div class="select">
						<label for="op3"> Année : </label>
						<input name="ann" type="number" min="0" max="8" value=<?php if(!isset($_COOKIE["annee"])) { echo "0";} else { echo $_COOKIE["annee"]; } ?> >
					</div>
					<div class="select">
						<label for="universités"> Universités : </label>
						<?php selector("universités",$connexion,1);  ?>
					</div>
					<input type="submit" value="VOIR" style="font-weight:bold">
					<input type="hidden" name="verif" value="1">
				</form>
			</nav>
			<article>
			  <?php if($connexion) { affi($connexion,$_COOKIE,$_GET,0); } ?>
		
		</main>
		<?php mysqli_close($connexion); ?>

	</body>
</html>
