<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Cuidando do meu bairro Creches</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lte IE 9]><link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.ie.css" /><![endif]-->

        <!--favicon-->
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>

        <!--Leaflet APIs-->
        <script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
        <script src="js/leaflet.markercluster-src.js"></script>
        <script src="js/KML.js"></script>


        <!--CSS Styles-->
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
        <link href="css/MarkerCluster.css" rel="stylesheet" />
        <link href="css/MarkerCluster.Default.css" rel="stylesheet" />
        <link href="css/table.css" rel="stylesheet"/>
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet"/>
        <link href="css/docs.css" rel="stylesheet"/>
	<link href="css/leaflet-search.css" rel="stylesheet" />

        <!--Javascript-->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script src="js/leaflet-search.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>


        <!--JQuery-->
        <script src="js/jquery.csv-0.71.min.js"></script>

        <!--Bootstrap-->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/application.js"></script>
				<script src="data/distritos.json"></script>

	<style>
	/*Inicio do estilo da layer de busca por privada ou publica*/
	#findpri {
		background: #eee;
		border-radius:.125em;
		border:2px solid #1978cf;
		box-shadow: 0 0 8px #999;
		margin-bottom: 10px;
		padding: 2px 0;
		width: 300px;
		height: 26px;
	}
	#findpu {
		background: #eee;
		border-radius:.125em;
		border:2px solid #1978cf;
		box-shadow: 0 0 8px #999;
		margin-bottom: 10px;
		padding: 2px 0;
		width: 300px;
		height: 26px;
	}
	.search-tooltip {
		width: 300px;
	}
	.leaflet-control-search .search-cancel {
		position: static;
		float: left;
		margin-left: -22px;
	}
    p.fonte{
        margin: 0 0 -4px;
    }
	/*Fim da declaracao de estilo*/
	</style>

    </head>

    <body>
        <?php include("header.inc.php"); ?>
        <div class="container">
            <header class="jumbotron subhead" id="overview" style="margin-top: -30px;">
                <div class="subnav">
                    <ul class="nav nav-pills">
                        <li><a href="#mapa">Mapa</a></li>
                        <li><a href="#busca">Legenda</a></li>
			<li><a href="#busca">Busca</a></li>
                        <li><a href="#estatistica">Estatísticas</a></li>
                    </ul>
                </div>
            </header>
            <section id="mapa">
                <h2>Mapas de oferta e demanda de Creches</h2>
                <p>Mapas de <b>oferta</b> de creches (divididas em públicas e conveniadas) e da <b>demanda</b> com os distritos coloridos pelo tamanho da fila</p>
    <table style="width:100%">
      <tr>
        <td style="width:500px;vertical-align:left;" align="center">
          <div class="row">
            <div style="width:397px; margin:0 auto;">
              <table id="label" cellspacing="0" cellpadding="0">
                <tr>
                  <th scope="col" class="nobg"></th>
                  <th scope="col">Significado</th>
                </tr>
                <tr>
                  <th scope="row" class="spec" style="text-align: left"><img src="img/pin-green.png" width="22" height="32"></img></th>
                  <td>As creches são <b>Conveniadas</b>.</td>
                </tr>
                <tr>
                  <th scope="row" class="specalt" style="text-align: left"><img src="img/pin-blue.png" width="22" height="32">
                  </img>
                </th>
                <td class="alt">As creches são <b>Públicas</b>.</td>
              </tr>
            </table>
          </div>
        </div>
      </td>
      <td style="width:500px;vertical-align:left;" align="center">

        <div class="row">
          <div style="width:397px; margin:0 auto;">
            <table id="label" cellspacing="0" cellpadding="0">
              <tr>
                <th scope="col" class="nobg"></th>
                <th scope="col">Significado</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
              <tr>
                <td scope="row" style="text-align: left; background-color: #FFEDA0"></td>
                <td>Fila per capta do distrito <b>menor que 5</b>.</td>
                <td scope="row" style="text-align: left; background-color: #FED976"></td>
                <td>Fila per capta do distrito <b>entre 5 e 10</b>.</td>

              </tr>
              <tr>
                <td scope="row" style="text-align: left; background-color: #FEB24C"></td>
                <td>Fila per capta do distrito <b>entre 10 e 20</b>.</td>
                <td scope="row" style="text-align: left; background-color: #FD8D3C"></td>
                <td>Fila per capta do distrito <b>entre 20 e 30</b>.</td>


              </tr>
              <tr>

                <td scope="row" style="text-align: left; background-color: #FC4E2A"></td>
                <td>Fila per capta do distrito <b>entre 30 e 40</b>.</td>
		<td scope="row" style="text-align: left; background-color: #E31A1C"></td>
                <td>Fila per capta do distrito <b>entre 40 e 50</b>.</td>
              </tr>
              <tr>
		<td scope="row" style="text-align: left; background-color: #BD0026"></td>
                <td>Fila per capta do distrito <b>entre 50 e 60</b>.</td>
                <td scope="row" style="text-align: left; background-color: #800026"></td>
                <td>Fila per capta do distrito <b>maior que 60</b>.</td>
              </tr>
            </table>
            Entenda o cálculo da fila per capta <a href="cores_do_mapa.php" target="_blank">clicando aqui</a>
          </div>
        </div>
      </td>
    </tr>
    </table>
                <div id="map" style="width: 1170px; height: 600px"></div>
            </section>
        <p class="fonte" align="right"><a href="http://dados.gov.br/dataset/instituicoes-de-ensino-basico" target="_blank">Censo Escolar de 2012.</a>, MEC/Inep, 2012</p>
        <p class="fonte" align="right"><a href="http://produtos.seade.gov.br/produtos/distritos/index.php" target="_blank">População de 0 a 4 anos por distrito</a>, Fundacão SEADE, 01/10/2014.</p>
        <p class="fonte" align="right"><a href="http://eolgerenciamento.prefeitura.sp.gov.br/se1426g/frmgerencial/ConsultaCandidatosCadastrados.aspx?Cod=000000" target="_blank">Consulta de candidatos cadastrados</a>, Secretaria da Educacao de Sao Paulo, 08/12/2014.</p>

	    <section id="busca">
		  <br><br>
	    	   <table style="width:1170px;" cellpadding="0" cellspacing="0" id="table">
			<tr>
	  		<td style="width:500px;vertical-align:top;" align="center">
	    		<h2 style="text-align: left">Busque uma creche</h2>
					<h4 style="text-align: center">Conveniada</h4>
					<div id="findpri"></div>
					<h4 style="text-align: center">Pública</h4>
					<div id="findpu"></div>
					*Clique na lupa e digite o nome da creche, ela irá aparecer no mapa acima.
  			</td>
			</tr>
		</table>
            </section>

            <section id="estatistica">
		<br>
		<br>
                  <h2>Estatísticas</h2>
   		   <h3 style="text-align: center">Creches por diretoria de ensino: Publicas, Privadas e Total</h3>
                    <div id = "containere" style = "min-width: 310px; height: 400px; margin: 0 auto"> </div>
                </div>
            </section>

		<script>

                    /*Processamento do arquivo das creches. Plotação no grafico, calculo das estisticas, geracao de grafico*/
                    $.get("data/creches.csv", function(data) {
                        var creches = $.csv.toArrays(data);

                  	//icone greeIcon = privada
                        var greenIcon = L.icon({
                                iconUrl: 'img/pin-green.png',
                                iconSize: [50, 41],
                                popupAnchor: [15, -41],

                        });
			//icone blueIcon = publica
                        var blueIcon = L.icon({
                                iconUrl: 'img/pin-blue.png',
                                iconSize: [50, 41],
                                popupAnchor: [15, -41],

                        });


                        //Layers
                        var publica = L.markerClusterGroup();
                        var privada = L.markerClusterGroup();

                        //Adiciona Creches e verifica pontos
	                function popUp(feature, layer) {
                            layer.bindPopup(feature.properties.name);
                        }

                        //Marcação das creches no mapa
                            for (var i = 1; i < creches.length - 1; i++) {
                                var a = creches[i];
                                    //Separação das creches
                                    if (a[4] == 'Privada') {
					var popup = L.popup().setContent(a[3] + "<br>" + "Administração: " + a[4] + "<br>" + "Dependencia Administrativa: " + a[58] + "<br>" + "Tipo: " + a[59] + "<br>" + "Distrito: " + a[6] + "<br>" + "Endereço: " + a[7] + " Nº " + a[8] + "<br>" + "Bairro: " + a[9] + "<br>" + "CEP: " + a[10] + " Telefone: 11 " + a[11] + "<br>" + "Fax: " + a[12] + "<br>" + "e-mail: " + a[13] + "<br>" + "Situação: " + a[14] + "<br>" + " Matriculados: " + a[57] + "<br>" + "Abre aos Finais de semana: " + a[55] + "<br><br>" + "Infraestrutura: " + "<br>" + "Número de Salas: " + a[40] + "<br>" + "Número de Funcionários: " + a[43] + "<br>" + "Acessibilidade: " + a[15] + "<br>" + "Dependencias PNE: " + a[16] + "<br>" + "Sanitário PNE: " + a[17] + "<br>" + "Cozinha: " + a[30] + "<br>" + "Refeitório: " + a[18] + "<br>" + "Despensa: " + a[19] + "<br>" + "Lavanderia: " + a[37] + "<br>" + "Chuveiro: " + a[36] +"<br>"+ "Auditorio: " + a[21] + "<br>" + "Laboratório de Informática: " + a[22] + "<br>" + "Laboratório de Ciências: " + a[23] + "<br>" + "Quadra de esportes coberta: " + a[25] +"<br>" +"Quadra descoberta: " + a[26] + "<br>" + "Pátio coberto: " + a[27] + " Descoberto: " + a[28] + "<br>" + "Parque Infantil: " + a[29] + "<br>" + "Biblioteca: " + a[36] + "<br>" + "Berçário: " + a[31] + "<br>" + "Sala de Leitura: " + a[41] + "<br>" + "Area verde: " + a[42] +"<br>"+ "Internet: " + a[45] + "<b>" + "TV: " + a[53] + "<br>" + "Multimídia: " + a[52]);
				   L.marker([a[2], a[1]],{icon: greenIcon, title: a[3]}).bindPopup( popup, {maxHeight: 300}) .addTo(privada);
                                    } else { //Publica
					var popup = L.popup().setContent(a[3] + "<br>" + "Distrito: " + a[6] + "<br>" + "Endereço: " + a[7] + " Nº " + a[8] + "<br>" + "Bairro: " + a[9] + "<br>" + "CEP: " + a[10] + " Telefone: 11 " + a[11] + "<br>" + "Fax: " + a[12] + "<br>" + "e-mail: " + a[13] + "<br>" + "Situação: " + a[14] + "<br>" + "Matriculados: " + a[57] + "<br>" + "Abre aos Finais de semana: " + a[55] + "<br><br>" + "Infraestrutura: " + "<br>" + "Número de Salas: " + a[40] + "<br>" + "Número de Funcionários: " + a[43] + "<br>" + "Acessibilidade: " + a[15] + "<br>" + "Dependencias PNE: " + a[16] + "<br>" + "Sanitário PNE: " + a[17] + "<br>" + "Cozinha: " + a[30] + "<br>" + "Refeitório: " + a[18] + "<br>" + "Despensa: " + a[19] + "<br>" + "Lavanderia: " + a[37] + "<br>" + "Chuveiro: " + a[36] + "<br>" + "Auditorio: " + a[21] + "<br>" + "Laboratório de Informática: " + a[22] + "<br>" + "Laboratório de Ciências: " + a[23] + "<br>" + "Quadra de esportes coberta: " + a[25]+"<br>" + " Quadra descoberta: " + a[26] + "<br>" + "Pátio coberto: " + a[27] + " Descoberto: " + a[28] + "<br>" + "Parque Infantil: " + a[29] + "<br>" + "Biblioteca: " + a[36] + "<br>" + "Berçário: " + a[31] + "<br>" + "Sala de Leitura: " + a[41] + "<br>" + "Area verde: " + a[42] + "<br>" + "Internet: " + a[45] + "<br>" + "TV: " + a[53] + "<br>" + "Multimídia: " + a[52]);
				   L.marker([a[2], a[1]], {icon: blueIcon, title: a[3]}).bindPopup( popup, {maxHeight: 300}).addTo(publica);
                                    }

                            }


                        //Configuração tile do mapa
                        var cmAttr = '&copy; Mapbox &copy OpenStreetMap',
                                cmUrl = 'http://{s}.tiles.mapbox.com/v3/renansfs.ia0j96ji/{z}/{x}/{y}.png';
                        var minimal = L.tileLayer(cmUrl, {styleId: 22677, attribution: cmAttr});

                        //Criacao do Mapa e quais serão suas layers
                        var map = L.map('map', {
                            center: [-23.58098, -46.61293],
                            zoom: 11,
                            layers: [minimal]
                        });
			//abertura do arquivo de layers e implementado no mapa.
                        var distritos = new L.KML("data/distritos.kml", {async: true});
                        //map.addLayer(distritos);

                        //Layer base
                        var baseLayers = {
                            "Minimal": minimal,
                        };

                        //Overlays
                        var overlays = {
                            "Públicas": publica,
                            "Privadas": privada
                        };
												 function getColor(d, distr) {
                            if(d == undefined){
                              console.log(distr);
                            }
                            //console.log(d);

                            return d > 60 ? '#800026' :
                                   d > 50  ? '#BD0026' :
                                   d > 40  ? '#E31A1C' :
                                   d > 30  ? '#FC4E2A' :
                                   d > 20   ? '#FD8D3C' :
                                   d > 10   ? '#FEB24C' :
                                   d > 5   ? '#FED976' :
                                              '#FFEDA0';
                        }

                        var fila_carregada = undefined;
                        function filaCSV(callback){
                            if(fila_carregada)
                              callback(fila_carregada);
                            else{
                              var filas = {};
                              $.ajax({
                                type: "GET",
                                url: "data/filas.csv",
                                dataType: "text",
                                async: false,
                                success: function(data) {
                                  var creches = $.csv.toArrays(data);

                                  for(i = 1; i < creches.length; i++){
                                    linha = creches[i].toString().split(",");
                                    var distrito = linha[0];
                                    var fila = linha[1];
                                    filas[distrito] = [fila, linha[2], linha[3]];
                                  }
                                  fila_carregada = filas;
                                  callback(filas);
                                }
                              });
                            }
                        }

                        function style(feature) {
                            var distrito = feature.properties['Name'].toUpperCase();
                            var r = undefined;
                            filaCSV(function(filas){
                              console.log(filas)
                              r = {
                                fillColor: getColor(filas[distrito][0], distrito),
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                              };
                            })
                            return r;
                        }


                        var distritos = new L.GeoJSON(distritoData, {style: style, onEachFeature: onEachFeature});
                        map.addLayer(distritos);


			                   function onEachFeature(feature, layer) {
                           filaCSV(function(filas){
                              var balao = "<b>" + feature.properties.Name + "</b>";
                              balao += "<br/> Fila per capta: " + filas[feature.properties.Name.toUpperCase()][0];
                              balao += "<br/> Tamanho da fila (2014, <a href='http://eolgerenciamento.prefeitura.sp.gov.br/se1426g/frmgerencial/ConsultaCandidatosCadastrados.aspx?Cod=000000' target='_blank'>SME</a>): " + filas[feature.properties.Name.toUpperCase()][1];
                              balao += "<br/> População de 0 a 4 anos (2014, <a href='http://produtos.seade.gov.br/produtos/distritos/index.php' target='_blank'>SEADE</a>): " + filas[feature.properties.Name.toUpperCase()][2];
                              layer.bindPopup(balao);
                                layer.on('mouseover', function (e) {
                                  this.openPopup();
                                });
                                layer.on('mouseout', function (e) {
                                  this.closePopup();
                                });
                              });
                          }
                         //Controle das layers

                        L.control.layers(baseLayers, overlays).addTo(map);


			/*Ferramenta de busca fora do mapa por nome de creche, findpri: privada e findpu: publica, conforme foi atribuido no inicio do código da layer de busca*/
			map.addControl(new L.Control.Search({wrapper: 'findpri', layer: privada, zoom: 19, initial: false}));
			map.addControl(new L.Control.Search({wrapper: 'findpu', zoom: 19, layer: publica, initial: false}));

			/*Ferramenta de busca dentro do mapa de endereços da google.*/
			var geocoder = new google.maps.Geocoder();

			function googleGeocoding(text, callResponse){
				geocoder.geocode({address: text}, callResponse);
			}

			function filterJSONCall(rawjson){
				var json = {},
				key, loc, disp = [];

				for(var i in rawjson){
				key = rawjson[i].formatted_address;

				loc = L.latLng( rawjson[i].geometry.location.lat(), rawjson[i].geometry.location.lng() );

				json[ key ]= loc;	//key,value format
				}
				return json;
			}

			map.addControl(new L.Control.Search({
				callData: googleGeocoding,
				filterJSON: filterJSONCall,
				markerLocation: true,
				autoType: false,
				autoCollapse: true,
				minLength: 2,
				zoom: 19
			})); //Fim busca endereço

			//Inicio das estatisticas
			//Variáveis dos diretorios, pub: publico, conv: conveniado
		    	var i = 0;
                    	var pub = new Array(12); //diretorias de ensino publicas
		    	var conv = new Array(12);
		    	while(i<13){
				pub[i] = 0;
				conv[i] = 0;
				i++;
		    	}

			//Inicio do processamento das estatisticas
			for (var i = 1; i < creches.length - 1; i++) {
                                var a = creches[i];

				if(a[6] == 'JARDIM PAULISTA' || a[6] == 'ITAIM BIBI' || a[6] == 'BUTANTA' || a[6] == 'RIO PEQUENO' || a[6] == 'PINHEIROS' || a[6] == 'ALTO DE PINHEIROS' || a[6] == 'MORUMBI' || a[6] == 'RAPOSO TAVARES' || 					a[6] == 'VILA SONIA'){ //BUTANTA
					if(a[4] == 'Privada') conv[0] = conv[0] + 1;
					else pub[0] = pub[0]+1;

				}else if(a[6] == 'CAMPO LIMPO' || a[6] == 'JARDIM SAO LUIS' || a[6] == 'VILA ANDRADE' || a[6] == 'CAPAO REDONDO' || a[6] == 'JARDIM ANGELA'){ //CAMPO LIMPO
					if(a[4] == 'Privada') conv[1] = conv[1] + 1;
					else pub[1] = pub[1]+1;
				}
				else if(a[6] == 'CIDADE DUTRA' || a[6] == 'GRAJAU' || a[6] == 'MARSILAC' || a[6] == 'PARELHEIROS' || a[6] == 'SOCORRO'){ //CAPELA DO SOCORRO
					if(a[4] == 'Privada') conv[2] = conv[2] + 1;
					else pub[2] = pub[2]+1;
				}
				else if(a[6] == 'LIMAO' || a[6] == 'BRASILANDIA' || a[6] == 'FREGUESIA DO O' || a[6] == 'CASA VERDE' || a[6] == 'CACHOEIRINHA'){  //FREQUESIA/BRASILANDIA
					if(a[4] == 'Privada') conv[3] = conv[3] + 1;
					else pub[3] = pub[3]+1;
				}
				else if(a[6] == 'GUAIANASES' || a[6] == 'LAJEADO' || a[6] == 'CIDADE TIRADENTES'){ //GUAIANASES
					if(a[4] == 'Privada') conv[4] = conv[4] + 1;
					else pub[4] = pub[4]+1;
				}
				else if(a[6] == 'REPUBLICA' || a[6] == 'BELA VISTA' || a[6] == 'BOM RETIRO' || a[6] == 'CAMBUCI' || a[6] == 'CONSOLACAO' ||	a[6] == 'CURSINO' || a[6] == 'IPIRANGA' || a[6] == 'LIBERDADE' || a[6] == 'MOEMA' || a[6] == 'SACOMA' || a[6] == 'SANTA CECILIA' || a[6] == 'SAO LUCAS' || a[6] == 'SAUDE' || a[6] == 'SE' || a[6] == 'VILA MARIANA' || a[6] == 'VILA PRUDENTE'){ //IPIRANGA
					if(a[4] == 'Privada') conv[5] = conv[5] + 1;
					else pub[5] = pub[5]+1;
				}
				else if(a[6] == 'ARICANDUVA' || a[6] == 'CARRAO' || a[6] == 'CIDADE LIDER' || a[6] == 'ITAQUERA' || a[6] == 'JOSE BONIFACIO' || a[6] == 'PARQUE DO CARMO' || a[6] == 'VILA FORMOSA'){ //ITAQUERA
					if(a[4] == 'Privada') conv[6] = conv[6] + 1;
					else pub[6] = pub[6]+1;
				}
				else if(a[6] == 'JACANA' || a[6] == 'MANDAQUI' || a[6] == 'SANTANA' || a[6] == 'TREMEMBE' || a[6] == 'TUCURUVI' || a[6] == 'VILA GUILHERME' || a[6] == 'VILA MARIA' || a[6] == 'VILA MEDEIROS'){
					if(a[4] == 'Privada') conv[7] = conv[7] + 1;
					else pub[7] = pub[7]+1; //JACANA - TREMEMBE
				}
				else if(a[6] == 'ITAIM PAULISTA' || a[6] == 'JARDIM HELENA' || a[6] == 'SAO MIGUEL' || a[6] == 'VILA CURUCA' || a[6] == 'VILA JACUI'){ //SAO MIGUEL PAULISTA
					if(a[4] == 'Privada') conv[8] = conv[8] + 1;
					else pub[8] = pub[8]+1;
				}
				else if(a[6] == 'AGUA RASA' || a[6] == 'ARTUR ALVIM' || a[6] == 'BELEM' || a[6] == 'BRAS' || a[6] == 'CANGAIBA' || a[6] == 'ERMELINO MATARAZZO' || a[6] == 'MOOCA' || a[6] == 'PARI' || a[6] == 'PENHA' ||
				a[6] == 'PONTE RASA' || a[6] == 'TATUAPE' || a[6] == 'VILA MATILDE'){ //PENHA
					if(a[4] == 'Privada') conv[9] = conv[9] + 1;
					else pub[9] = pub[9]+1;
				}
				else if(a[6] == 'ANHANGUERA' || a[6] == 'BARRA FUNDA' || a[6] == 'JAGUARA' || a[6] == 'JAGUARE' || a[6] == 'JARAGUA' || a[6] == 'LAPA' || a[6] == 'PERDIZES' || a[6] == 'PERUS' || a[6] == 'PIRITUBA' ||
				a[6] == 'SAO DOMINGOS' || a[6] == 'VILA LEOPOLDINA'){ //PIRITUBA
					if(a[4] == 'Privada') conv[10] = conv[10] + 1;
					else pub[10] = pub[10]+1;
				}
				else if(a[6] == 'CAMPO BELO' || a[6] == 'CAMPO GRANDE' || a[6] == 'CIDADE ADEMAR' || a[6] == 'JABAQUARA' || a[6] == 'PEDREIRA' || a[6] == 'SANTO AMARO'){ //SANTO AMARO
					if(a[4] == 'Privada') conv[11] = conv[11] + 1;
					else pub[11] = pub[11]+1;
				}
				else if(a[6] == 'IGUATEMI' || a[6] == 'SAO MATEUS' || a[6] == 'SAO RAFAEL' || a[6] == 'SAPOPEMBA'){
					if(a[4] == 'Privada') conv[12] = conv[12] + 1; //SAO MATEUS
					else pub[12] = pub[12]+1;
				}else{
					console.log(a[6]);
				}
                            }

		//Função que implementa o grafico baseado no processamento do arquivo acima.
		     $(function() {
                                  $('#containere').highcharts({
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: ' '
                                            },
                                            subtitle: {
                                                text: ' '
                                            },
                                            xAxis: {
                                                categories: [
                                                    'Butantã',
                                                    'Campo Limpo',
                                                    'Capela do Socorro',
                                                    'Frequesia/Brasilêndia',
                                                    'Guaianazes',
                                                    'Ipiranga',
                                                    'Itaquera',
                                                    'Jaçaba/Tremembé',
                                                    'São Miguel Paulista',
                                                    'Penha',
                                                    'Pirituba',
						    'Santo Amaro',
                                                    'Sao Mateus'
                                                ]
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: {
                                                    text: 'Quantidade de Creches'
                                                }
                                            },
                                            tooltip: {
                                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                                             '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                                                footerFormat: '</table>',
                                                shared: true,
                                                useHTML: true
                                            },
                                            plotOptions: {
                                                column: {
                                                    pointPadding: 0.2,
                                                    borderWidth: 0
                                                }
                                            },
                                            series: [{
                                                    name: 'Públicas',
                                                    data: [pub[0], pub[1], pub[2], pub[3], pub[4], pub[5], pub[6], pub[7], pub[8], pub[9], pub[10], pub[11], pub[12]]

                                                }, {
                                                    name: 'Conveniadas',
                                                    data: [conv[0], conv[1], conv[2], conv[3], conv[4], conv[5], conv[6], conv[7], conv[8], conv[9], conv[10], conv[11], conv[12]]

                                                }, {
                                                    name: 'Total',
                                                    data: [(pub[0]+conv[0]),(pub[1]+conv[1]), (pub[2]+conv[2]), (pub[3]+conv[3]), 							   (pub[4]+conv[4]), (pub[5]+conv[5]), (pub[6]+conv[6]), (pub[7]+conv[7]), (pub[8]+conv[8]), (pub[9]+conv[9]), (pub[10]+conv[10]), (pub[11]+conv[11]), (pub[12]+conv[12])]
                                                }]
                                        });
                                 });
});
		    //fim do grafico




                </script>
            <?php include("footer.inc.php"); ?>
    </body>
</html>
