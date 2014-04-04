<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<title>Cuidando da minha creche</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lte IE 9]><link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.ie.css" /><![endif]-->

		<!--favicon-->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>
	
		<!--Leaflet APIs-->
		<script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
		<script src="js/leaflet.markercluster-src.js"></script>
		<script src="js/KML.js"></script>  
		<script src="js/leaflet-pip.min.js"></script>  

		<!--CSS Styles-->
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
		<link href="css/MarkerCluster.css" rel="stylesheet" />
		<link href="css/MarkerCluster.Default.css" rel="stylesheet" />
		<link href="css/table.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<link href="css/docs.css" rel="stylesheet">
		<!--END OF CSS Styles-->
		
		<!--Javascript-->
                <!--JQuery-->
		<script src="js/jquery-1.10.2.min.js"></script>
		<script src="js/jquery.csv-0.71.min.js"></script>
		
					
		<!--Bootstrap-->
		<script src="js/bootstrap.min.js"></script>
		<script src="js/application.js"></script>
		
		
		
	</head>

	<body>
		
		<?php include("header.inc.php"); ?>
		<div class="container">
			
			<header class="jumbotron subhead" id="overview">
			  <div class="subnav">
			    <ul class="nav nav-pills">
			      <li><a href="#mapa">Mapa</a></li>
			      <li><a href="#label">Legenda</a></li>
		
			    </ul>
			  </div>
			</header>

			<section id="mapa">
			  <div class="page-header" style>
			    <h2>Mapa das Creches</h2>
				</div>
				<form class="form">
      					<input id="address" class="input-medium search-query" type="text" style="width: 400px;" placeholder="Coloque aqui um endereço para procurar no mapa">
      					<button id="btnSearch" class="btn btn-primary" type="button" value="Localizar" onclick="codeAddress()">Localizar</button></form>
				 	<div id="map" style="width: 1024px; height: 600px"></div>	
						<script>
						
						
						//Icones
						// Criando ícones customizados
						

						$.get("data/creches.csv", function(data){
							var creches = $.csv.toArrays(data);
				
						var blueIcon = L.Icon.Default.extend({
							options: {
								iconUrl: 'img/pin-blue.png',
								iconSize:     [50, 41],
								popupAnchor:  [15, -41],
							}
						});
						var greenIcon = L.Icon.Default.extend({
							options: {
								iconUrl: 'img/pin-green.png', 
								iconSize:     [50, 41],
								popupAnchor:  [15, -41],
							}
						});
						var orangeIcon = L.Icon.Default.extend({
							options: {
								iconUrl: 'img/pin-orange.png',
								iconSize:     [50, 41],
								popupAnchor:  [15, -41],
							}
						});
						
						var blueIcon = new blueIcon();
						var greenIcon = new greenIcon();
						var orangeIcon = new orangeIcon();
						
						//Layers
						var publica = L.markerClusterGroup();
						var privada = L.markerClusterGroup();
						
						//Adiciona Creches e verifica pontos


						function popUp(feature, layer) {
   							layer.bindPopup(feature.properties.name);
  						}
						
						$.getJSON("data/distritos.json", function(data) {
    						var temp = new L.geoJson(data, { onEachFeature: popUp});					
						for(var i=1; i<creches.length-1; i++){
							var a = creches[i];
							var x = L.latLng(a[2], a[1]);
							var result = leafletPip.pointInLayer(x , temp, true);
							if(result.length == 1){
								if(a[4] == 'Privada'){ // Filtro para separar as creches
									var marker = L.marker([a[2],a[1]],{icon: greenIcon}).bindPopup(a[3]+"<br>"+"Distrito: "+a[6]+"<br>"+"Endereço: "+a[7]+" Nº "+a[8]+"<br>"+"Bairro: "+a[9]+"<br>"+"CEP: "+a[10]+" Telefone: 11 "+a[11]+"<br>"+"Faz: "+a[12]+"<br>"+"e-mail: "+a[13]+"<br>"+"Situação: "+a[14]+"<br>"+"Abre aos Finais de semana: "+a[55]+"<br><br>"+"Infraestrutura: "+"<br>"+"Número de Salas: "+a[40]+"<br>"+"Número de Funcionários: "+a[43]+"<br>"+"Acessibilidade: "+a[15]+"<br>"+"Dependencias PNE: "+a[16]+"<br>"+"Sanitário PNE: "+a[17]+"<br>"+"Cozinha: "+a[30]+"<br>"+"Refeitório: "+a[18]+"<br>"+"Despensa: "+a[19]+"<br>"+"Lavanderia: "+a[37]+"<br>"+"Chuveiro: "+a[36]+"Auditorio: "+a[21]+"<br>"+"Laboratório de Informática: "+a[22]+"<br>"+"Laboratório de Ciências: "+a[23]+"<br>"+"Quadra de esportes coberta: "+a[25]+" Descoberta: "+a[26]+"<br>"+"Pátio coberto: "+a[27]+" Descoberto: "+a[28]+"<br>"+"Parque Infantil: "+a[29]+"<br>"+"Biblioteca: "+a[36]+"<br>"+"Berçário: "+a[31]+"<br>"+"Sala de Leitura: "+a[41]+"<br>"+"Area verde: "+a[42]+"Internet: "+a[45]+"<b>"+"TV: "+a[53]+"<br>"+"Multimídia: "+a[52]).addTo(privada);
								}else{
									var marker = L.marker([a[2],a[1]],{icon: blueIcon}).bindPopup(a[3]+"<br>"+"Distrito: "+a[6]+"<br>"+"Endereço: "+a[7]+" Nº "+a[8]+"<br>"+"Bairro: "+a[9]+"<br>"+"CEP: "+a[10]+" Telefone: 11 "+a[11]+"<br>"+"Fax: "+a[12]+"<br>"+"e-mail: "+a[13]+"<br>"+"Situação: "+a[14]+"<br>"+"Abre aos Finais de semana: "+a[55]+"<br><br>"+"Infraestrutura: "+"<br>"+"Número de Salas: "+a[40]+"<br>"+"Número de Funcionários: "+a[43]+"<br>"+"Acessibilidade: "+a[15]+"<br>"+"Dependencias PNE: "+a[16]+"<br>"+"Sanitário PNE: "+a[17]+"<br>"+"Cozinha: "+a[30]+"br"+"Refeitório: "+a[18]+"<br>"+"Despensa: "+a[19]+"<br>"+"Lavanderia: "+a[37]+"<br>"+"Chuveiro: "+a[36]+"<br>"+"Auditorio: "+a[21]+"<br>"+"Laboratório de Informática: "+a[22]+"<br>"+"Laboratório de Ciências: "+a[23]+"<br>"+"Quadra de esportes coberta: "+a[25]+" Descoberta: "+a[26]+"<br>"+"Pátio coberto: "+a[27]+" Descoberto: "+a[28]+"<br>"+"Parque Infantil: "+a[29]+"<br>"+"Biblioteca: "+a[36]+"<br>"+"Berçário: "+a[31]+"<br>"+"Sala de Leitura: "+a[41]+"<br>"+"Area verde: "+a[42]+"<br>"+"Internet: "+a[45]+"<br>"+"TV: "+a[53]+"<br>"+"Multimídia: "+a[52]).addTo(publica);
								}
							}
						}
					});	

					// Criando o mapa
					//Layer delimitacao	
					
					var cmAttr = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
					cmUrl = 'http://{s}.tile.cloudmade.com/0f41e16652a74c9ba844a8f1a2ffee54/{styleId}/256/{z}/{x}/{y}.png';
					var minimal   = L.tileLayer(cmUrl, {styleId: 22677, attribution: cmAttr});
					
					var map = L.map('map', {
						center: [-23.58098,-46.61293],
						zoom: 11,
						layers: [minimal, publica, privada] 
						});
					var distritos = new L.KML("data/distritos.kml", {async: true});					
					map.addLayer(distritos);
						

					
					//Layers
						var baseLayers = {
							"Minimal": minimal,
						};
			
						//Overlays
						var overlays = {
							"Públicas": publica,
							"Privadas": privada,
							"Distritos": distritos
						};

					L.control.layers(baseLayers, overlays).addTo(map);
						
				});
			</script>
			
				
			</section>

			<section id="label">
			  <div class="page-header">
			    <h2>Legenda dos Marcadores</h2>
			  </div>
			  <div class="row">
			    <div style="width:397px; margin:0 auto;">
			      <table id="label" cellspacing="0">
							<tr>
							  <th scope="col" class="nobg"></th>
							  <th scope="col">Significado</th>
							</tr>
			
							<tr>
							  <th scope="row" class="spec" style="text-align: left"><img src="img/pin-green.png" width="22" height="32"></img></th>
							  <td>As creches são <b>Conveniadas</b>.</td>
							</tr>

							<tr>
							  <th scope="row" class="specalt" style="text-align: left"><img src="img/pin-blue.png" width="22" height="32"></img></th>
							  <td class="alt">As creches são <b>Públicas</b>.</td>
							</tr>

							
			      </table>
			    </div>
			  </div>
			</section>
		</div>
		<br>
		<?php include("footer.inc.php"); ?>
	</body>
</html>
