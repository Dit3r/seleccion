$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);



$(document).ready( function (){


	/**************GENERA GRAFICOS POR RANGO CUANDO CARGA CON CLICK EN ACEPTAR FIN******************/
	$('#getRango').click(function() {	
		/**************grafico barra/piefin***************/
		$.ajax({
			type:"POST",
			url :"./rango/generaGraphRango",
			dataType:"html",
			beforeSend:inicioEnvio,
		 	timeout : 30000,
		 	data:{
		 		from:$('#from').val(),
		 		to:$('#to').val()
		 	},
			success: function(dato){
				$('#micontenidoajax').html(dato);
				var ts = Math.round((new Date()).getTime() / 1000);
				$('#graphBarraToptenRango').attr('src', './assets/generated/graphBarraToptenRango.png?'+ts);
				$('#graphPieGralRango').attr('src', './assets/generated/graphPieGralRango.png?'+ts);
			},
			complete: function(){
				$('#loadingRango').hide();
				console.log("ejecucion de generaGraphRango con el CLICK");
			},				
			error: function(xhr, ajaxOptions, thrownError) { 
				msg = "A ocurridoff un error ";
				console.log(msg+" "+xhr.status + " " + xhr.statusText);
			}
		});//fin ajax

			
		//****trae la tabla con el detalle de los top 10****//
        $.ajax({
            url: './rango/newGeneraTop10',
            type:'POST',
            dataType: 'html',
            beforeSend:inicioEnvio,
            timeout : 30000,
		 	data:{
		 		from:$('#from').val(),
		 		to:$('#to').val(),
	            locfrom:$('#locfrom').val(),
	            locto:$('#locto').val()
		 	},            
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
		            url: './rango/newNivel2',
		            type:'POST',
		            dataType: 'html',
		            timeout : 30000,
					data:{
						pos:valor,
				 		from:$('#from').val(),
				 		to:$('#to').val()						
					},
					//beforeSend:inicioEnvio,
		            success: function(output_string){
		            		$('ul.lstTotPos').html("");
		                    $('#'+elem+' ul.lstTotPos').html(output_string);
		                    $("#aviso").html("listo nivel 2");
		                },
					complete: function(){
						$('#'+elem+' div.loading_msg').hide();
						$('#loadingRango').hide();
					}		                
				}).done(function() {
					$('.nivel2').click(function (e){
						var valorlocal = $(this).attr('data-valorlocal');
						var valorpos = $(this).attr('data-valorpos');
						var elemtercern = $(this).attr("id");//segundo_1
						$('#'+elemtercern+' div.loading_msg').show();
						var padre = $('#'+elemtercern).parent().parent().attr('id');//primero_1
						var elementocompleto = '#'+padre+' #'+elemtercern+' .lstDetPos';

						$.ajax({
							url: './rango/newNivel3',
							type:'POST',
							dataType: 'html',
							timeout : 30000,
							data:{
								local:valorlocal,
								pos:valorpos,
						 		from:$('#from').val(),
						 		to:$('#to').val()									
							},
							//beforeSend:inicioEnvio,
							success: function(output_string){
							//$(this).children('.lstDetPos').html("test 0954");
								$(elementocompleto).html(output_string);
								$("#aviso").html("listo nivel 3");
								},
							complete: function(){
								$('#'+elemtercern+' div.loading_msg').hide();
								$('#loadingRango').hide();
							}
						});
						e.stopPropagation();
					});
				});
				e.stopPropagation();
			});
		});
	//****trae la tabla con el detalle de los top 10****//
/////////////////NUEVO GRID //////////////////////
	});
/**************DATEPICKERINICIO***************/
	 $( "#from" ).datepicker({
	minDate: "-1m",
	defaultDate: "0",
	changeMonth: true,
	numberOfMonths: 1,
	dateFormat: "dd/mm/yy",
	onClose: function( selectedDate ) {
	$( "#to" ).datepicker( "option", "minDate", selectedDate );
	}
	});
	$( "#to" ).datepicker({
	maxDate: 0,
	defaultDate: "0",
	changeMonth: true,
	numberOfMonths: 1,
	dateFormat: "dd/mm/yy",
	onClose: function( selectedDate ) {
	$( "#from" ).datepicker( "option", "maxDate", selectedDate );
	}
	});

