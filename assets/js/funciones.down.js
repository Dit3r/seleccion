$(document).ready( function (){

	$.ajax({
		type:"POST",
		url :"./down/generaGraph",
		dataType:"html",
	 	timeout : 10000,
		success: function(dato){
			var ts = Math.round((new Date()).getTime() / 1000);
			$('#graphBarra').attr('src', './assets/generated/graphBarraTopten.png?'+ts);
			$('#graphPieGral').attr('src', './assets/generated/graphPieGral.png?'+ts);
		},
		error: function(xhr, ajaxOptions, thrownError) { 
					msg = "A ocurridoff un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
		}
	});//fin ajax

	setInterval(function() {
		$.ajax({
				type:"POST",
				url :"./down/generaGraph",
				dataType:"html",
			 	timeout : 10000,
				success: function(dato){
					var ts = Math.round((new Date()).getTime() / 1000);
					$('#graphBarra').attr('src', './assets/generated/graphBarraTopten.png?'+ts);
					$('#graphPieGral').attr('src', './assets/generated/graphPieGral.png?'+ts);
				},
				error: function(xhr, ajaxOptions, thrownError) { 
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
			  }
		   });//fin ajax
	}, 3600000);

/**************GRID******************/

	$('#getGrid').click(function() {

		//trae la tabla con el detalle de los top 10
        $.ajax({
            url: './down/newGeneraTop10',
            type:'POST',
            dataType: 'html',
            beforeSend:inicioEnvio,
            success: function(output_string){
                    $('.containerGrid').html(output_string);
                    $('.nivel1 span:first').addClass('fa fa-plus-circle');
                } 
		}).done(function() {
			$('.nivel1').click(function (e){
				var valor = $(this).attr('data-valor');
				var elem = $(this).attr("id");

				if ($('#'+elem+' span:first').attr('class') == 'fa fa-minus-circle') {
					$('.nivel1 span').removeClass('fa-minus-circle');	
					$('.nivel1 span:first').addClass('fa fa-plus-circle');				
					$('#'+elem+' span:first').addClass('fa fa-plus-circle');
					$('#'+elem+' ul').css('display','none');
				} else {
					$('#'+elem+' div.loading_msg').show();
					$('.nivel1 span:nth-child(1)').removeClass('fa-minus-circle');
					$('.nivel1 span:nth-child(1)').addClass('fa-plus-circle');
					$('#'+elem+' span:first').addClass('fa-minus-circle');
					$('#'+elem+' span:first').removeClass('fa-plus-circle');
					$('#'+elem+' ul').css('display','block');
				}

				var identificador = '#'+elem+' ul.lstTotPos'

				$.ajax({
		            url: './down/newNivel2',
		            type:'POST',
		            dataType: 'html',
		            timeout : 10000,
					data:{pos:valor},
					beforeSend:inicioEnvio,
		            success: function(output_string){
		            		$('ul.lstTotPos').html("");
		                    $('#'+elem+' ul.lstTotPos').html(output_string);
		                    $("#aviso").html("listo nivel 2");
		                },
						complete: function(){
							$('#'+elem+' div.loading_msg').hide();
						}		                
				}).done(function() {
					$('.nivel2').click(function (e){
						var valorlocal = $(this).attr('data-valorlocal');
						var valorpos = $(this).attr('data-valorpos');
						var elemtercern = $(this).attr("id");//segundo_1
						$('#'+elemtercern+' div.loading_msg').show();
						var padre = $('#'+elemtercern).parent().parent().attr('id');//primero_1
						var elementocompleto = '#'+padre+' #'+elemtercern+' .lstDetPos';
						console.log(elementocompleto);

						$.ajax({
							url: './down/newNivel3',
							type:'POST',
							dataType: 'html',
							data:{local:valorlocal,pos:valorpos},
							beforeSend:inicioEnvio,
							success: function(output_string){
							//$(this).children('.lstDetPos').html("test 0954");
								$(elementocompleto).html(output_string);
								$("#aviso").html("listo nivel 3");
								},
							complete: function(){
								$('#'+elemtercern+' div.loading_msg').hide();
							}
						});
						e.stopPropagation();
					});
				});
				e.stopPropagation();
			});
		});
		$( ".containerGrid" ).toggle( "slow" );
	});
/**************GRIDFIN******************/
	//para que funcione la muestra y ocultamiento con el toogle, si no hay que apretarlo 2 veces
	$('#getGrid').trigger('click');
});//fin document ready
function inicioEnvio() {
	/*
	  var x=$("#aviso");
	  x.html(':::::::::::::::::::::::::::');
	  */
}

