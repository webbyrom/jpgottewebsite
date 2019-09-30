/**
 * Admin scripts for the theme.
 * Use for widgets and some other admin UI/UX features.
 *
 * Package: WP Cargo pifour
 * Since: WP Cargo pifour 1.0
 */

window.RedExpMedia = window.RedExpMedia || {};

jQuery( document ).ready( function($) {
    "use strict";
    /*--------------------------------------------------------------
    ## SINGLE IMAGE UPLOAD
    --------------------------------------------------------------*/
    RedExpMedia.Image = {

        // Media upload frame
        //--------------------------------------------------
        mediaSelect : function( event, action, holder_id ) {
            var object = this;

            var frame = wp.media({
                title : cargo_pifourMediaLocalize.add_image,
                multiple : false,
                library : { type : 'image' }
            });

            if ( action == 'edit' ) {
                var attachment_id = $( event.target ).closest( 'li[data-id]' ).attr( 'data-id' );
                frame.on( 'open', function(e) {
                    var selection = frame.state().get('selection'),
                        attachment;

                    selection.remove( selection.first() );

                    if ( 0 !== attachment_id ) {
                        attachment = wp.media.attachment( attachment_id );
                        selection.add( attachment ? attachment : '' );
                    }
                });
            }

            frame.on( 'select', function() {
                object.render( event, action, frame.state().get('selection').first().toJSON(), holder_id );
            });

            frame.open();
        },

        // Add a new image
        //--------------------------------------------------
        add : function( event, holder_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'add', holder_id );
        },

        // Edit an image
        //--------------------------------------------------
        edit : function( event, holder_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'edit', holder_id );
        },

        // Remove an image
        //--------------------------------------------------
        remove : function( event, holder_id ) {
            event.preventDefault();
            this.render( event, 'remove', 0, holder_id );
        },

        // Render image view
        //--------------------------------------------------
        render : function( event, action, attachment, holder_id ) {
            var object = this,
                imageHolder = $( '#' + holder_id ),
                field_id = $( event.target ).closest( '[data-img-mu-field]' ).attr( 'data-img-mu-field' );
            var valueHolder = $( '#' + field_id );

            if ( ! valueHolder.length ) {
                return;
            }

            if ( imageHolder.children().length > 1 ) {
                imageHolder.children().first().remove();
            }

            valueHolder.val( attachment.id );

            if ( action == 'remove' )
                return;

            imageHolder.prepend(
                '<li data-id="' + attachment.id + '"' + 
                    ' style="background-image:url(' + attachment.url + ');">' +
                    '<a class="image-edit" href="#" onclick="RedExpMedia.Image.edit(event,\'' + holder_id + '\')">' +
                        '<i class="dashicons dashicons-edit"></i>' +
                    '</a>' +
                    '<a class="image-delete" href="#" onclick="RedExpMedia.Image.remove(event,\'' + holder_id + '\')">' +
                        '<i class="dashicons dashicons-trash"></i>' +
                    '</a>' +
                '</li>'
            );
        }
    };


    /*--------------------------------------------------------------
    ## MULTIPLE IMAGES UPLOAD
    --------------------------------------------------------------*/

    RedExpMedia.Images = {

        setup: function() {
            var object = this;
            $( '[data-img-mu-field]' ).each( function() {
                if ( $( this ).children().length <= 2 ) return;
                var this_id = $( this ).attr( 'id' ),
                    field_id = $( this ).attr( 'data-img-mu-field' );

                var $field = $( '#' + field_id );

                if ( this_id && field_id && $field.length ) {
                    $( this ).sortable({
                        items: "> *:not(:last-child)",
                        stop: function( e, ui ) {
                            object.generate_values( this_id, field_id );
                        }
                    });
                }
            });
        },

        // Media upload frame
        //--------------------------------------------------
        mediaSelect : function( e, action, holder_id ) {
            var object = this,
                frame;

            var mediaOptions = {
                title : cargo_pifourMediaLocalize.add_images,
                multiple : true,
                library : { type : 'image' }
            };

            if ( action == 'edit' ) {
                mediaOptions.multiple = false;
            }

            frame = wp.media( mediaOptions );

            if ( action == 'edit' ) {
                attachment_id = $( event.target ).closest( 'li[data-id]' ).attr( 'data-id' );

                frame.on( 'open', function() {
                    var selection = frame.state().get('selection'),
                        attachment;

                    if ( selection.length ) {
                        selection.remove( selection );
                    }

                    if ( ! isNaN( attachment_id ) && 0 !== attachment_id ) {
                        attachment = wp.media.attachment( attachment_id );
                        if ( attachment ) {
                            selection.add( attachment ? attachment : '' );
                        }
                    }
                });
            }

            frame.on( 'select', function() {
                object.render( e, action, frame.state().get('selection'), holder_id );
            });

            frame.open();
        },

        // Add new images
        //--------------------------------------------------
        add : function( event, holder_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'add', holder_id );
        },

        // Edit an image
        //--------------------------------------------------
        edit : function( event, holder_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'edit', holder_id );
        },

        // Remove an image
        //--------------------------------------------------
        remove : function( event, holder_id ) {
            event.preventDefault();
            this.render( event, 'remove', 0, holder_id );
        },

        // Render images view
        //--------------------------------------------------
        render : function( event, action, attachments, holder_id ) {
            var object = this,
                imagesHolder = $( '#' + holder_id ),
                field_id = $( event.target ).closest( '[data-img-mu-field]' ).attr( 'data-img-mu-field' ),
                valueHolder = $( '#' + field_id );

            var actionFrom = $( event.target ).closest( 'li[data-id]' );

            if ( ! valueHolder.length ) {
                return;
            }

            switch( action ) {
                case 'remove' :
                    actionFrom.remove();
                    break;

                case 'edit' :
                    if ( attachments ) {
                        var attachment = attachments.first().toJSON();
                        $(
                            '<li data-id="' + attachment.id + '"' + 
                                ' style="background-image:url(' + attachment.url + ');">' +
                                '<a class="image-edit" href="#" onclick="RedExpMedia.Images.edit(event,\'' + holder_id + '\')">' +
                                    '<i class="dashicons dashicons-edit"></i>' +
                                '</a>' +
                                '<a class="image-delete" href="#" onclick="RedExpMedia.Images.remove(event,\'' + holder_id + '\')">' +
                                    '<i class="dashicons dashicons-trash"></i>' +
                                '</a>' +
                            '</li>'
                        ).insertBefore( actionFrom );
                        actionFrom.remove();
                    }
                    break;

                // Default to 'add'
                default:
                    if ( attachments ) {
                        attachments.map( function( attachment ) {
                            attachment = attachment.toJSON();
                            $(
                                '<li data-id="' + attachment.id + '"' + 
                                    ' style="background-image:url(' + attachment.url + ');">' +
                                    '<a class="image-edit" href="#" onclick="RedExpMedia.Images.edit(event,\'' + holder_id + '\')">' +
                                        '<i class="dashicons dashicons-edit"></i>' +
                                    '</a>' +
                                    '<a class="image-delete" href="#" onclick="RedExpMedia.Images.remove(event,\'' + holder_id + '\')">' +
                                        '<i class="dashicons dashicons-trash"></i>' +
                                    '</a>' +
                                '</li>'
                            ).insertBefore( actionFrom );
                        });
                    }
            }

            object.generate_values( holder_id, field_id );

            imagesHolder.sortable({
                items: "> *:not(:last-child)",
                stop: function( e, ui ) {
                    object.generate_values( holder_id, field_id );
                }
            });
        },

        // Generate value after render
        //--------------------------------------------------
        generate_values : function( holder_id, field_id ) {
            var image_ids = [],
                imagesHolder = $( '#' + holder_id ),
                valueHolder = $( '#' + field_id );
            $('#' + holder_id + ' li[data-id]').each( function() {
                var image_id = $(this).data('id');
                if ( undefined !== image_id && ! isNaN( image_id ) && image_id !== 0 ) {
                    image_ids.push( image_id );
                }
            } );
            
            if ( image_ids.length > 0 ) {
                valueHolder.val( image_ids.join( "," ) );
            }
            else
            {
                valueHolder.val('');
            }
        }
    };

    RedExpMedia.Images.setup();


    /*--------------------------------------------------------------
    ## Video upload
    --------------------------------------------------------------*/

    RedExpMedia.Video = {

        // Media upload frame
        //--------------------------------------------------
        mediaSelect : function( event, action, field_id ) {
            var object = this;

            var frame = wp.media({
                title : cargo_pifourMediaLocalize.add_video,
                multiple : false,
                library : { type : 'video' }
            });

            frame.on( 'select', function() {
                object.render( event, action, frame.state().get('selection').first().toJSON(), field_id );
            });

            frame.open();
        },

        // Add a new video
        //--------------------------------------------------
        add : function( event, field_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'add', field_id );
        },

        // Remove a video
        //--------------------------------------------------
        remove : function( event, field_id ) {
            event.preventDefault();
            this.render( event, 'remove', 0, field_id );
        },

        // Render video view
        //--------------------------------------------------
        render : function( event, action, attachment, field_id ) {
            var valueHolder = $( '#' + field_id );

            if ( ! valueHolder.length ) {
                return;
            }

            if ( action == 'remove' || ! attachment ) {
                valueHolder.val( '' ).html( '' );
                return;
            }

            valueHolder.html( '[video src="' + attachment.url + '"/]' );
        }
    };


    /*--------------------------------------------------------------
    ## Audio upload
    --------------------------------------------------------------*/

    RedExpMedia.Audio = {

        // Media upload frame
        //--------------------------------------------------
        mediaSelect : function( event, action, field_id ) {
            var object = this;

            var frame = wp.media({
                title : cargo_pifourMediaLocalize.add_audio,
                multiple : false,
                library : { type : 'audio' }
            });

            frame.on( 'select', function() {
                object.render( event, action, frame.state().get('selection').first().toJSON(), field_id );
            });

            frame.open();
        },

        // Add a new audio
        //--------------------------------------------------
        add : function( event, field_id ) {
            event.preventDefault();
            this.mediaSelect( event, 'add', field_id );
        },

        // Remove a audio
        //--------------------------------------------------
        remove : function( event, field_id ) {
            event.preventDefault();
            this.render( event, 'remove', 0, field_id );
        },

        // Render audio view
        //--------------------------------------------------
        render : function( event, action, attachment, field_id ) {
            var valueHolder = $( '#' + field_id );

            if ( ! valueHolder.length ) {
                return;
            }

            if ( action == 'remove' || ! attachment ) {
                valueHolder.val( '' ).html( '' );
                return;
            }

            valueHolder.html( '[audio src="' + attachment.url + '"/]' );
        }
    };

});
