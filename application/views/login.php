<h1>Inicia sesi&oacute;n</h1>

<?php if(@$error_login): ?>
	Error en el usuario o contrase&ntilde;a.
	<br />
<?php endif; ?>

<?php echo @validation_errors(); ?>

<br />

<form method="post">
	Username: <input type="text" name="username" value="<?php echo @$_POST['username']; ?>"/><br />
	Password: <input type="password" name="password" value="<?php echo @$_POST['password']; ?>" /><br />
	<input type="submit" value="Iniciar sesi&oacute;n">
</form>