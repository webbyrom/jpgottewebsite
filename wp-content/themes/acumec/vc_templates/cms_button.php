<?php 
$size = $custom_size = $align = $link = $title = $button_block = $el_class = $add_icon = $i_align = $css = $a_href = $a_title = $a_target = $icon_name ='';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
 
//parse link
$link = ( '||' === $link ) ? '' : $link;
$link = vc_build_link( $link );
$use_link = false;
if ( strlen( $link['url'] ) > 0 ) {
	$use_link = true;
	$a_href = $link['url'];
	$a_title = $link['title'];
	$a_target = $link['target'];
}
$position = 'pos-inline';
if ($button_block=='1' && $align != 'align' ) {
	$position = 'btn-block';
}
elseif($button_block!='1' && $align != 'align') {
	$position = 'pos-block';
}
$wrapper_classes = array(
	'cms-btn',
    $this->getExtraClass( $el_class ),
    'text-' . $align,
    $position,
);  
$class_to_filter = implode( ' ', array_filter( $wrapper_classes ) );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$border_button = $btn_bg = $btn_type_cls = '';
$size = !empty($atts['size']) ? $atts['size'] : '';
if ($size == 'btn-custom') {
	if(!empty($spacing) ) {
		$custom_size = 'padding: '.$spacing.'; ';
	}
}
$radius ='border-radius: 0;';
if(!empty($border_radius)){
	$radius ='border-radius:'.$border_radius.'; ';
}
$attributes[] = 'style="'.$custom_size.$radius.'text-align: inherit;"';
if (!empty($atts['btn_style']) && $atts['btn_style']=='btn-theme') {
	$btn_type_cls = $btn_type_theme;
}
else {
	$btn_type_cls = $btn_type_bootstrap;
}
$color_trans ='';
if($button_bg == '1'){
	$btn_bg ='btn-bg';
	$color_trans = !empty($color_transparent) ? $color_transparent : '';
}

$button_classes = array(
    'btn',
    $btn_type_cls,
	$size,
	$btn_bg,
	$color_trans,
);

$button_html = $title;

if ( '' === trim( $title ) ) {
	$button_classes[] = '';
	$button_html = '<span>&nbsp;</span>';
}
if ($button_block=='1' && $align != 'align' ) {
	$button_classes[] = 'btn-block';
}

if ( isset($add_icon) && $add_icon == '1' ) {
	$button_classes[] = 'btn-icon-' . $i_align;
    $icon_name = "icon_" . $atts['icon_type'];
    $icon_class = isset($atts[$icon_name]) ? $atts[$icon_name] : '';
     
	$icon_html = '<i class="btn-icon ' . esc_attr( $icon_class ) . '"></i>';
 
	if ( 'left' === $i_align ) {
		$button_html = $icon_html . ' ' . $button_html;
	} else {
		$button_html .= ' ' . $icon_html;
	}
}

if ( $button_classes ) {
	$button_classes = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $button_classes ) ), $this->settings['base'], $atts ) );
	$attributes[] = 'class="' . trim( $button_classes ) . '"';
}

if ( $use_link ) {
	$attributes[] = 'href="' . esc_url( trim( $a_href ) ) . '"';
	$attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
	if ( ! empty( $a_target ) ) {
		$attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
	}
}
$attributes = implode( ' ', $attributes );
?> <div class="<?php echo trim( esc_attr( $css_class ) ) ?>" > 
<?php if ( $use_link ) {
		echo '<a ' . $attributes . '>' . $button_html . '</a>';
} else {
	echo '<button ' . $attributes . '>' . $button_html . '</button>';
} ?> </div>