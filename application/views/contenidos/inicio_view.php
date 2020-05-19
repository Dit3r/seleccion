<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12" >
				<h1><?php echo NOMBREPROYECTO; ?></h1>
			</div>
		</div><!-- /.row -->
		<div class="row">
			<div class="col-md-3" >
			<?php 
				if (isset($_POST['rut'])) {
					$_SESSION['rut'] = $_POST['rut'];
				}
				if (isset($_GET['desloguea'])) {
					if ($_GET['desloguea']== 'si') {
						unset($_SESSION);
					}
				}
				if (!isset($_SESSION['rut'])):
			?>
			<!-- en la huapi hagl inicio
				<form name="logueoTMP" action="<?php echo site_url(); ?>" method="POST" class="form-inline" role="form">
					<div class="form-group">
						<label for="rut">Ingrese rut</label>
						<input name="rut" id="rut" type="text" placeholder="Ingrese rut" value="11477812">
					</div>
					<button type="submit" class="btn btn-default">Enviar</button>
				</form>
			en la huapi hagl fin -->
			<?php 
				else:
			?>
				<p>Esta logueado con rut <strong><?php echo $_SESSION['rut']; ?></strong></p>
				<!-- SOLO EN LA HUAPI 
				<a class="btn btn-danger" href="<?php echo site_url(); ?>desloguear">Desloguear</a>
				<a class="btn btn-success" href="<?php echo site_url(); ?>traslado/pendiente">PENDIENTES</a>
				 -->
			<?php 
				endif;
			?>
			</div>
		</div><!-- /.row -->
		<div class="row">
			<div class="col-md-8 col-md-offset-2" >
				<img src="http://wiredfoxtechnologies.com/wp-content/uploads/workflow__670-article.jpg" alt="" />
			</div>
		</div><!-- /.row -->
	</div><!-- /.container -->
</div> <!-- /.section -->