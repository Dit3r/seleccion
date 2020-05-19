<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mostrar grilla con simbolo mas y menos</title>
	<link href="examples/bootstrap-3.0.0/css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery.treegrid.css">

	<script type="text/javascript" src="js/jquery.treegrid.js"></script>
	<script type="text/javascript" src="js/jquery.treegrid.bootstrap3.js"></script>
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('.tree').treegrid({
	                    expanderExpandedClass: 'glyphicon glyphicon-minus',
	                    expanderCollapsedClass: 'glyphicon glyphicon-plus'
	                });
	    });
	</script>
</head>
<body>
	<h1>Mostrar grilla con simbolo mas y menos</h1>
    <table class="table tree">
        <tr class="treegrid-1">
            <td>Root node 1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-2 treegrid-parent-1">
            <td>Node 1-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-3 treegrid-parent-1">
            <td>Node 1-2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-4 treegrid-parent-3">
            <td>Node 1-2-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-5">
            <td>Root node 2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-6 treegrid-parent-5">
            <td>Node 2-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-7 treegrid-parent-5">
            <td>Node 2-2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-8 treegrid-parent-7">
            <td>Node 2-2-1</td><td>Additional info</td>
        </tr>        
    </table>	
</body>
</html>