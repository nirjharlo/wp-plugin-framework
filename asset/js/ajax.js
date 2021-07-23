/**
 *
 * Sample AJAX JS file
 * Callback in NirjharLo\\WP_Plugin_Framework\\Lib\\Ajax
 *
 */
 jQuery(document).ready(function() {

   jQuery("#add_by_ajax form").submit(function() {

     event.preventDefault();

     var val = jQuery("input[name='text_name']").val();

       jQuery.post(
         ajax.url,
         { 'action': 'custom_name', 'val': val },
         function(response) {
           if ( response != '' && response != false && response != undefined ) {

             var data = JSON.parse(response);
             // Do some stuff
           }
         }
       );
     }
   });
 });