/**************DATEPICKERFIN***************/

	/*****************DESCARGAR CSVINICIO************************/
	$('#getCsv').click(function() {
		//alert("test 1051");
        $.ajax({
            url: './rango/csv/',
            type:'POST',
            dataType: 'html',
            timeout : 30000,
            beforeSend:inicioEnvio,
		 	data:{
		 		from:$('#from').val(),
		 		to:$('#to').val()
		 	},            
            success: function(output_string){
                    //$('.containerGrid').html(output_string);
                    //$('.nivel1 span:first').addClass('fa fa-plus-circle');
                    console.log("todo ok")
                } 
		})

	});
	/*****************DESCARGAR CSVFIN************************/	

	/****************RANGO LOCALES INICIO**************************/
	//BOTON ON/OFF INICIO
	$('.btn-toggle').click(function(e) {

	    	$('#locfrom').val(1);
	    	$('#locto').val(950);

	    $(this).find('.btn').toggleClass('active');  
	    
	    if ($(this).find('.btn-primary').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-primary');
	    	$('#filtro').toggleClass('esconder');
	    }
	    $(this).find('.btn').toggleClass('btn-default');
	    e.stopPropagation();
	});

	$('.cifra').click(function(e) {
		//alert("cccc");
	    var id = $(this).attr('id');
	    $('#'+id).val("");
	    //console.log(cccccc);
	    e.stopPropagation();
	});

	$('form').submit(function(){
		//alert($(this["options"]).val());
	    return false;
	});
	//BOTON ON/OFF FIN

	//VALIDACION RANGO INICIO
	$('#locfrom').jStepper({minValue:1, maxValue:950, minLength:2});

	$('#locto').jStepper({minValue:1, maxValue:950, minLength:2});
	$('#locto').blur(function() {

		var vallocfrom = parseInt($('#locfrom').val());
		var vallocto = parseInt($('#locto').val());
		
		if (vallocto < vallocfrom) {
			$('#locto').val(vallocfrom);
		};
	});
	//VALIDACION RANGO INICIO
	/****************RANGO LOCALES FIN**************************/
});//FIN DOCUMENT READY


///////////////////////////////////////////////////////////
function cargaPaginaRango () {
    /**************GENERA GRAFICOS POR RANGO CUANDO CARGA LA PAGINA******************/
    $.ajax({
        type:"POST",
        url :"./rango/generaGraphRango",
        dataType:"html",
        timeout : 30000,
        success: function(dato){
            var ts = Math.round((new Date()).getTime() / 1000);
            $('#graphBarraToptenRango').attr('src', './assets/generated/graphBarraToptenRango.png?'+ts);
            $('#graphPieGralRango').attr('src', './assets/generated/graphPieGralRango.png?'+ts);
            console.log("ejecucion de generaGraphRango al cargar pagina en success");
        },      
        error: function(xhr, ajaxOptions, thrownError) { 
                    msg = "A ocurridoff un error ";
                    console.log(msg+" "+xhr.status + " " + xhr.statusText);
        },
        complete: function(){
            $('#loadingRango').hide();
            console.log("ejecucion de generaGraphRango al cargar pagina");
        }   
    });//fin ajax

    //****GRID TOP 10  (grid)****//
    $.ajax({
        url: './rango/newGeneraTop10',
        type:'POST',
        dataType: 'html',
        timeout : 30000,
        beforeSend:inicioEnvio,
        data:{
            from:$('#from').val(),
            to:$('#to').val()
        },            
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
                url: './rango/newNivel2',
                type:'POST',
                dataType: 'html',
                timeout : 30000,
                data:{
                    pos:valor,
                    from:$('#from').val(),
                    to:$('#to').val()                       
                },
                //beforeSend:inicioEnvio,
                success: function(output_string){
                $('ul.lstTotPos').html("");
                $('#'+elem+' ul.lstTotPos').html(output_string);
                $("#aviso").html("listo nivel 2");
                },
                complete: function(){
                    $('#'+elem+' div.loading_msg').hide();
                    $('#loadingRango').hide();
                }                       
            }).done(function() {
                $('.nivel2').click(function (e){
                    var valorlocal = $(this).attr('data-valorlocal');
                    var valorpos = $(this).attr('data-valorpos');
                    var elemtercern = $(this).attr("id");//segundo_1
                    $('#'+elemtercern+' div.loading_msg').show();
                    var padre = $('#'+elemtercern).parent().parent().attr('id');//primero_1
                    var elementocompleto = '#'+padre+' #'+elemtercern+' .lstDetPos';
                    $.ajax({
                        url: './rango/newNivel3',
                        type:'POST',
                        dataType: 'html',
                        timeout : 30000,
                        data:{
                            local:valorlocal,
                            pos:valorpos,
                            from:$('#from').val(),
                            to:$('#to').val()                                   
                        },
                        //beforeSend:inicioEnvio,
                        success: function(output_string){
                        //$(this).children('.lstDetPos').html("test 0954");
                            $(elementocompleto).html(output_string);
                            $("#aviso").html("listo nivel 3");
                            },
                        complete: function(){
                            $('#'+elemtercern+' div.loading_msg').hide();
                            $('#loadingRango').hide();
                        }
                    });
                    e.stopPropagation();
                });
            });
            e.stopPropagation();
        });
    });
    /**************GENERA GRAFICOS POR RANGO CUANDO CARGA LA PAGINA FIN******************/

}//fin funcion cargaPaginaRango

//carga todo al iniciar la pagina
cargaPaginaRango();
///////////////////////////////////////////////////////////


function inicioEnvio() {
	  //var x=$("#loadingRango");
	  //x.html(':::::::::::::::::::::::::::');
	  $('#loadingRango').show();
}

