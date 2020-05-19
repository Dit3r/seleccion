<h1>Area restringida</h1>

Bienvenido <?php echo $this->user->username; ?> - <a href="<?php echo site_url('welcome/logout'); ?>">Salir</a>
<p>El tipo de variable es <?php echo gettype($this->user->username); ?></p>
<p>El email es <?php echo $this->user->email; ?> </p>