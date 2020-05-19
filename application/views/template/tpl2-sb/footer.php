</div>
  <div class="footer">

    <h2 class="empresas"> 
      EMPRESAS
      <img class="sb" src="<?php echo base_url(); ?>assets/template/tpl2-sb/img/sb.png">
    </h2>

    
    <ul class="uas">
      <li><a href="#" class="u">d</a></li>
      <li><a href="#" class="a">+</a></li>
      <li><a href="#" class="s">i</a></li>
    </ul>

    <h2 class="intranet"><?php echo NOMBREPROYECTO; ?></h2>
    <a href="#" class="scroll-top"></a>
  </div>
	



    <!-- date picker 
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/daterangepicker.js"></script>
    -->
    <script type="text/javascript">

        $(document).on("ready",function(){

            /**/
            //Se activa cuando el scroll supera los 100px
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('a.scroll-top').fadeIn();
                } else {
                    $('a.scroll-top').fadeOut();
                }
            });
            //Crea la animacion al dar clic sobre el boton
            $('a.scroll-top').click(function() {
                $("html, body").animate({scrollTop: 0}, 600);
                return false;
            });
            /**/
            
          $("#menu").hide();
          $(".menu").mouseover(function(){
              $("#menu").show();
          });
          $("#menu").mouseleave(function(){
              $(this).hide();
          });
          $("table tr:odd").addClass("odd");

  		 $("a.droptopmenu").click(function(e){
			 var goTo = $(this).attr("href");
		        window.location = goTo;    
		        e.preventDefault();         
		   });    

        });

    </script>
  </body>
</html>