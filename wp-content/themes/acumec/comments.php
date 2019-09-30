
<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
      
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title heading-heebo">
			<?php  
            $comments_number = get_comments_number();
            if ( '1' === $comments_number ) {?>
                <?php printf( _x( '01 COMMENT', 'comments title', 'acumec' ), ''); ?>  
           <?php }
           else{
           		 if ( $comments_number < 10 ) {
           		 	printf( _nx( '( %1$s ) COMMENT ', '0%1$s COMMENTS', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), '');
           		 }
           		else {
           			printf( _nx( '( %1$s ) COMMENT ', '%1$s COMMENTS', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), '');
        	 	} 
           	}?>
		</h2>
 
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true, 
				'avatar_size' => 80,
                'callback'          => 'acumec_comment',
			) );
			?>
		</ol><!-- .comment-list -->

		<?php acumec_comment_nav(); ?>

	<?php endif; // have_comments() ?>
    <?php 
    $commenter = wp_get_current_commenter();
	 
	$args = array(
			'id_form'           => 'commentform',
			'id_submit'         => 'submit',
			'title_reply'       => esc_html__( 'LEAVE A COMMENT','acumec'),
			'title_reply_to'    => esc_html__( 'Add Comment To %s','acumec'),
			'cancel_reply_link' => esc_html__( 'Cancel Reply','acumec'),
			'label_submit'      => esc_html__( 'Submit Now','acumec'),
			'comment_notes_before' => '',
			'comment_notes_after'   => '',
'fields' => apply_filters( 'comment_form_default_fields', array(

				'author' =>
				'<div class="col-md-6 col-sm-12 spacing-r5"><p class="comment-form-author">'.
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
				'" size="30" aria-required="true" required="required" placeholder="'.esc_html__('Name ','acumec').'"/></p></div>',

				'email' =>
				'<div class="col-md-6 col-sm-12 spacing-l5"><p class="comment-form-email">'.
				'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'" size="30" aria-required="true" required="required" placeholder="'.esc_html__('Email','acumec').'"/></p></div>',
                
                'url' =>
				'<div class="col-md-12 col-sm-12"><p class="comment-form-phone">'.
				'<input id="phone" name="phone" type="text" value="'. esc_attr(  $commenter['comment_author_url'] ).'" size="30" aria-required="true" required="required" placeholder="'.esc_html__('Phone Number','acumec').'"/></p></div>',
                
			)
			),
			'comment_field' =>  '<div class="col-md-12 col-sm-12"><p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" required="required" placeholder="'.esc_html__('Comment','acumec').'"></textarea></p></div>',
	    );
	comment_form($args);

    ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'acumec' ); ?></p>
	<?php endif; ?>

	

</div><!-- .comments-area -->
