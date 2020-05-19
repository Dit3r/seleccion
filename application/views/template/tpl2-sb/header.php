<?php 
if(!isset($_SESSION)){
	session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php if (isset($titlePage)) { echo $titlePage . ' - '; } ?>Empresas SB</title>
<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/*
@font-face {
  font-family: "Myriad Pro";
  src: url('<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.eot');
  src: local('☺'), 
 url('<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.eot')
       url('<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.woff') format('woff'), 
       url('<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.ttf') format('truetype'), 
       url('<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.svg#webfontg8dbVmxj') format('svg');
}
*/
@font-face {
    font-family: "Myriad Pro";
    src: url(<?php echo site_url(); ?>assets/template/tpl2-sb/fonts/MyriadPro-Regular.woff);
}
body {
	font-family: "Myriad Pro";
	font-size: 14px;
}
</style>
<link rel="icon"
	href="<?php echo site_url(); ?>assets/template/tpl2-sb/img/favicon.ico"
	type="image/x-icon" />
<!-- Bootstrap -->
<link
	href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/bootstrap.min.css"
	rel="stylesheet">
<!-- va ligado a  bootstrap-select.js-->
<link rel="stylesheet" type="text/css"
	href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/bootstrap-select.css">

<!-- Responsive table -->
<link rel="stylesheet" type="text/css"
	href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/responsive-tables.css">

<!--===========styles SB==================-->
<!-- 
    <link href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/font-google-yanone.css" rel="stylesheet">
    -->
<link
	href="<?php echo site_url(); ?>assets/template/tpl2-sb/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link
	href='<?php echo site_url(); ?>assets/template/tpl2-sb/css/style.css'
	rel='stylesheet' type='text/css'>
<link
	href='<?php echo site_url(); ?>assets/template/tpl2-sb/css/custom.css'
	rel='stylesheet' type='text/css'>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script
	src="<?php echo site_url(); ?>assets/lib/jquery/jquery-v1.11.1.js"></script>
	
	
<!--VALIDAR NAVEGADOR INICIO-->	
    <script>
        DetectarIE();
        
        function DetectarIE() {
            // detect IE
            var IEversion = detectIE();

            if (IEversion !== false) {
              //document.getElementById('result').innerHTML = 'IE ' + IEversion;
                if (IEversion < 9) {
                    window.location = "<?php echo site_url(); ?>navegacion/navegadorIncompatible";
                }
            } 
        }



        // add details to debug result
        //document.getElementById('details').innerHTML = window.navigator.userAgent;



        /**
         * detect IE
         * returns version of IE or false, if browser is not Internet Explorer
         */
        function detectIE() {
          var ua = window.navigator.userAgent;

          // test values
          // IE 10
          //ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';
          // IE 11
          //ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';
          // IE 12 / Spartan
          //ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

          var msie = ua.indexOf('MSIE ');
          if (msie > 0) {
            // IE 10 or older => return version number
            return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
          }

          var trident = ua.indexOf('Trident/');
          if (trident > 0) {
            // IE 11 => return version number
            var rv = ua.indexOf('rv:');
            return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
          }

          var edge = ua.indexOf('Edge/');
          if (edge > 0) {
            // IE 12 => return version number
            return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
          }

            // other browser
          return false;
        }    
    </script>
<!--VALIDAR NAVEGADOR    FIN-->	
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script
	src="<?php echo site_url(); ?>assets/template/tpl2-sb/js/bootstrap.min.js"></script>
<!-- jquery tables -->
<script
	src="<?php echo site_url(); ?>assets/template/tpl2-sb/js/responsive-tables.js"></script>

<!-- va ligado a  bootstrap-select.css-->
<script src="<?php echo site_url(); ?>assets/template/tpl2-sb/js/bootstrap-select.js"></script>

</head>

<body style="">
	<div id="wrap">
		<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container-full">

				<div class="navbar-header">

					<button type="button" class="navbar-toggle" style="color: red;"
						data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<!--Menujagl
          <a class="navbar-brand menu" href="#">
            <img src="<?php echo site_url(); ?>assets/template/tpl2-sb/img/menu.png">
          </a>
      Menujagl-->
				</div>
				<!-- menu ppal inicio -->
				<div class="collapse navbar-collapse">
					<ul id="lstMenu" class="nav navbar-nav left">
						<!-- 
				<li class="active"><a href="<?php echo site_url(); ?>inicio">inicio</a></li>
				<li><a href="<?php echo site_url(); ?>menu1">Menu 1</a></li>
				 -->
						<li><a href="http://<?php echo $_SERVER['HTTP_HOST']?>/intranet/Portal" style="color: white;"> <span
								class="glyphicon glyphicon-home"></span>
						</a></li>
						<li class="dropdown-toggle" data-toggle="dropdown">
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown"> <span>Traslados</span>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="droptopmenu"
										href="<?php echo site_url(); ?>traslado/solicitud">Solicitud
											de Traslado</a></li>
									<li><a class="droptopmenu"
										href="<?php echo site_url(); ?>traslado/pendiente">Solicitudes
											Pendientes</a></li>
									<li><a class="droptopmenu"
										href="<?php echo site_url(); ?>traslado/historico">Solicitudes
											Historica</a></li>
									<!-- <li><a class="droptopmenu" href="#">Informes</a></li> -->
								</ul>
							</div>
						</li>
						<li class="dropdown-toggle" data-toggle="dropdown">
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown"> <span>Cambio
										cargo</span> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="droptopmenu" href="#">Solicitud Cambio Cargo</a></li>
									<li><a class="droptopmenu" href="#">Consultas o Solicitudes</a></li>
									<li><a class="droptopmenu" href="#">Informes</a></li>
								</ul>
							</div>
						</li>
						<li class="dropdown-toggle" data-toggle="dropdown">
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown"> <span>Cambio
										Pto. Equilibrio</span> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="droptopmenu" href="#">Solicitud Cambio
											Pto.Equilibrio.</a></li>
									<li><a class="droptopmenu" href="#">Solicitudes Pendientes</a></li>
									<li><a class="droptopmenu" href="#">Solicitudes Historica</a></li>
									<li><a class="droptopmenu" href="#">Informes</a></li>
								</ul>
							</div>
						</li>
						<li class="dropdown-toggle" data-toggle="dropdown">
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown"> <span>Ingreso Vacante</span> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<!-- 
										<a class="droptopmenu" href="<?php echo site_url(); ?>ingreso_vacante/pendientes">
											Solicitudes Pendientes
										</a>
										 -->
										<a class="droptopmenu" href="#">
											Solicitudes Pendientes
										</a>
									</li>
									<li>
										<!-- 
										<a class="droptopmenu" href="<?php echo site_url(); ?>ingreso_vacante/historico">
											Solicitudes Historica
										</a>
										-->
										<a class="droptopmenu" href="#">
											Solicitudes Historica
										</a>
									</li>
									<li><a class="droptopmenu" href="#">Informes</a></li>
								</ul>
							</div>
						</li>
						<li class="dropdown-toggle" data-toggle="dropdown">
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown"> <span>Creación
										de Cargo</span> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a class="droptopmenu" href="#">Solicitud Creacion de Cargo</a></li>
									<li><a class="droptopmenu" href="#">Consultas o Solicitudes</a></li>
									<li><a class="droptopmenu" href="#">Informes</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
				<!-- /.nav-collapse -->
				<!-- menu ppal fin -->
			</div>
			<!-- /.container -->
		</div>
		<!-- /.navbar -->