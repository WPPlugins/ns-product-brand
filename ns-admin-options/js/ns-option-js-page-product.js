jQuery(document).ready( function($) {

var media_uploader = null;

function open_media_uploader_image(tab)
{
   media_uploader = wp.media({
       frame:    "select",
       multiple: false,
        title: 'Select Placeholder Image',
        library: {
        type: 'image' // limits the frame to show only images
       },
   });

   media_uploader.on("select", function(){
       var json = media_uploader.state().get("selection").first().toJSON();
       console.log(json);

       var image_url = json.url;
       var image_id = json.id;
      
        if(tab == 'tab1'){
          jQuery('#brand-image-id').val('');
            jQuery('#brand-image-id').val(image_id);
        
         jQuery('#ns_image_show').attr('src',image_url);
          //  jQuery('#ns_image_show').val(image_url);
        }
          if(tab == 'tab2'){   
        jQuery('#brand-image-id').val('');
            jQuery('#brand-image-id').val(image_id);
         jQuery('#image-tax').attr('src',image_url);
          jQuery('#image-tax').attr('srcset',image_url);
        
        }
   });

   media_uploader.open();
}

jQuery("#ct_tax_media_button").on('click', function() {
        open_media_uploader_image('tab1');
    });
jQuery("#ct_tax_button").on('click', function() {
        open_media_uploader_image('tab2');
    });
jQuery("#ct_tax_media_remove").on('click', function() {
       jQuery('#brand-image-id').val('');  
         jQuery('#ns_image_show').attr('src','');
    });
jQuery("#ct_tax_remove").on('click', function() {
        jQuery('#brand-image-id').val('');
         jQuery('#image-tax').attr('src','');
          jQuery('#image-tax').attr('srcset','');
    });
       
   });