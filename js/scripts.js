/**
 * 
 * Scripts
 * 
 * This will handle all the jquery stuff.
 * 
 * @return html string
 * @author epstudios
 *      
 */

jQuery(document).ready(function ($) {
    
  $(function() {
    $( ".eps-admin-menu-list" ).sortable();
    $( ".eps-admin-menu-list" ).disableSelection();
  });
   
   $('select#details\\[menu_item_type\\]').each( function() { show_options( $(this).val() ); });
   $('select#details\\[menu_item_type\\]').change(function(){ show_options( $(this).val() ); });
   
   function show_options( target ) {
       switch(target) {
        case 'single':
            $('#details\\[triple_price\\], #details\\[triple_serving\\], #details\\[double_price\\], #details\\[double_serving\\]').closest('p').hide();
            $('#details\\[single_price\\], #details\\[single_serving\\]').closest('p').show();
            break;
        case 'double':
            $('#details\\[triple_price\\], #details\\[triple_serving\\]').closest('p').hide();
            $('#details\\[double_price\\], #details\\[double_serving\\], #details\\[single_price\\], #details\\[single_serving\\]').closest('p').show();
            break;
        case 'triple':
            $('#details\\[triple_price\\], #details\\[triple_serving\\], #details\\[double_price\\], #details\\[double_serving\\], #details\\[single_price\\], #details\\[single_serving\\]').closest('p').show();
            break;
        }
   }
   
   /**
    * 
    * 
    * Tabs
    */
    $('.eps-tab-nav a').click(function(e){
        e.preventDefault();
        var target = $(this).attr('href');
        
        height = $('eps-' + target + '-pane').height();
        
        $('.eps-panes .eps-pane').hide();
         
        $(target + '-pane').show().height( 'auto' );
        
        $('.eps-tab-nav a').removeClass('active');
        $(this).addClass('active');
     });
     
     $('.eps-tab-nav li:first-child a').addClass('active');
     $('.eps-panes > .eps-pane').hide();
     
     var hash = window.location.hash;
     
     if( hash ) {
        $(hash+'-pane').show();
        $('.eps-tab-nav a').removeClass('active');
        $('.eps-tab-nav a').eq( $(hash +'-pane').index() ).addClass('active');
     } else {
        $('.eps-panes .eps-pane:first-child').show();  
        $('.eps-tab-nav a:first-child').addClass('active');
     }
   
});



