<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Salcobrand - <?php echo $titlePage; ?> </title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo site_url(); ?>assets/template/tpl-sb/css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="<?php echo site_url(); ?>assets/template/tpl-sb/css/modern-business.css" rel="stylesheet">
    <link href="<?php echo site_url(); ?>assets/template/tpl-sb/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl-sb/css/docs.min.css">

    <link href="<?php echo site_url(); ?>assets/template/tpl-sb/css/custom.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="<?php echo site_url(); ?>assets/template/tpl-sb/img/favicon.ico"/>
    <script src="<?php echo site_url(); ?>assets/template/tpl-sb/js/jquery-1.10.2.js"></script>
    <script src="<?php echo site_url(); ?>assets/template/tpl-sb/js/bootstrap.js"></script>

</head>

<body>
<div id="wrap">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
                        <a class="navbar-brand" href="<?php echo site_url(); ?>">DASHBOARD SALCOBRAND</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php echo site_url(); ?>">Inicio</a>
                            </li>
                            <li><a href="<?php echo site_url(); ?>ingreso">Ingreso</a>
                            </li>
                            <li><a href="<?php echo site_url(); ?>monitor">Monitor</a>
                            </li>                            
                        </ul>
                    </div>
                <!-- /.navbar-collapse -->
                </div>
            </div>
        </div>
        <!-- /.container -->
    </nav>