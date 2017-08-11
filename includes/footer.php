</div>

<footer class="text-center" id="footer">&copy; Copyright 2016-2020 Shaunta's Boutique</footer>



<script>
  jQuery(window).scroll(function(){
  	 var vscroll = jQuery(this).scrollTop();
  	 jQuery('#logotext').css({
  		"transform" : "translate(0px, "+vscroll/2+"px)"
  	 });
 
     var vscroll = jQuery(this).scrollTop();
  	 jQuery('#back-flower').css({
  		"transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
  	 });

  	 var vscroll = jQuery(this).scrollTop();
  	 jQuery('#fore-flower').css({
  		"transform" : "translate(0px, -"+vscroll/2+"px)"
  	 });

  });

  function detailsmodal(id){
    var data = {"id" : id};
    jQuery.ajax({
      url : '/tutorial/includes/detailsmodal.php',
      method : "post",
      data : data,
      success : function(data){
        jQuery('body').append(data);
        jQuery('#details-modal').modal('toggle');
      },
      error : function(){
        alert("SOMETHING WENT WRONG");
      }
    });
  }
</script>
</body>	
</html>