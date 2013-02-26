function getdata(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

jQuery(document).ready(function(){

	$(".lightbox").fancybox({
			padding: 10,

			openEffect : 'elastic',
			openSpeed  : 250,

			closeEffect : 'elastic',
			closeSpeed  : 50,

			closeClick : true,

			helpers : {
			overlay : true
			}
	});

	jQuery('#gw_content').wysihtml5({
		"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
		"emphasis": true, //Italics, bold, etc. Default true
		"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
		"html": true, //Button which allows you to edit the generated HTML. Default false
		"link": true, //Button to insert a link. Default true
		"image": true, //Button to insert an image. Default true,
		"color": false //Button to change color of font  
	});

	jQuery('.btn[rel="toggle"]').click(function(){
			var ID = jQuery(this).attr('id');
			ID = ID.replace("button-", "")
			ID = '#'+ID;
			jQuery(ID+'.hide').toggle()
			jQuery(this).toggleClass('btn-info');
			return false;
	})

   jQuery('#dp3').datepicker()

	jQuery('.delete-content').click(function(event){
		event.preventDefault();
		var DELETE = jQuery(this).attr('rel');
		var ID = jQuery(this).attr('title');
		jQuery.ajax({
		  type: "POST",
		  url: "../gw_core/ajax/delete.php",
		  data: { delete: DELETE, id: ID }
		}).done(function( msg ) {
			jQuery('tr#row-'+ID+' td').animate({backgroundColor:'#E06060'}).slideUp()
			jQuery('#del-'+ID).modal('hide')
		});
	})
	
	jQuery('.gw_list-view-dett').each(function(el){
		
		jQuery(this).click(function(){
			var id = jQuery(this).attr('rel')
			jQuery('#dett-'+id).toggle()
	
		})
	})
		
	jQuery('.go_load').click(function(event){
		event.preventDefault();
		jQuery('.loading').html('');
		var content = jQuery(this).attr('rel');
		var newval = content+'-val';
		var value = jQuery('#'+newval).val()
		jQuery('.key_temp#'+content).remove()
		jQuery('.oksubmit#'+content).show()
		jQuery('#load-'+content).html('<p style="text-align:center"><img src="../gw_lib/img/365.png" alt="loading" /></p>').load(
			"../../gw_core/ajax/"+content+".php",
			{ 'value': value, 'type':'new' },
			function() {
            jQuery(this).fadeIn(250); // when page.html has loaded, fade #target in
        	}
        )
	})

	jQuery('.edit_content').click(function(event){
		event.preventDefault();
		jQuery('.loading').html('');
		var content = jQuery(this).attr('rel')
		var href = jQuery(this).attr('href')
		var id = href.replace('#edit-','')
		var el = '#load-'+content+'-'+id;
		jQuery(el).html('<p style="text-align:center"><img src="../gw_lib/img/365.png" alt="loading" /></p>').load(
			"../../gw_core/ajax/"+content+".php",
			{ 'value': id,'type':'edit' },
			function() {
            jQuery(this).fadeIn(250); // when page.html has loaded, fade #target in
        	}
        )
	})
	
	jQuery('.del_att').live('click',function(event){
		event.preventDefault()
		var ID = jQuery(this).attr('href');
		var string = jQuery(this).attr('rel');
		//alert(ID)
		jQuery(ID+' .thumb').html('')

		jQuery.ajax({
		  type: "POST",
		  url: "../gw_core/ajax/delete.php",
		  data: string
		}).done(function( msg ) {
			jQuery(ID+' .upload-box,'+ID+' .start-box').show()
		});
	})
	
	jQuery('.del_meta-gal').live('click',function(event){
		event.preventDefault()
		var elemento = jQuery(this).parent().parent().attr('id');
		elemento = '#'+elemento;
		var string = jQuery(this).attr('rel');
		jQuery.ajax({
		  type: "POST",
		  url: "../gw_core/ajax/delete.php",
		  data: string
		}).done(function( msg ) {
			if(msg) {
				jQuery(elemento).remove()
				alert('Allegato n#'+msg+' rimosso');
			}
		});
	})

	jQuery('.pinterest_style li .pin-thumb').click(function() {
		var check = jQuery('input.check',this);
		
		var back = jQuery(this).css('backgroundColor')
			if(!check.is(':checked')) {
				check.prop('checked', true);
				//jQuery(this).removeClass('span2').addClass('span4').css('z-index','999')
				jQuery(this).animate({backgroundColor:'#F7E6E6',borderColor:'#FFBCBC',color:'#cc0000'},100)
				jQuery('.label',jQuery(this).parent()).addClass('label-important')
				jQuery('.close-del',this).show()
			} else {
				check.prop('checked', false);
				//jQuery(this).removeClass('span4').addClass('span2').css('z-index','0')
				jQuery(this).animate({backgroundColor:'#fff',borderColor:'#ddd',color:'#999'},50)
				jQuery('.label',jQuery(this).parent()).removeClass('label-important')
				jQuery('.close-del',this).hide()
			}
	});

	jQuery('.pinterest_style').isotope({
		filter: '*',
		animationOptions: {
	     duration: 750,
	     easing: 'linear',
	     queue: false,
	   }
	});
	
	jQuery('.gw_del-content').click(function(event){
		event.preventDefault()
		var ID = jQuery(this).attr('rel')
		var type = jQuery(this).attr('title')
		var content = jQuery(this).attr('data-content')
		var answer = confirm('Sei sicuro di voler eliminare questo elemento #'+ID );
		var genitore = jQuery(this).parent().parent().parent()
		
		if(answer) {
			jQuery.ajax({
			  type: "POST",
			  url: "../gw_core/ajax/delete.php",
			  data: { delete: content, id: ID }
			}).done(function( msg ) {
				if(type == 'pinterest') {
					jQuery('.pinterest_style').isotope( 'remove', genitore ).isotope('reLayout');
				} else if(type == 'single-user') {
					location.href = "allusers.php?action=delete&note=true&return=ok&return=error&msg=106";
				} else {
					location.href = "list.php?gw_type="+type+"&action=delete&note=true&return=ok&return=error&msg=105";
				}
			});
		}
		
		
	})

	jQuery('.media-dett').click(function(){
		var ID = jQuery(this).attr('rel')
		jQuery('#modal-dett-html').html('<p style="text-align:center"><img src="../gw_lib/img/365.png" alt="loading" /></p>')
		jQuery.ajax({
		  type: "POST",
		  url: "../gw_core/ajax/media-dett.php",
		  data: { 'media': ID }
		}).done(function( msg ) {
			if(msg) {
				jQuery('#modal-dett-html').hide().html(msg).slideDown()
			}
		});
	})

	jQuery('#filters a').click(function(){
	  var selector = jQuery(this).attr('data-filter');
	    jQuery('.pinterest_style').isotope({ 
		filter: selector,
		animationOptions: {
	     duration: 750,
	     easing: 'linear',
	     queue: false,
		 
	   }
	  });
	  return false;
	});

	jQuery('#filters a').click(function(){
		jQuery('#filters a').removeClass('btn-warning')
       	jQuery(this).addClass('btn-warning')
   	});

})