<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <title>Cuidando do meu Bairro Creches</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

		<!--favicon-->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>

    <!--CSS Styles-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <!--END OF CSS Styles-->

    <!--Javascript-->
		<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/application.js"></script>
		<script src="js/jquery.validate.js"></script>
		<script src="js/form-validator.js"></script>
  </head>

  <body>
    <?php include("header.inc.php"); ?>

    <div class="container">
      	<div class="row">
      		<div class="span7">
      			<h1>Cores do mapa</h1>
            <p>As cores do mapa indicam quão grande é a fila por vagas em creches no distrito.</p>
            <p>O tamanho da fila é extraído da página da <a href="http://eolgerenciamento.prefeitura.sp.gov.br/se1426g/frmgerencial/ConsultaCandidatosCadastrados.aspx?Cod=000000" target="_blank">Secretaria Municipal de Educação</a>.</p>
            <p>Para calcular a <b>fila per capta</b> do distrito ainda são utilizados os tamanhos da população infantil desses distritos. O tamanho da população infantul é fornecido pela <a href="http://produtos.seade.gov.br/produtos/distritos/index.php" target="_blank">Fundação SEADE</a>.</p>
            <p>A <b>fila per capta</b> é calculada dividindo-se o tamanho da fila pelo tamanho da população (e multiplicando o valor por 100).</p>
            <p>Os valores são atualizados diariamente.</p>
      		</div>
      	</div> <!--Row-->
    </div> <!--Container-->

    <?php include("footer.inc.php"); ?>
  </body>
</html>
