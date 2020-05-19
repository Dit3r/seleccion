function alertaPersonalizada(tituloAlert,contenidoAlert){
	$.alert.open({
		title: tituloAlert,
	    content: contenidoAlert,
	    callback: function(button) {
	        if (!button)
	            $.alert.open('Alert was canceled.');
	        else
	            location.reload();
	    }
	});
}
function soloAlertaPersonalizada(tituloAlert,contenidoAlert){
	$.alert.open({
		title: tituloAlert,
	    content: contenidoAlert
	});
}
function alertaReloadPage(tituloAlert,contenidoAlert){
	$.alert.open({
		title: tituloAlert,
	    content: contenidoAlert,
	    callback: function(button) {
	        if (!button)
	        	location.reload();
	        else
	            location.reload();
	    }
	});
}
function limpiarFormulario(tituloAlert,contenidoAlert){
	$.alert.open({
		title: tituloAlert,
	    content: contenidoAlert,
	    type:'confirm',
	    buttons: {
	        yes: 'Si',
	        no: 'No'
	    },
	    callback: function(button) {
	        if (button == 'yes') {
	    		$('#frm_solicitud_traslado')[0].reset();
	    		$('#sol_rut,#sol_local_dest').html('<option value="">----</option>');
	    		$('#sol_motivo').removeAttr( "disabled" );
	        } else {
	        	return false;
	        }
	            
	    }
	});
}

