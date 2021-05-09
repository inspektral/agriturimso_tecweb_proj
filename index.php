<?php
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Agriturismo Morgan</title>

<meta name="title" content="Agriturismo Morgan" />
<meta name="description"
	content="Agriturismo situato nei pressi di Montebelluna (TV), che offre camere e colazione" />
<meta name="keywords"
	content="Agriturismo, Bed and Breakfast, Camere da letto, Montebelluna" />

<meta name="author" content="Matteo, Massimo, Tito, Lorenzo" />
<meta name="language" content="italian it" />

<link rel="stylesheet" type="text/css" href="style/style_lavoro.css"
	media="screen" />

<link rel="stylesheet" type="text/css" href="style/small_lavoro.css"
	media="handheld, screen and (max-width: 640px), only screen and (max-device-width: 640px)" />

<link rel="stylesheet" type="text/css" href="style/printLavoro.css"
	media="print" />

</head>
<body>
	<div id="header">
		<ul class="aiutiNascosti">
			<li><a href="#menu">Vai al menu</a></li>
			<li><a href="#contentHomePage">Vai al contenuto</a></li>
			<li><a href="#ultimeNews">Vai alle ultime news</a></li>
		</ul>
		<img id="logo" src="images/bbtlogo.png"
			alt="Logo dell'agriturismo a forma di casa stilizzata con una a come tetto e una m come muros" />
		<h1>Agriturismo Morgan Morgan</h1>
		<h2>Ai piedi del Montello e dei colli Asolani</h2>
	</div>

	<div id="breadcrumb">
		<div id="menu">
			<ul>
				<li xml:lang="en" id="currentLink">Home</li>
				<li><a href="camere.php">Camere</a></li>
				<li><a href="territorio.php">Territorio</a></li>
				<li><a href="diconodinoi.php">Dicono di noi</a></li>
			</ul>
		</div>
		<div id="user">
		<?php
            if (isset($_SESSION["user"])) {
                echo "<p class='reg'>Benvenuto: " . $_SESSION["user"] . "</p>";
            } else {
                echo '<a href="registrati.html" class="reg">Registrati</a> <a
            				href="accedi.html" class="reg">Accedi</a>';
            }
        ?>
		</div>
	</div>

	<div class="row">
		<div id="contentHomePage" class="contenutoGenerale column">
			<h2 class="center">Benvenuti nella terra del carciofo</h2>

			<img src="images/21.jpg" id="imgIndex"
				alt="foto panoramica dell'agriturismo con un campo di terra di fronte e montagne sullo sfondo" />
			<p>
				Che sia per vacanza o per lavoro, l’<strong>Agriturismo
					Morgan</strong> – situato in posizione strategica ai piedi del <strong>Montello</strong>
				e dei <strong>colli asolani</strong> – è il punto di partenza ideale
				per visitare centri storici e città d’arte come Montebelluna,
				Castelfranco Veneto, Asolo, Treviso, Valdobbiadene e Venezia.
			</p>
			<p>Potete ripercorrere le tappe della Grande Guerra visitando
				monumenti commemorativi, le zone di battaglia lungo il fiume Piave e
				il recentissimo museo del Memoriale Veneto – MEVE – a Biadene di
				Montebelluna.</p>
			<p>
				Avete inoltre un’ampia scelta tra escursioni lungo i sentieri
				limitrofi e nelle vicine Prealpi dolomitiche, percorsi in mountain
				bike o in bicicletta da strada. Vicino inoltre alla famosa <strong>Strada
					del Prosecco e Vini dei Colli Conegliano Valdobbiadene</strong>, formatasi
				nel 2003 come successore della <strong>Strada del Vino
					Bianco</strong>, inaugurata nel lontano <strong>1966, prima arteria
					enologica italiana</strong>. L’azienda è specializzata nella produzione di
				frutta e verdura a km 0, che potete acquistare direttamente in loco.
			</p>
		</div>

		<div id="rightSideBar" class="column right">
			<div id="ultimeNews">
				<h2>Ultime News</h2>
				<ul>
					<li><strong>10/06/2019</strong> - Festa della Musica e delle
						carote</li>
					<li><strong>05/04/2021</strong> - Oggi festa dell' asparago! <br />Buona
						Pasquetta a tutti!</li>
					<li><strong>24/03/2019</strong> - Raccolta degli Asparagi</li>
				</ul>
			</div>
			<div id="adminSection">
				<button id="buttonNews">Gestisci</button>
			</div>
		</div>

	</div>

	<div id="footer">
		<img src="images/valid-xhtml10.png" alt="logo validazione w3c xhtml"
			id="imgValidCode" /> <img src="images/vcss-blue.gif"
			alt="validlogo validazione w3c css" id="imgValidCSS" />
		<p>
			Il sito è stato creato per un esercizio nell'ambito del corso di <a
				href="https://elearning.unipd.it/math/login/index.php">Tecnologie
				Web</a> e non rappresenta quindi il sito ufficiale dell'Agriturismo. I
			testi sono stati gentilmente presi in prestito da <a
				href="http://www.agriturismomorgan.it/">Agriturismo Morgan</a>.
		</p>
		<p>Tech Group - All rights reserved</p>
	</div>
</body>
</html>
