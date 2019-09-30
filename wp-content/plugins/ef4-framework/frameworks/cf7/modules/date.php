<?php
/**
** A base module for the following types of tags:
** 	[date] and [date*]		# Date
**/

/* form_tag handler */

add_action( 'wpcf7_init', 'ef4_cf7_add_form_tag_date' );

function ef4_cf7_add_form_tag_date() {
	if (function_exists('wpcf7_add_form_tag'))
	wpcf7_add_form_tag( array( 'datepicker','datepicker*' ),
		'ef4_cf7_date_form_tag_handler', array( 'name-attr' => true ) );
}
function ef4_cf7_date_form_tag_handler( $tag ) {
	if ( empty( $tag->name ) ) {
		return '';
	}
    wp_enqueue_script('ef4-front');
    wp_enqueue_style('flatpickr');
    wp_enqueue_script('flatpickr');

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );

	$class .= ' wpcf7-validates-as-date';

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$atts = array();

	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );
	$atts['data-min'] = $tag->get_option( 'min','[-0-9a-zA-Z_:]+',true );
	$atts['data-max'] = $tag->get_option( 'max','[-0-9a-zA-Z_:]+',true );
//	$atts['step'] = $tag->get_option( 'step', 'int', true );

	if ( $tag->has_option( 'readonly' ) ) {
		$atts['readonly'] = 'readonly';
	}

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}

	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

	$value = (string) reset( $tag->values );

	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$atts['placeholder'] = $value;
		$value = '';
	}

	$value = $tag->get_default_option( $value );

	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['value'] = $value;
    $atts['class'].= ' flatpickr-date';
    $atts['type'] = 'text';

	$atts['name'] = $tag->name;

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
		sanitize_html_class( $tag->name ), $atts, $validation_error );

	return $html;
}


/* Validation filter */

add_filter( 'wpcf7_validate_datepicker', 'ef4_cf7_date_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_datepicker*', 'ef4_cf7_date_validation_filter', 10, 2 );

function ef4_cf7_date_validation_filter( $result, $tag ) {
	$name = $tag->name;

	$min = $tag->get_date_option( 'min' );
	$max = $tag->get_date_option( 'max' );

	$value = isset( $_POST[$name] )
		? trim( strtr( (string) $_POST[$name], "\n", " " ) )
		: '';

	if ( $tag->is_required() && '' == $value ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	} elseif ( '' != $value && ! wpcf7_is_date( $value ) ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_date' ) );
	} elseif ( '' != $value && ! empty( $min ) && $value < $min ) {
		$result->invalidate( $tag, wpcf7_get_message( 'date_too_early' ) );
	} elseif ( '' != $value && ! empty( $max ) && $max < $value ) {
		$result->invalidate( $tag, wpcf7_get_message( 'date_too_late' ) );
	}

	return $result;
}


/* Messages */

//add_filter( 'wpcf7_messages', 'ef4_cf7_date_messages' );
//
//function ef4_cf7_date_messages( $messages ) {
//	return array_merge( $messages, array(
//		'invalid_date' => array(
//			'description' => __( "Date format that the sender entered is invalid", 'contact-form-7' ),
//			'default' => __( "The date format is incorrect.", 'contact-form-7' )
//		),
//
//		'date_too_early' => array(
//			'description' => __( "Date is earlier than minimum limit", 'contact-form-7' ),
//			'default' => __( "The date is before the earliest one allowed.", 'contact-form-7' )
//		),
//
//		'date_too_late' => array(
//			'description' => __( "Date is later than maximum limit", 'contact-form-7' ),
//			'default' => __( "The date is after the latest one allowed.", 'contact-form-7' )
//		),
//	) );
//}


/* Tag generator */

add_action( 'wpcf7_admin_init', 'ef4_cf7_add_tag_generator_date', 19 );
function ef4_cf7_add_tag_generator_date() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'datepicker', __( 'Datepicker', 'ef4-framework' ),
		'ef4_cf7_tag_generator_date' );
}

function ef4_cf7_tag_generator_date( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );
	$type = 'datepicker';

	$description = __( "Generate a form-tag for a date input field. For more details, see %s.", 'contact-form-7' );

	$desc_link = wpcf7_link( __( 'https://contactform7.com/date-field/', 'contact-form-7' ), __( 'Date Field', 'contact-form-7' ) );

?>
<div class="control-box">
<fieldset>
<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
	<label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'contact-form-7' ) ); ?></label></td>
	</tr>

	<tr>
	<th scope="row"><?php echo esc_html( __( 'Range', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Range', 'contact-form-7' ) ); ?></legend>
            <label>
                <?php echo esc_html( __( 'Min', 'ef4-framework' ) ); ?>
                <input type="text" name="min" class="oneline option" />
            </label>
            <i><?php esc_html_e('Format "Y-m-d" or "today"','ef4-framework') ?></i>
            <label>
                <?php echo esc_html( __( 'Max',  'ef4-framework') ); ?>
                <input type="text" name="max" class="oneline option" />
            </label>
            <i><?php esc_html_e('Format "Y-m-d"','ef4-framework') ?></i>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>
<?php
}
