/**
 * 
 * PHOTO UPLOADER
 * 
 * This script will launch a Wordpress media uploader.
 * 
 * @return html string
 * @author epstudios
 *      
 */


jQuery(document).ready(function ($) {

 
    // This requires that wp_enqueue_media has been called prior.
    var eps_custom_uploader;
 
    $('button.upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if ( eps_custom_uploader ) {
            eps_custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        eps_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the data and set it as the text field's value
        eps_custom_uploader.on('select', function() {
            attachment = eps_custom_uploader.state().get('selection').first().toJSON(); // get the first
            $('#term_thumbnail_id').val(attachment.id);
            $('#term_thumbnail_img img').attr('src', attachment.url);
        });
 
        //Open the uploader dialog
        eps_custom_uploader.open();
 
    });
 
 
});