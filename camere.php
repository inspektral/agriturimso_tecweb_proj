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
			<li><a href="#contentCamere">Vai al contenuto</a></li>
			<li><a href="#ultimeNews">Vai alle ultime news</a></li>
		</ul>
		<img id="logo" src="images/bbtlogo.png"
			alt="Logo dell'agriturismo costituito da una M e una A stilizzate" />
		<h1>Agriturismo Morgan</h1>
		<h2>Ai piedi del Montello e dei colli Asolani</h2>
	</div>

	<div id="breadcrumb">
		<div id="menu">
			<ul>
				<li><a href="index.php" xml:lang="en">Home</a></li>
				<li id="currentLink">Camere</li>
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

		<div class="contenutoGenerale column">
			<div class="contenutoGenerale column">
				<div class="bedroom">
					<h2>Camera Matrimoniale</h2>
					<h3>
						<span>2 Persone</span> <span><abbr title="euro">€</abbr> <span
							class="price">62,30</span> a notte</span>
					</h3>
					<img src="./images/matrimoniale.jpg" longdesc="./rooms-longdescs/matrimoniale.txt" />
					<ul>
						<li>Buona colazione inclusa</li>
						<li>Cancellazione <strong class="free">GRATUITA</strong></li>z
						<li><strong>NESSUN PAGAMENTO ANTICIPATO</strong>
							<div>Paga in struttura</div></li>
					</ul>
					<div class="services">
						<dl>
							<dt>Bagno privato</dt>
							<dt>Wi-Fi gratis!</dt>
							<dt>
								Superficie camera 12 <abbr title="metri quadrati">mq.</abbr>
							</dt>
							<dt>Parcheggio privato disponibile gratuitamente in loco
								senza prenotazione.</dt>
							<dd>Gratis!</dd>
						</dl>
						<div>
							<h4>Servizi in camera:</h4>
							<ul>
								<li>Balcone</li>
								<li>Vista giardino</li>
								<li>TV a schermo piatto</li>
								<li>Aria condizionata</li>
								<li>Riscaldamento</li>
								<li><span xml:lang="fr">Parquet</span> o pavimento in legno</li>
								<li>Armadio o guardaroba</li>
								<li>Doccia</li>
								<li>Prodotti da bagno in omaggio</li>
								<li><abbr xml:lang="en" title="water closet">WC</abbr></li>
								<li>Bagno privato</li>
								<li>Vasca</li>
								<li xml:lang="fr">Bidet</li>
								<li>Carta igienica</li>
								<li>Asciuga mani</li>
							</ul>
						</div>
					</div>
					<p class="prenota_Button">
						<a class="bottoneform" href="prenota.html">Prenota</a>
					</p>
					<div class="gallery">
						<img src="./images/3.jpg" alt="" class="galleryElement" /> <img
							src="./images/4.jpg" alt="" class="galleryElement" /> <img
							src="./images/6.jpg" alt="" class="galleryElement" /> <img
							src="./images/7.jpg" alt="" class="galleryElement" />
					</div>
				</div>

				<div class="bedroom">
					<h2>Camera Tripla</h2>
					<h3>
						<span>3 Persone</span> <span><abbr title="euro">€</abbr> <span
							class="price">79,50</span> a notte</span>
					</h3>
					<img src="./images/1-1.jpg" alt=""
						longdesc="./rooms-longdescs/tripla.txt" />
					<ul>
						<li>Buona colazione inclusa</li>
						<li>Cancellazione <strong class="free">GRATUITA</strong></li>
						<li><strong>NESSUN PAGAMENTO ANTICIPATO</strong>
							<div>Paga in struttura</div></li>
					</ul>
					<div class="services">
						<dl>
							<dt>Wi-Fi gratis!</dt>
							<dt>Parcheggio privato disponibile gratuitamente in loco
								senza prenotazione.</dt>
						</dl>
					</div>
					<p class="prenota_Button">
						<a class="bottoneform" href="prenota.html">Prenota</a>
					</p>
					<div class="gallery">
						<img src="./images/2-1.jpg" alt="" class="galleryElement" /> <img
							src="./images/3-1.jpg" alt="" class="galleryElement" /> <img
							src="./images/4-1.jpg" alt="" class="galleryElement" />
					</div>
				</div>

				<div class="bedroom">
					<h2>Camera Singola</h2>
					<h3>
						<span>1 Persona</span> <span><abbr title="euro">€</abbr> <span
							class="price">46</span> a notte</span>
					</h3>
					<img src="./images/2-2.jpg" alt=""
						longdesc="./rooms-longdescs/singola.txt" />
					<ul>
						<li>Buona colazione inclusa</li>
						<li>Cancellazione <strong class="free">GRATUITA</strong></li>
						<li><strong>NESSUN PAGAMENTO ANTICIPATO</strong>
							<div>Paga in struttura</div></li>
					</ul>
					<div class="services">
						<dl>
							<dt>Bagno privato</dt>
							<dt>
								Superficie camera 10 <abbr title="metri quadrati">mq.</abbr>
							</dt>
							<dt>Camera singola climatizzata</dt>
							<dt>Parcheggio privato disponibile gratuitamente in loco
								senza prenotazione.</dt>
						</dl>
						<div>
							<h4>Servizi in camera:</h4>
							<ul>
								<li>Balcone</li>
								<li>Vista giardino</li>
								<li>TV a schermo piatto</li>
								<li>Aria condizionata</li>
								<li>Riscaldamento</li>
								<li><span xml:lang="fr">Parquet</span> o pavimento in legno</li>
								<li>Armadio o guardaroba</li>
								<li>Doccia</li>
								<li>Prodotti da bagno in omaggio</li>
								<li><abbr xml:lang="en" title="water closet">WC</abbr></li>
								<li>Bagno privato</li>
								<li>Vasca</li>
								<li xml:lang="fr">Bidet</li>
								<li>Carta igienica</li>
								<li>Asciuga mani</li>
							</ul>
						</div>
					</div>
					<p class="prenota_Button">
						<a class="bottoneform" href="prenota.html">Prenota</a>
					</p>
				</div>
			</div>
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
			<!-- <div id="socialCorner"> -->
			<!--<h1 xml:lang="en">Social Corner</h1>-->
			<!-- </div> -->
		</div>

	</div>

	<div id="footer">
		<img src="images/valid-xhtml10.png" alt="Sito verificato secondo standard XHTML 1.0" id="imgValidCode" />
		<img src="images/vcss-blue.gif" alt="Sito verificato secondo standard CSS" id="imgValidCSS" />
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