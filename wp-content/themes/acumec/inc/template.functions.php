<?php
/**
 * get theme option
 */
function acumec_get_theme_option(){
    global $opt_theme_options;
    return $opt_theme_options;
}
 
/**
 * get meta option
 */
function acumec_get_meta_option(){
     global $opt_meta_options;
     return $opt_meta_options;
}


/**
 * get header top layout.
 */
function acumec_header_top(){ 
    global $opt_theme_options,$opt_meta_options;
    if( !class_exists('EF4Framework') || ( class_exists('EF4Framework') && empty($opt_theme_options['enable_header_top']) && empty($opt_meta_options['enable_header_top'] ) ) ){
        get_template_part('inc/header/headertop', '');
        return;
    }     
    
    if ( (!empty($opt_theme_options['enable_header_top']) && $opt_theme_options['enable_header_top'] == '1') || (!empty($opt_meta_options['header_layout'] && !empty($opt_meta_options['enable_header_top']) && $opt_meta_options['enable_header_top'] == '1' ) ) ){ 
        get_template_part('inc/header/headertop', 'layout2');
    }else{
       return;
    }
}
/**
 * get header layout.
 */
function acumec_header(){
    global $opt_theme_options, $opt_meta_options;

    if(empty($opt_theme_options['header_layout'])){

        get_template_part('inc/header/header', 'default');
        return;
    }
    if(is_page() &&  !empty($opt_meta_options['header_layout'])){

        $opt_theme_options['header_layout'] = $opt_meta_options['header_layout'];
    } 
    
    /* load custom header template. */
    get_template_part('inc/header/header', $opt_theme_options['header_layout']);   
}

/**
 * get theme logo.
 */
function acumec_header_logo(){
    global $opt_theme_options, $opt_meta_options;
        $has_sticky_logo =  !empty($opt_theme_options['sticky_logo']['url']) ? 'has-sticky-logo' : ''; 
    echo '<div class="main_logo '.esc_attr($has_sticky_logo).'">';
        if ((!empty($opt_theme_options['menu_transparent']) && $opt_theme_options['menu_transparent'] == 1 ) || (is_page() && !empty($opt_meta_options['menu_transparent']) && $opt_meta_options['menu_transparent'] == 1 )) {
            if(!empty($opt_theme_options['transparent_logo']['url'])) {
                echo '<a class="main-logo transparent-logo" href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['transparent_logo']['url']) . '"></a>';  
            }
        }
        else {
            if(!empty($opt_theme_options['main_logo']['url'])) {
                echo '<a class="main-logo" href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['main_logo']['url']) . '"></a>';  
            }
            else {
                echo '<h3 class="site-title"><a href="' . esc_url( home_url( '/' )) . '" rel="home">' . get_bloginfo( "name" ) . '</a></h3>';
                echo '<p class="site-description">' . get_bloginfo( "description" ) . '</p>';
            }
        }
        
    echo '</div>';
    acumec_header_sticky_logo();
}

/**
 * get theme logo.
 */
function acumec_header_sticky_logo(){
    global $opt_theme_options;


    /* default logo. */
    if(empty($opt_theme_options['sticky_logo']['url']))
        return;

    echo '<div class="sticky_logo">';

    if(!empty($opt_theme_options['sticky_logo']['url'])) {
        echo '<a href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['sticky_logo']['url']) . '"></a>';
    }
    else {
        echo '<h3 class="site-title"><a href="' . esc_url( home_url( '/' )) . '" rel="home">' . get_bloginfo( "name" ) . '</a></h3>';
        echo '<p class="site-description">' . get_bloginfo( "description" ) . '</p>';
    }

    echo '</div>';
}

function acumec_header_logo1(){
    global $opt_theme_options, $opt_meta_options;
        $has_sticky_logo =  !empty($opt_theme_options['sticky_logo']['url']) ? 'has-sticky-logo' : ''; 
    echo '<div class="main_logo '.esc_attr($has_sticky_logo).'">';

    if ((!empty($opt_theme_options['menu_transparent']) && $opt_theme_options['menu_transparent'] == 1 ) || (is_page() && !empty($opt_meta_options['menu_transparent']) && $opt_meta_options['menu_transparent'] == 1 )) {
            if(!empty($opt_theme_options['transparent_logo']['url'])) {
                echo '<a class="main-logo transparent-logo" href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['transparent_logo']['url']) . '"></a>';  
            }
        }
        else {
            if(!empty($opt_theme_options['main_logo1']['url'])) {
                echo '<a class="main-logo" href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['main_logo1']['url']) . '"></a>';  
            }
            else {
                echo '<h3 class="site-title"><a href="' . esc_url( home_url( '/' )) . '" rel="home">' . get_bloginfo( "name" ) . '</a></h3>';
                echo '<p class="site-description">' . get_bloginfo( "description" ) . '</p>';
            }
        }

    echo '</div>';
    acumec_header_sticky_logo();
}

function acumec_header_logo2(){
    global $opt_theme_options, $opt_meta_options;
        $has_sticky_logo =  !empty($opt_theme_options['sticky_logo1']['url']) ? 'has-sticky-logo' : ''; 
    echo '<div class="main_logo '.esc_attr($has_sticky_logo).'">';

    if(!empty($opt_theme_options['main_logo2']['url'])) {
            echo '<a class="main-logo" href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['main_logo2']['url']) . '"></a>';  
        }
    else {
        echo '<h3 class="site-title"><a href="' . esc_url( home_url( '/' )) . '" rel="home">' . get_bloginfo( "name" ) . '</a></h3>';
        echo '<p class="site-description">' . get_bloginfo( "description" ) . '</p>';
    }

    echo '</div>';
    acumec_header_sticky_logo1();
}

function acumec_header_sticky_logo1(){
    global $opt_theme_options;


    /* default logo. */
    if(empty($opt_theme_options['sticky_logo1']['url']))
        return;

    echo '<div class="sticky_logo">';

    if(!empty($opt_theme_options['sticky_logo1']['url'])) {
        echo '<a href="' . esc_url(home_url('/')) . '"><img alt="' .  get_bloginfo( "name" ) . '" src="' . esc_url($opt_theme_options['sticky_logo1']['url']) . '"></a>';
    }
    else {
        echo '<h3 class="site-title"><a href="' . esc_url( home_url( '/' )) . '" rel="home">' . get_bloginfo( "name" ) . '</a></h3>';
        echo '<p class="site-description">' . get_bloginfo( "description" ) . '</p>';
    }

    echo '</div>';
}

/**
 * get header layout class
 */
function acumec_header_layout_class($class = ''){
    global $opt_theme_options,$opt_meta_options;
    if (is_page() && !empty($opt_meta_options['enable_one_page']) && $opt_meta_options['enable_one_page'] == '1' && !empty($opt_meta_options['page_one_page']) && $opt_meta_options['page_one_page'] == '1') {
        $class = 'header-layout8';
    }
    else {
        if(empty($opt_theme_options)){
            echo esc_attr($class);
            return;
        }
        if(is_page() && !empty($opt_meta_options['header_layout']))
            $opt_theme_options['header_layout'] = $opt_meta_options['header_layout'];
            
        if(!empty($opt_theme_options['header_layout'])) 
            $class = 'header-'.$opt_theme_options['header_layout'];
    }  
    echo esc_attr($class);
}

function acumec_archive_sidebar(){
    global $opt_theme_options;

    $_sidebar = 'right';

    if(isset($opt_theme_options['archive_layout']))
        $_sidebar = $opt_theme_options['archive_layout'];
        
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_sidebar = 'full';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'left' )
        $_sidebar = 'left';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'right' )
        $_sidebar = 'right';
        
    return 'is-sidebar-' . esc_attr($_sidebar);
}

function acumec_archive_class(){
    global $opt_theme_options;

    $_class = "col-xs-12 col-sm-12 col-md-9 col-lg-9";

    if(isset($opt_theme_options['archive_layout']) && $opt_theme_options['archive_layout'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";
        
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'left' )
        $_class = "col-xs-12 col-sm-12 col-md-9 col-lg-9";
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'right' )
        $_class = "col-xs-12 col-sm-12 col-md-9 col-lg-9";
        
        
    echo esc_attr($_class);
}

/**
 * get header class.
 */
function acumec_header_class($class = ''){
    global $opt_theme_options;

    if(empty($opt_theme_options)){
        echo esc_attr($class);
        return;
    }

    if($opt_theme_options['menu_sticky'])
        $class .= ' sticky-desktop';

    echo esc_attr($class);
}

function acumec_revo_header() {
    global $opt_meta_options;
    if(is_page() && !empty($opt_meta_options['header_revo'])){
        echo do_shortcode('[rev_slider alias="'.$opt_meta_options['header_revo'].'"]'); 
    }
}

/**
 * main navigation.
 */
function acumec_header_navigation(){

    global $opt_meta_options;

    $attr = array(
        'menu_class' => 'nav-menu menu-main-menu',
        'theme_location' => 'primary'
    );

    if(is_page() && !empty($opt_meta_options['header_menu']))
        $attr['menu'] = $opt_meta_options['header_menu'];
    /* enable mega menu. */
    if(class_exists('HeroMenuWalker')){ $attr['walker'] = new HeroMenuWalker(); }

    $locations = get_nav_menu_locations();

    if(empty($locations[ 'primary' ]))
        return;

    /* main nav. */
    wp_nav_menu( $attr );
}

/**
 * get page title layout
 */
function acumec_page_title(){
    global $opt_theme_options, $opt_meta_options;
    $has_image = '';
    
    /* default. */
    $layout = '2';
    $bg_color = $image1 = $align = '';
    /* get theme options */
    if(is_page() && isset($opt_meta_options['page_title_enable']) && $opt_meta_options['page_title_enable']=='0') { 
        return;
    }
    if(isset($opt_theme_options['page_title_layout']))
        $layout = $opt_theme_options['page_title_layout'];

    if(isset($opt_theme_options['page_title_align']))
        $align = $opt_theme_options['page_title_align'];

     /* custom layout from page. */
    if(is_page() && !empty($opt_meta_options['page_title_enable']) && $opt_meta_options['page_title_enable']=='1' && !empty($opt_meta_options['page_title_layout']) && $opt_meta_options['page_title_layout'] > 1 ){
        $layout = $opt_meta_options['page_title_layout'];
        if (!empty($opt_meta_options['page_title_background_color'])) {
            $bg_color ='background-color: '. $opt_meta_options['page_title_background_color']['rgba'].';';
        }
        if(!empty($opt_meta_options['page_title_background_image'])){
            $image1 = "background-image: url(".esc_attr($opt_theme_options['page_title_background_image']['background-image']).");background-repeat:".$opt_theme_options['page_title_background_image']['background-repeat'].";background-size:".$opt_theme_options['page_title_background_image']['background-size'].";background-attachment:".$opt_theme_options['page_title_background_image']['background-attachment'].";background-position:".$opt_theme_options['page_title_background_image']['background-position']."; ";
        }           
    }

    /* custom layout from single */
    if(!is_page() && !empty($opt_theme_options['single_page_title_layout']) && $opt_theme_options['single_page_title_layout'] > 1){
        $layout = $opt_theme_options['single_page_title_layout'];
        if (!empty($opt_theme_options['single_page_title_background_color'])) {
            $bg_color = 'background-color: '.$opt_theme_options['single_page_title_background_color']['rgba'].';';
        }
        if (!empty($opt_theme_options['single_page_title_background_image']) ){
            $image1 = "background-image: url(".esc_attr($opt_theme_options['single_page_title_background_image']['background-image']).");background-repeat:".$opt_theme_options['single_page_title_background_image']['background-repeat'].";background-size:".$opt_theme_options['single_page_title_background_image']['background-size'].";background-attachment:".$opt_theme_options['single_page_title_background_image']['background-attachment'].";background-position:".$opt_theme_options['single_page_title_background_image']['background-position']."; ";
        }
    }
    
    ?>
    <div id="page-title" class="page-title <?php echo 'layout-'.esc_attr($layout);?>" style="<?php echo esc_attr($image1); ?>">
            <div class="bg-overlay" style="<?php echo esc_attr($bg_color); ?>"></div>
            <div class="page-title-content <?php  echo esc_html($has_image); ?>">
                <div class="container">
                    <div class="row">
                    <?php switch ($layout){
                        case '2':?>                        
                                <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="page-title-wrap <?php echo esc_attr($align);?>">
                                        <div class="page-title-text">
                                            <h1><?php acumec_get_page_title(); ?></h1>
                                        </div> 
                                        <div class="breadcrumb-text"><?php acumec_get_bread_crumb(); ?></div>
                                    </div>
                                </div>
                    <?php break;
                        case '3':?>
                                    <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-title-wrap <?php echo esc_attr($align); ?> ">
                                            <div class="breadcrumb-text"><?php acumec_get_bread_crumb(); ?></div>
                                            <div class="page-title-text">
                                                <h1><?php acumec_get_page_title(); ?></h1>
                                            </div> 
                                        </div>
                                    </div>
                    <?php break;
                         case '4':?>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-title-wrap <?php echo esc_attr($align);?> ">
                                            <div class="row">
                                                <div id="page-title-text" class="page-title-text col-xs-12 col-sm-12 col-md-6 col-lg-6"><h1><?php acumec_get_page_title(); ?></h1></div>
                                                <div id="breadcrumb-text" class="breadcrumb-text col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="breadcrumb-wrap">
                                                        <?php acumec_get_bread_crumb(); ?>
                                                    </div>
                                                </div>
                                            </div>         
                                        </div>
                                    </div>               
                    <?php break;
                        case '5':?>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-title-wrap <?php echo esc_attr($align);?> ">
                                            <div class="row">
                                                <div id="breadcrumb-text" class="breadcrumb-text col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="breadcrumb-wrap">
                                                        <?php acumec_get_bread_crumb(); ?>
                                                    </div></div>
                                                <div id="page-title-text" class="page-title-text col-xs-12 col-sm-12 col-md-6 col-lg-6"><h1><?php acumec_get_page_title(); ?></h1></div>
                                            </div>         
                                        </div>
                                    </div>               
                    <?php break;
                        case '6':?>
                                    <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-title-wrap <?php echo esc_attr($align); ?> ">
                                            <div class="page-title-text">
                                                <h1><?php acumec_get_page_title(); ?></h1>
                                            </div> 
                                        </div>
                                    </div>
                     <?php break;
                        case '7':?>
                                    <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="page-title-wrap <?php echo esc_attr($align); ?> ">
                                            <div class="breadcrumb-text"><?php acumec_get_bread_crumb(); ?></div>
                                        </div>
                                    </div>
                   <?php break;
                        default:
                            ?>
                            <div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="page-title-wrap <?php echo esc_attr($align); ?>">
                                        <div class="breadcrumb-text"><?php acumec_get_bread_crumb(); ?></div>
                                        <div class="page-title-text">
                                            <h1><?php acumec_get_page_title(); ?></h1>
                                        </div> 
                                    </div>
                                </div>
                            <?php
                        break;
                    } ?>
                    </div>
                </div>
            </div>

    </div><!-- #page-title -->
    <?php
}

/**
 * page title
 */
function acumec_get_page_title(){

    global $opt_meta_options;
    if(is_home()){
        if(is_front_page()){ 
            esc_html_e('Our Blog', 'acumec');
        } else { 
            if (!empty($opt_meta_options['page_title_text'])) {
                echo esc_html($opt_meta_options['page_title_text']);
            }else{
                echo get_the_title(get_option( 'page_for_posts' ));
            }
        }
    }
    elseif (!is_archive()){
        /* page. */
        if(is_page()) :
            /* custom title. */
                /* custom title. */
            if(!empty($opt_meta_options['page_title_text'])):
                echo esc_html($opt_meta_options['page_title_text']);
            else :
                the_title();
            endif;

        elseif (is_front_page()):
            esc_html_e('Our Blog', 'acumec');
        /* search */
        elseif (is_search()):
            printf( esc_html__( 'Search Results for: %s', 'acumec' ), '<span>' . get_search_query() . '</span>' );
        /* 404 */
        elseif (is_404()):
            esc_html_e( '404 Not Found', 'acumec');
        /* other */
        else :
            the_title();
        endif;
    } else {
        /* category. */
        if ( is_category() || is_tax()) :
            single_cat_title();
        elseif ( is_tag() ) :
            /* tag. */
            single_tag_title();
        /* author. */
        elseif ( is_author() ) :
            printf( esc_html__( 'Author: %s', 'acumec' ), '<span class="vcard">' . get_the_author() . '</span>' );
        /* date */
        elseif ( is_day() ) :
            printf( esc_html__( 'Day: %s', 'acumec' ), '<span>' . get_the_date() . '</span>' );
        elseif ( is_month() ) :
            printf( esc_html__( 'Month: %s', 'acumec' ), '<span>' . get_the_date() . '</span>' );
        elseif ( is_year() ) :
            printf( esc_html__( 'Year: %s', 'acumec' ), '<span>' . get_the_date() . '</span>' );
        /* post format */
        elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
            esc_html_e( 'Asides', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
            esc_html_e( 'Galleries', 'acumec');
        elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
            esc_html_e( 'Images', 'acumec');
        elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
            esc_html_e( 'Videos', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
            esc_html_e( 'Quotes', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
            esc_html_e( 'Links', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
            esc_html_e( 'Statuses', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
            esc_html_e( 'Audios', 'acumec' );
        elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
            esc_html_e( 'Chats', 'acumec' );
        /* woocommerce */
        elseif (function_exists('is_woocommerce') && is_woocommerce()):
            woocommerce_page_title();
        else :
            /* other */
            the_title();
        endif;
    }
}

/**
 * Breadcrumb NavXT
 *
 * @since 1.0.0
 */
function acumec_get_bread_crumb() {

    if(!function_exists('bcn_display')) return;

    bcn_display();
}

function acumec_my_search_form( $form ) {
    $form = '<form method="get" action="'. esc_url( home_url( '/'  ) ).'" class="searchform search-form">
            <div class="form-group">
                <input type="text" value="' . get_search_query() . '" name="s" class="form-control" placeholder="'.esc_html__("Search",'acumec').'" id="modal-search-input">
            </div>
            <button type="submit" class="theme_button"><i class="fa fa-search"></i></button>
             ';
         $form .='</form>';
    return $form;
}
add_filter( 'get_search_form', 'acumec_my_search_form' );



/**
 * Display an optional post detail.
 */

function acumec_post_detail(){
    global $opt_theme_options;
    $single_year  = get_the_time('Y'); 
    $single_month = get_the_time('F'); 
    $single_day   = get_the_time('d');
    ?>
    <?php   if((isset($opt_theme_options['single_date']) && $opt_theme_options['single_date']) || (isset($opt_theme_options['single_categories']) && $opt_theme_options['single_categories']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
            <div class="entry-meta">
                <ul class="single_detail">   
                    <?php if(!isset($opt_theme_options['single_date']) || (isset($opt_theme_options['single_date']) && $opt_theme_options['single_date'])): ?>
                        <li class="blog-date">
                            <a href="<?php echo esc_attr(get_day_link( $single_year,  get_the_time('m'),  get_the_time('d'))); ?>"><span><?php echo esc_attr(get_the_date('d F, Y')); ?></span></a>
                        </li>
                    <?php endif; ?>   
                    <?php if(has_category() && (!isset($opt_theme_options['single_categories']) || (isset($opt_theme_options['single_categories']) && $opt_theme_options['single_categories']))): ?>
                        <li class="detail-terms"><?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                    <?php endif; ?>
                    <?php if(!isset($opt_theme_options['single_comment']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
                        <?php  
                        $comments_number = get_comments_number();
                        if ( '1' === $comments_number ) {?>
                            <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _x( '1 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                       <?php }else{?>
                     <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                        </a></li>
                        <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php
    endif;
}

function acumec_post_detail_before(){
    global $opt_theme_options;
    $single_year  = get_the_time('Y'); 
    $single_month = get_the_time('F'); 
    $single_day   = get_the_time('d');
    ?>
    <?php   if((isset($opt_theme_options['single_date']) && $opt_theme_options['single_date']) ): ?>
            <div class="entry-meta-before">
                <div class="entry-meta-wrap">
                    <ul class="single_detail">   
                        <?php if(!isset($opt_theme_options['single_date']) || (isset($opt_theme_options['single_date']) && $opt_theme_options['single_date'])): ?>
                            <li class="blog-date">
                                <a href="<?php echo esc_attr(get_day_link( $single_year,  get_the_time('m'),  get_the_time('d'))); ?>"><span><?php echo esc_attr(get_the_date('d F, Y')); ?></span></a>
                            </li>
                        <?php endif; ?>   
                        <?php if(isset($opt_theme_options['single_author']) && ($opt_theme_options['single_author'] == 1)): ?>
                            <li class="author-detail">
                                <span><?php echo esc_html__('Posted in ','acumec'); ?> <?php echo esc_attr(get_the_author()); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if(isset($opt_theme_options['single_author']) && ($opt_theme_options['single_author'] == 1)): ?>
                        <div class="author-image">
                            <?php echo get_avatar(get_the_author_meta('ID'), 46); ?>
                        </div>
                    <?php endif; ?>
                </div>           
            </div>
        <?php
    endif;
}
function acumec_post_detail_after(){
    global $opt_theme_options;
    ?>
    <?php   if( (isset($opt_theme_options['single_categories']) && $opt_theme_options['single_categories']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
            <div class="entry-meta">
                <ul class="single_detail">    
                    <li class="detail-title"><?php echo esc_html__('in','acumec') ?></li>
                    <?php if(has_category() && (!isset($opt_theme_options['single_categories']) || (isset($opt_theme_options['single_categories']) && $opt_theme_options['single_categories']))): ?>
                        
                        <li class="detail-terms"><?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                    <?php endif; ?>
                    <?php if(!isset($opt_theme_options['single_comment']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
                        <?php  
                        $comments_number = get_comments_number();
                        if ( '1' === $comments_number ) {?>
                            <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _x( '1 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                       <?php }else{?>
                     <li class="detail-comment"><a href="<?php the_permalink(); ?>"><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                        </a></li>
                        <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php
    endif;
}
function acumec_post_related($category) {   
    if($category){  
        $term_ids = wp_list_pluck($category,'term_id');
        $args = array(
          'post_type' => 'post',
          'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator'=> 'IN'  
                 )),
          'posts_per_page' => 4,
          'orderby' => 'date',
          'order' => 'DESC',
          'post__not_in'=>array( get_the_ID())
       );
    }else{  
        $tags = wp_get_post_tags(get_the_ID());
        if ($tags) {
            $first_tag = $tags[0]->term_id;
            $args=array(
            'tag__in' => array($first_tag),
            'post__not_in' => array(get_the_ID()),
            'posts_per_page'=>4,
            'caller_get_posts'=>1
            );
        }
    }
    $wp_query = new WP_Query($args);
    ?>
    <h5 class="related-title"><?php echo esc_html__('RELATED ARTICLES','acumec'); ?></h5>
    <?php if ($wp_query->have_posts()): ?>
        <div class="post-related clearfix">
            <?php while ($wp_query->have_posts()): $wp_query->the_post(); ?>
                <div class="post-related-item">
                    <div class="related-item-wrap clearfix">
                        <div class=" related-thumbnail">
                            <?php 
                                $class = $thumbnail = '';
                                if(has_post_thumbnail()):
                                    $class = ' has-thumbnail';
                                    $thumbnail = get_the_post_thumbnail(get_the_ID(),'thumbnail');
                                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
                                    $image_url = esc_url($image[0]);
                                endif;
                            ?>
                            <div class="item-media">
                                <div class="cms-grid-media<?php echo esc_attr($class);?>"> <a  href="<?php the_permalink();?>"><?php echo wp_kses_post($thumbnail);?></a></div>
                            </div>
                        </div> 
                        <div class="item-title">
                            <?php acumec_post_detail_related(); ?>
                            <h6>
                                <a class="port-link" href="<?php the_permalink();?>" title="<?php the_title()?>"><?php the_title();?></a>
                            </h6>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata (); ?>
        </div>
    <?php endif;
}
function acumec_post_detail_related(){
    global $opt_theme_options;
    $single_year  = get_the_time('Y'); 
    $single_month = get_the_time('F'); 
    $single_day   = get_the_time('d');
    ?>
    <?php   if((isset($opt_theme_options['single_date']) && $opt_theme_options['single_date']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
            <div class="entry-meta-related">
                <ul class="single_detail">   
                    <?php if(!isset($opt_theme_options['single_date']) || (isset($opt_theme_options['single_date']) && $opt_theme_options['single_date'])): ?>
                        <li class="blog-date">
                            <a href="<?php echo esc_attr(get_day_link( $single_year,  get_the_time('m'),  get_the_time('d'))); ?>"><span><i class="fa fa-clock-o"></i><?php echo esc_attr(get_the_date('F d, Y')); ?></span></a>
                        </li>
                    <?php endif; ?>   
                    <?php if(!isset($opt_theme_options['single_comment']) || (isset($opt_theme_options['single_comment']) && $opt_theme_options['single_comment'])): ?>
                        <?php  
                        $comments_number = get_comments_number();
                        if ( '1' === $comments_number ) {?>
                            <li class="detail-comment"><a href="<?php the_permalink(); ?>"><i class="fa fa-comments-o"></i><?php printf( _x( '1', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                       <?php }else{?>
                        <li class="detail-comment"><a href="<?php the_permalink(); ?>"><i class="fa fa-comments-o"></i><?php printf( _nx( '%1$s', '%1$s', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                        </a></li>
                        <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php
    endif;
}

function acumec_post_tag(){
    if(has_tag() && (!isset($opt_theme_options['single_tag']) || (isset($opt_theme_options['single_tag']) && $opt_theme_options['single_tag']))): ?>
        <div class="single-tags"><span class="lbl-tags"><i class="fa fa-tag"></i></span><?php the_tags('', ', ' ); ?></div>
    <?php endif; 
}

/**
 * Display an optional post video.
 */
function acumec_post_video() {

    global $opt_meta_options, $wp_embed;

    /* no video. */
    if(empty($opt_meta_options['opt-video-type'])) {
        acumec_post_thumbnail();
        return;
    }

    if($opt_meta_options['opt-video-type'] == 'local' && !empty($opt_meta_options['otp-video-local']['id'])){

        $video = wp_get_attachment_metadata($opt_meta_options['otp-video-local']['id']);

        echo do_shortcode('[video width="'.esc_attr($opt_meta_options['otp-video-local']['width']).'" height="'.esc_attr($opt_meta_options['otp-video-local']['height']).'" '.$video['fileformat'].'="'.esc_url($opt_meta_options['otp-video-local']['url']).'" poster="'.esc_url($opt_meta_options['otp-video-thumb']['url']).'"][/video]');

    } elseif($opt_meta_options['opt-video-type'] == 'youtube' && !empty($opt_meta_options['opt-video-youtube'])) {

        echo do_shortcode($wp_embed->run_shortcode('[embed]'.esc_url($opt_meta_options['opt-video-youtube']).'[/embed]'));

    } elseif($opt_meta_options['opt-video-type'] == 'vimeo' && !empty($opt_meta_options['opt-video-vimeo'])) {

        echo do_shortcode($wp_embed->run_shortcode('[embed]'.esc_url($opt_meta_options['opt-video-vimeo']).'[/embed]'));

    }
}

/**
 * Display an optional post audio.
 */
function acumec_post_audio() {
    global $opt_meta_options;
     $style =''; 
    if(has_post_thumbnail()){
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
        $image_url = esc_url($image[0]);
        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
    }
    /* no audio. */
    if(empty($opt_meta_options['otp-audio']['id'])) {
        acumec_post_thumbnail();
        return;
    }
    $audio = wp_get_attachment_metadata($opt_meta_options['otp-audio']['id']); 
    ?>
     <div class="entry-wrap" <?php echo ''.$style;?>>
        <div class="entry-inside">
            <?php  
                echo do_shortcode('[audio '.$audio['fileformat'].'="'.esc_url($opt_meta_options['otp-audio']['url']).'"][/audio]');    
            ?>
        </div> 
    </div>
    <?php
}


/**
 * Display an optional post quote.
 */
function acumec_post_quote() {
    global $opt_meta_options;
 
    $style =''; 
    if(has_post_thumbnail()){
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
        $image_url = esc_url($image[0]);
        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
    }

    if(empty($opt_meta_options['opt-quote-content'])){
        acumec_post_thumbnail();
        return;
    }

    $opt_meta_options['opt-quote-title'] = !empty($opt_meta_options['opt-quote-title']) ? esc_html($opt_meta_options['opt-quote-title']) : '' ;
    $quote_sub_title = !empty($opt_meta_options['opt-quote-sub-title']) ? esc_html($opt_meta_options['opt-quote-sub-title']) : '' ;
?>
<div class="post-quote">
    <div class="entry-wrap" <?php echo ''.$style;?>>
        <div class="entry-inside">
            <div class="entry-header">
                <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
            </div>
            <?php  
                echo '<blockquote class ="quote-meta">'.'<p class = "quote-content">'.esc_html($opt_meta_options['opt-quote-content']).'</p><p class="quote-title">'.esc_html($opt_meta_options['opt-quote-title']).'</p><p class="quote-subtitle">'.wp_kses_post($quote_sub_title).'</p></blockquote>'; 
            ?>
        </div>
    </div>
</div>
    
<?php  
}

/**
 * Ajax post like.
 *
 * @since 1.0.0
 */
function acumec_post_like_callback(){
    global $smof_data;

    $post_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

    $likes = null;

    if($post_id && !isset($_COOKIE['cms_post_like_'. $post_id])){

        /* get old like. */
        $likes = get_post_meta($post_id , '_cms_post_likes', true);

        /* check old like. */
        $likes = $likes ? $likes : 0 ;

        $likes++;

        /* update */
        update_post_meta($post_id, '_cms_post_likes' , $likes);

        /* set cookie. */
        setcookie('cms_post_like_'. $post_id, $post_id, time() * 20, '/');
    }
    $text = '';
    if($likes){
        if($likes > 1) $text = esc_html__(' Likes','acumec'); else $text = esc_html__(' Like','acumec');
    }

    echo esc_attr($likes.$text);
    exit();
}

add_action('wp_ajax_cms_post_like', 'acumec_post_like_callback');
add_action('wp_ajax_nopriv_cms_post_like', 'acumec_post_like_callback');


/*/ convert dates to readable format /*/
if (!function_exists('acumec_relative_time')) {
    function acumec_relative_time($a) {
        //get current timestampt
        $b = strtotime("now");
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if (is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if ($d < 3)
                return esc_html__('right now','acumec');
            //if less then minute
            if ($d < $minute)
                return floor($d) . esc_html__(' seconds ago','acumec');
            //if less then 2 minutes
            if ($d < $minute * 2)
                return esc_html__('1 minute ago','acumec');
            //if less then hour
            if ($d < $hour)
                return floor($d / $minute) . esc_html__(' minutes ago','acumec');
            //if less then 2 hours
            if ($d < $hour * 2)
                return esc_html__('1 hour ago','acumec');
            //if less then day
            if ($d < $day)
                return floor($d / $hour) . esc_html__(' hours ago','acumec');
            //if more then day, but less then 2 days
            if ($d > $day && $d < $day * 2)
                return esc_html__('yesterday','acumec');
            //if less then year
            if ($d < $day * 365)
                return floor($d / $day) . esc_html__(' days ago','acumec');
            //else return more than a year
            return esc_html__('over a year ago','acumec');
        }
    }
}
/**
 * Check post has thumbnail
 *
 * @since 1.0.0
 */
function acumec_get_image_crop($size) { 
    if( has_post_thumbnail() && !post_password_required() && !is_attachment() &&  wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size)){
        $class = ' has-thumbnail';
        if (function_exists('wpb_getImageBySize')){
            $img_id = get_post_thumbnail_id();
            $img = wpb_getImageBySize( array(
                'attach_id'  => $img_id,
                'thumb_size' => $size,
                'class'      => '',
            )); 

            $thumbnail = $img['thumbnail'];
        } else {
            $thumbnail = get_the_post_thumbnail(get_the_ID(),$size);
        }
    } 
}

/**
 * crop image
 *
 * @since 1.0.0
 */
/**
 * Return the thumbnail be cropped
 */

function acumec_get_image_croped($img_id,$size){ 
    if (function_exists('wpb_getImageBySize')){
        if(!empty($img_id)){
            $img = wpb_getImageBySize( array(
                'attach_id'  => $img_id,
                'thumb_size' => $size,
                
            ));
            $thumbnail = $img['thumbnail'];
        }else{
            return '';
        }
    } else {
        if(!empty($img_id)){
            $large_img = wp_get_attachment_image_src($img_id, 'full'); 
            $image_large_src = $large_img[0]; 
            $thumbnail = '<img src="'.esc_url($image_large_src).'" />';
        }else{
            return '';
        }
    }
    return $thumbnail;
}


function acumec_archive_link(){
   preg_match('/\<a(.*)\>(.*)\<\/a\>/', get_the_content(), $link);
    if(!empty($link[0])){

       echo wp_kses($link[0],true);
       
    } else {     
        return ;
    }
}
function acumec_post_link() {
    global $opt_meta_options;
 
    $style ='';
    if(has_post_thumbnail()){
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
        $image_url = esc_url($image[0]);
        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
    }
?>
    <div class="entry-wrap post-link" <?php echo ''.$style;?>>
        <div class="entry-inside">
        <?php acumec_archive_link(); ?>
        </div>
    
    </div>
<?php  
}

function acumec_post_link_archive() {
    global $opt_meta_options;
 
    $style ='';
    if(has_post_thumbnail()){
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
        $image_url = esc_url($image[0]);
        $style = 'style="background-image:url('.$image_url.'); background-size: cover;background-position: center;"';
    }
?>
    <div class="entry-wrap" <?php echo ''.$style;?>>
        <div class="entry-inside">
            <header class="entry-header">     
                <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                <?php acumec_archive_detail1(); ?>
            </header><!-- .entry-header -->
            <div class="icon-link">
                <span class="fa fa-link"></span>
            </div>
            <div class="archive-link">
                <?php acumec_archive_link(); ?>
            </div>  
        </div> 
    </div>
<?php  
}

/**
 * Display an optional post gallery.
 */
function acumec_post_gallery($size){
    global $opt_theme_options, $opt_meta_options;

    /* no gallery. */
    if(empty($opt_meta_options['opt-gallery'])) { 
        acumec_post_thumbnail();
        return;
    }
    if(is_single()) $img_size = 'large';
    else $img_size = $size;
    $array_id = explode(",", $opt_meta_options['opt-gallery']);
    if (empty( $array_id)) {
        echo "true";
    }
    else{
    ?>
    <div class="post_gallery_wrap">
        <div id="carousel-post-gallery" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php $i = 0; ?>
                <?php foreach ($array_id as $image_id): ?>
                    <?php
                    $attachment_image = wp_get_attachment_image_src($image_id, $img_size, false);
                    if($attachment_image[0] != ''):?>
                        <div class="item <?php if( $i == 0 ){ echo 'active'; } ?>">
                            <img style="width:100%;" data-src="holder.js" src="<?php echo esc_url($attachment_image[0]);?>"/>
                        </div>
                    <?php $i++; endif; ?>
                <?php endforeach; ?>
            </div>
            <a class="left carousel-control" href="#carousel-post-gallery" role="button" data-slide="prev">
                <span class="fa fa-angle-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-post-gallery" role="button" data-slide="next">
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </div>
    <?php
    }
}
/**
 * Return the thumbnail be cropped
 */
function acumec_getImageCroped($img_id, $size){ 
    if (function_exists('wpb_getImageBySize')){
        $img = wpb_getImageBySize( array(
            'attach_id'  => $img_id,
            'thumb_size' => $size,
            'class'      => '',
        ));
        $thumbnail = $img['thumbnail'];
    } else {
        $thumbnail =  wp_get_attachment_image_src( $img_id, $size );
    }
        return $thumbnail;

}

function acumec_post_thumbnail($img_size='') {
     
    global $opt_theme_options;
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }
     
    $img_size = !empty($img_size) ? $img_size : 'large';
     the_post_thumbnail($img_size);
   
}

/**
 * Display an optional post status.
 */
function acumec_post_status() {
    global $opt_meta_options;

    if(empty($opt_meta_options['opt-status'])){
        return;
    }
    if(!empty($opt_meta_options['opt-status']['thumbnail']) ){
        echo '<div class="media media-status inline-block">';
        echo '<img src="'.esc_url($opt_meta_options['opt-status']['thumbnail']).'" class="round">';
        echo '</div>';
    }   
}

function acumec_post_archive_before(){
    global $opt_theme_options;
    $archive_year  = get_the_time('Y'); 
    $archive_month = get_the_time('F'); 
    $archive_day   = get_the_time('d');
    ?>
    <?php   if((isset($opt_theme_options['archive_date']) && $opt_theme_options['archive_date']) ): ?>
            <div class="entry-meta-before">
                <div class="entry-meta-wrap">
                    <ul class="post-archive">   
                        <?php if(!isset($opt_theme_options['archive_date']) || (isset($opt_theme_options['archive_date']) && $opt_theme_options['archive_date'])): ?>
                            <li class="blog-date">
                                <a href="<?php echo esc_attr(get_day_link( $archive_year,  get_the_time('m'),  get_the_time('d'))); ?>"><span><?php echo esc_attr(get_the_date('d F, Y')); ?></span></a>
                            </li>
                        <?php endif; ?>   
                        <?php if(isset($opt_theme_options['archive_author']) && ($opt_theme_options['archive_author'] == 1)): ?>
                            <li class="author-archive">
                                <span><?php echo esc_html__('Posted in ','acumec'); ?> <?php echo esc_attr(get_the_author()); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if(isset($opt_theme_options['archive_author']) && ($opt_theme_options['archive_author'] == 1)): ?>
                        <div class="author-image">
                            <?php echo get_avatar(get_the_author_meta('ID'), 46); ?>
                        </div>
                    <?php endif; ?>
                </div>           
            </div>
        <?php
    endif;
}
function acumec_post_archive_after(){
    global $opt_theme_options;
    ?>
    <?php   if( (isset($opt_theme_options['archive_categories']) && $opt_theme_options['archive_categories']) || (isset($opt_theme_options['archive_comment']) && $opt_theme_options['archive_comment'])): ?>
            <div class="entry-meta">
                <ul class="archive_archive">    
                    <li class="detail-title"><?php echo esc_html__('in','acumec') ?></li>
                    <?php if(has_category() && (!isset($opt_theme_options['archive_categories']) || (isset($opt_theme_options['archive_categories']) && $opt_theme_options['archive_categories']))): ?>

                        <li class="archive-terms"><?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                    <?php endif; ?>
                    <?php if(!isset($opt_theme_options['archive_comment']) || (isset($opt_theme_options['archive_comment']) && $opt_theme_options['archive_comment'])): ?>
                        <?php  
                        $comments_number = get_comments_number();
                        if ( '1' === $comments_number ) {?>
                            <li class="archive-comment"><a href="<?php the_permalink(); ?>"><?php printf( _x( '1 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                       <?php }else{?>
                     <li class="archive-comment"><a href="<?php the_permalink(); ?>"><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                        </a></li>
                        <?php } ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php
    endif;
}


/**
 * Display social share in single footer.
 */
function acumec_post_sharing(){
    global $opt_theme_options;
     if(isset($opt_theme_options['single_social_share']) && ($opt_theme_options['single_social_share'] == 1)): 
            ?>
            <div class="entry-footer1">
                <div class="entry-share">
                    <ul class="social-share list-unstyled">
                        <?php if(isset($opt_theme_options['share-facebook']) && $opt_theme_options['share-facebook']==1): ?>
                            <li><a class="share-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i class="fa fa-facebook"></i> <?php echo esc_html__('Facebook','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-twitter']) && $opt_theme_options['share-twitter']==1): ?>
                            <li><a class="share-twitter" target="_blank" href="https://twitter.com/home?status=<?php esc_html_e('Check out this article', 'acumec');?>:%20<?php echo strip_tags(get_the_title());?>%20-%20<?php the_permalink();?>"><i class="fa fa-twitter"></i><?php echo esc_html__('Twitter','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-googleplus']) && $opt_theme_options['share-googleplus']==1): ?>
                            <li><a class="share-google" target="_blank" href="https://plus.google.com/share?url=<?php the_permalink();?>"><i class="fa fa-google-plus"></i><?php echo esc_html__('Google Plus','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-linkedin']) && $opt_theme_options['share-linkedin']==1): ?>
                            <li><a class="share-linkedin" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink();?>"><i class="fa fa-linkedin"></i><?php echo esc_html__('Linkedin','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-pinterest']) && $opt_theme_options['share-pinterest']==1): ?>
                            <li><a class="share-pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php the_permalink();?>"><i class="fa fa-pinterest"></i><?php echo esc_html__('Pinterest','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-tumblr']) && $opt_theme_options['share-tumblr']==1): ?>
                            <li><a class="share-tumblr" target="_blank" href="http://www.tumblr.com/share/link?url=<?php the_permalink();?>&amp;name=<?php echo strip_tags(get_the_title());?>&amp;description=<?php echo strip_tags(get_the_excerpt());?>"><i class="fa fa-tumblr"></i><?php echo esc_html__('Tumblr','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-vk']) && $opt_theme_options['share-vk']==1): ?>
                            <li><a class="share-vk" target="_blank" href="https://vk.com/share.php?url=<?php the_permalink();?>"><i class="fa fa-vk"></i><?php echo esc_html__('VKontakte','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-xing']) && $opt_theme_options['share-xing']==1): ?>
                            <li><a class="share-xing" target="_blank" href="https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=<?php the_permalink();?>"><i class="fa fa-xing"></i><?php echo esc_html__('Xing','acumec'); ?></a></li>
                        <?php endif;?>
                        <?php if(isset($opt_theme_options['share-reddit']) && $opt_theme_options['share-reddit']==1): ?>
                            <li><a class="share-reddit" target="_blank" href="http://www.reddit.com/submit?url=<?php the_permalink();?>&amp;title=<?php echo strip_tags(get_the_title());?>"><i class="fa fa-reddit"></i><?php echo esc_html__('Reddit','acumec'); ?></a></li>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
<?php  
    endif;        
}
function acumec_portfolio_sharing(){
    global $opt_meta_options;
    if(isset($opt_meta_options['share_enable']) && $opt_meta_options['share_enable'] == 1): ?>
        <div class="portfolio-share">
            <ul class="social-share list-unstyled">
                <li class="sharing-title"><?php echo esc_html__('SHARE: ','acumec'); ?></li>
                <?php if(isset($opt_meta_options['share-facebook']) && $opt_meta_options['share-facebook']==1): ?>
                    <li class="share-social"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i class="fa fa-facebook"></i></a></li>
                <?php endif; ?>
                <?php if(isset($opt_meta_options['share-twitter']) && $opt_meta_options['share-twitter']==1): ?>
                    <li class="share-social"><a target="_blank" href="https://twitter.com/home?status=<?php esc_html_e('Check out this article', 'acumec');?>:%20<?php echo strip_tags(get_the_title());?>%20-%20<?php the_permalink();?>"><i class="fa fa-twitter"></i></a></li>
                <?php endif; ?>
                <?php if(isset($opt_meta_options['share-googleplus']) && $opt_meta_options['share-googleplus']==1): ?>
                    <li class="share-social"><a target="_blank" href="https://plus.google.com/share?url=<?php the_permalink();?>"><i class="fa fa-google-plus"></i></a></li>
                <?php endif; ?>
                <?php if(isset($opt_meta_options['share-vk']) && $opt_meta_options['share-vk']==1): ?>
                    <li class="share-social"><a target="_blank" href="https://vk.com/share.php?url=<?php the_permalink();?>"><i class="fa fa-vk"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php 
    endif;         
}
function acumec_product_sharing(){
    ?>
    <div class="product-sharing">
        <a class="twitter" target="_blank" href="https://twitter.com/home?status=<?php esc_html_e('Check out this article', 'acumec');?>:%20<?php echo strip_tags(get_the_title());?>%20-%20<?php the_permalink();?>"><i aria-hidden="true" class="fa fa-twitter"></i></a>
        <a class="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink();?>"><i aria-hidden="true" class="fa fa-facebook"></i></a>
        <a class="google" target="_blank" href="https://plus.google.com/share?url=<?php the_permalink();?>"><i aria-hidden="true" class="fa fa-google-plus"></i></a>
        <a class="linkedin" title="<?php esc_html_e('Share this article to Linkedin','acumec'); ?>" target="_blank" href="https://linkedin.com/shareArticle?url=<?php the_permalink();?>"><i class="fa fa-linkedin"></i></a>
    </div>
    <?php
}

function acumec_post_author(){
    global $opt_theme_options;
    
    $desc = get_the_author_meta('description');
    $socials = get_user_meta( get_the_author_meta('ID'), 'ef4u_extend_social', true );
    ?>
     <?php if(isset($opt_theme_options['single_author']) && ($opt_theme_options['single_author'] == 1)): ?>
        <?php if (!empty($desc)): ?>
            <div class="author-meta">
                <div class=" display_table_md">
                    <div class="col-avatar display_table_cell_md">
                        <div class="item-media">
                            <?php echo get_avatar(get_the_author_meta('ID'), 170); ?>

                        </div>
                    </div>
                    <div class=" display_table_cell_md">
                        <div class="item-content">
                            <div class="item-header">
                                <h4><?php echo esc_attr(get_the_author()); ?></h4>
                                <?php if(!empty($socials)){
                                    echo '<ul class="author-social social-icons">';
                                        foreach($socials as $social){
                                                echo '<li><a href="'.esc_url($social['url']).'" class="social-icon rounded-icon '.esc_attr($social['icon']).'"></a></li>';                                     
                                        }
                                    echo '</ul>';
                                    }
                                ?>
                            </div>
                            
                            <?php if(!empty($desc)): ?>
                                <p class="desc"><?php echo esc_attr(get_the_author_meta('description')); ?></p>
                            <?php
                                endif;
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif ?>
    <?php  endif;
}
function acumec_post_sidebar(){
    global $opt_theme_options;

    $_sidebar = 'right';

    if(isset($opt_theme_options['single_layout'])) {
        $_sidebar = $opt_theme_options['single_layout'];
    }

    if( is_singular('project') && isset($opt_theme_options['single_layout_project'])) {
        $_sidebar = $opt_theme_options['single_layout_project'];
    }

    if( is_singular('team') && isset($opt_theme_options['single_layout_team'])) {
        $_sidebar = $opt_theme_options['single_layout_team'];
    }

    if( is_singular('case_studies') && isset($opt_theme_options['single_layout_case_studies'])) {
        $_sidebar = $opt_theme_options['single_layout_case_studies'];
    }

    if( is_singular('service') && isset($opt_theme_options['single_layout_service'])) {
        $_sidebar = $opt_theme_options['single_layout_service'];
    }
        
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_sidebar = 'full';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'left' )
        $_sidebar = 'left';
    if(isset($_GET['layout']) && trim($_GET['layout']) == 'right' )
        $_sidebar = 'right';
    
    return 'is-sidebar-' . esc_attr($_sidebar);
}

function acumec_post_class(){
    global $opt_theme_options;

    $_class = "col-xs-12 col-sm-12 col-md-9 col-lg-9";

    if( is_singular('post') && isset($opt_theme_options['single_layout']) && $opt_theme_options['single_layout'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";

    if( is_singular('project') && isset($opt_theme_options['single_layout_project']) && $opt_theme_options['single_layout_project'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";

    if( is_singular('team') && isset($opt_theme_options['single_layout_team']) && $opt_theme_options['single_layout_team'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";

    if( is_singular('case_studies') && isset($opt_theme_options['single_layout_case_studies']) && $opt_theme_options['single_layout_case_studies'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";

    if( is_singular('service') && isset($opt_theme_options['single_layout_service']) && $opt_theme_options['single_layout_service'] == 'full')
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";

    if(isset($_GET['layout']) && trim($_GET['layout']) == 'full' )
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-offset-1 col-lg-10";
    echo esc_attr($_class);
}

/**
 * Display an optional archive detail.
 */
function acumec_archive_author(){
    global $opt_theme_options;
    if(!isset($opt_theme_options['archive_author']) || (isset($opt_theme_options['archive_author']) && $opt_theme_options['archive_author'] == 1)): ?>
            <div class="detail-author">
                <div class="author-thumbnail">
                    <?php echo get_avatar(get_the_author_meta('ID'), 30); ?>
                </div>
                <div class="author-wrap">
                    <?php echo esc_html__('Posted in','acumec'); ?> <?php the_author_posts_link(); ?>
                </div>
            </div>
    <?php endif; 
}
function acumec_archive_readmore() {
    global $opt_theme_options;
    if(!isset($opt_theme_options['archive_readmore']) || (isset($opt_theme_options['archive_readmore']) && $opt_theme_options['archive_readmore'])): ?>
        <footer class="entry-footer">
            <a class="btn btn-theme-primary" href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'acumec') ?><i class="fa fa-angle-double-right"></i></a>
        </footer><!-- .entry-footer -->
    <?php  endif;
}

/* archive detail */
function acumec_archive_detail(){
    global $opt_theme_options;
    $archive_year  = get_the_time('Y'); 
    $archive_month = get_the_time('F'); 
    $archive_day   = get_the_time('d'); 
    ?>
    <?php   if(isset($opt_theme_options['archive_meta']) && $opt_theme_options['archive_meta'] == '1'): ?> 
                <div class="entry-meta">
                    <ul class="archive_detail">
                        <li class="detail-title"><?php echo esc_html__('in','acumec'); ?></li>  
                        <?php if(!isset($opt_theme_options['archive_date']) || (isset($opt_theme_options['archive_date']) && $opt_theme_options['archive_date'])): ?>
                            <li class="blog-date">
                                <span class="archive-day"><?php echo esc_attr(get_the_date('d')); ?></span><span><?php echo esc_attr(get_the_date('F')); ?></span><span><?php echo esc_attr(get_the_date('Y')); ?></span>
                            </li>
                        <?php endif; ?>  
                        <?php if(has_category() && (!isset($opt_theme_options['archive_categories']) || (isset($opt_theme_options['archive_categories']) && $opt_theme_options['archive_categories']))): ?>
                            <li class="detail-terms"> <?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                        <?php endif; ?>
                        <?php if(!isset($opt_theme_options['archive_comment']) || (isset($opt_theme_options['archive_comment']) && $opt_theme_options['archive_comment'])): ?>
                            <?php  
                            $comments_number = get_comments_number();
                            if ( '1' === $comments_number ) {?>
                                <li class="detail-comment"><a href="<?php the_permalink(); ?>"><span class="fa fa-comments"></span><?php printf( _x( '1 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                           <?php }else{?>
                                 <li class="detail-comment"><a href="<?php the_permalink(); ?>"><span class="fa fa-comments"></span><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                            </a></li>
                            <?php } ?>
                        <?php endif; ?>
                        <?php if(has_tag() && (!isset($opt_theme_options['archive_tag']) || (isset($opt_theme_options['archive_tag']) && $opt_theme_options['archive_tag']))): ?>
                            <li class="detail-tags"><span class="fa fa-tag"></span><?php the_tags('', ', ' ); ?></li>
                        <?php endif; ?>
                    </ul>
                 </div><!-- .entry-meta -->
    <?php endif;
}

function acumec_archive_detail1(){
    global $opt_theme_options;
    $archive_year  = get_the_time('Y'); 
    $archive_month = get_the_time('F'); 
    $archive_day   = get_the_time('d'); 
    ?>
    <?php   if(isset($opt_theme_options['archive_meta']) && $opt_theme_options['archive_meta'] == '1'): ?>
                <div class="entry-meta">
                    <ul class="archive_detail">
                        <li class="detail-title"><?php echo esc_html__('in','acumec'); ?></li>   
                        <?php if(!isset($opt_theme_options['archive_date']) || (isset($opt_theme_options['archive_date']) && $opt_theme_options['archive_date'])): ?>
                            <li class="detail-date">
                                <span><?php echo esc_attr(get_the_date('d F, Y')); ?></span>
                            </li>
                        <?php endif; ?>  
                        <?php if(has_category() && (!isset($opt_theme_options['archive_categories']) || (isset($opt_theme_options['archive_categories']) && $opt_theme_options['archive_categories']))): ?>
                            <li class="detail-terms"> <?php printf(('<span> %1$s</span>'),get_the_category_list( ', ' ));  ?></li>
                        <?php endif; ?>
                        <?php if(!isset($opt_theme_options['archive_comment']) || (isset($opt_theme_options['archive_comment']) && $opt_theme_options['archive_comment'] == 1)): ?>
                            <?php  
                                $comments_number = get_comments_number();
                                if ( '1' === $comments_number ) {?>
                                    <li class="detail-comment"><a href="<?php the_permalink(); ?>"><span class="fa fa-comments"></span><?php printf( _x( '1 comment', 'comments title', 'acumec' ), get_the_title() ); ?></a></li>  
                               <?php }else{?>
                                     <li class="detail-comment"><a href="<?php the_permalink(); ?>"><span class="fa fa-comments"></span><?php printf( _nx( '%1$s comment ', '%1$s comments', $comments_number, 'comments title', 'acumec' ), number_format_i18n( $comments_number ), get_the_title());?>
                                </a></li>
                        <?php }endif; ?>

                        <?php if(has_tag() && isset($opt_theme_options['archive_tag']) && $opt_theme_options['archive_tag'] == 1): ?>
                            <li class="detail-tags"><span class="fa fa-tag"></span><?php the_tags('', ', ' ); ?></li>

                        <?php  endif; ?>
                    </ul>
                 </div><!-- .entry-meta -->
    <?php endif;
}

add_filter( 'body_class', 'acumec_body_extra_class' );
function acumec_body_extra_class( $classes ) {
    global $opt_theme_options,$opt_meta_options;
    
    if( !empty($opt_theme_options['general_layout']) && $opt_theme_options['general_layout'] == '1' ){
        $classes[] = 'boxed-layout';
    }  
    if(is_page() && isset($opt_meta_options['opt_general_layout']) && $opt_meta_options['opt_general_layout'] == '1')
        $classes[] = 'boxed-layout';
    if (is_page() && !empty($opt_meta_options['primary_color_style'])) {
        $classes[] = $opt_meta_options['primary_color_style'];
    }
    return $classes;
     
}
function acumec_general_class(){
    global $opt_theme_options,$opt_meta_options;
    $classes = '';
    if( !empty($opt_theme_options['general_layout']) && $opt_theme_options['general_layout'] == '1' ){
        $classes = 'cs-boxed';
    }  
    if(is_page() && isset($opt_meta_options['opt_general_layout']) && $opt_meta_options['opt_general_layout'] == '1')
        $classes = 'cs-boxed';
    
    echo esc_attr($classes);
}  

/**
 * Show main sidebar
 **/
function acumec_main_sidebar(){
    if(class_exists('Woocommerce')){
        if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product() ) {
            return;
        }
    }
        
    if ( is_active_sidebar( 'sidebar-1' ) ){
        echo '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">';
            echo '<div  id="widget-area" class="widget-area" role="complementary">';
                dynamic_sidebar( 'sidebar-1' ); 
            echo '</div>';
        echo '</div>';
    }
} 

function acumec_footer_align(){
    global $opt_theme_options;
    if (isset($opt_theme_options['footer_bottom_align'])) {
        echo esc_attr($opt_theme_options['footer_bottom_align']);
    }
    else
        return;
}

/* client logo footer */
function acumec_client_logo_footer() {
    global $opt_theme_options;
    global $opt_meta_options;

    if(empty($opt_theme_options['enable_client_footer']) || (!empty($opt_theme_options['enable_client_footer']) && $opt_theme_options['enable_client_footer'] == '0') ) {
        return;
    }
    if ( !is_page() || (is_page() && !empty($opt_meta_options['enable_client_footer']) && $opt_meta_options['enable_client_footer'] == '1') ){?>
        <div id="client-footer"  class="client-logo-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <?php if(is_active_sidebar( 'sidebar-client-logo' ) ){   ?>
                            <div class="client-footer cms-carousel clearfix owl-carousel ">
                            <?php dynamic_sidebar( 'sidebar-client-logo' );?>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
<?php }
}

/**
 * footer layout
 */
function acumec_footer_top(){ 
    global $opt_theme_options;
    global $opt_meta_options;
    /* footer-top */
    if(empty($opt_theme_options['footer-top-column']))
        return;

    $_class = "";
    $width = 'container';
    if( (isset($opt_theme_options['enable_footer_top']) && $opt_theme_options['enable_footer_top']== '1') ): ?>
        <?php if (!is_page() || (is_page() && isset($opt_meta_options['enable_footer_top']) && $opt_meta_options['enable_footer_top']== '1') ): ?>
            <?php if (!empty($opt_theme_options['enable_full_footer_top']) && $opt_theme_options['enable_full_footer_top'] == '1'){
                $width = 'container-fullwidth';
                } ?>
                <div id="footer-top" class="footer-top">
                    <div class="bg-overlay"></div>
                    <div class="<?php echo esc_attr($width); ?>">
                        <div class="row">          
                    <?php 
                    switch ($opt_theme_options['footer-top-column']){
                        case '1':
                            $_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-wrap';
                            break;
                        case '2':
                            $_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 footer-wrap';
                            break;
                        case '3':
                            $_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12 footer-wrap';
                            break;
                        case '4':
                            $_class = 'col-lg-3 col-md-6 col-sm-12 col-xs-12 footer-wrap';
                            break;
                        case '5':
                            $_class = 'col-md-12 col-sm-12 col-xs-12 footer-wrap';
                            break;
                    }
                    if ($opt_theme_options['footer-top-column'] == '5') {
                        for($i = 1 ; $i <= $opt_theme_options['footer-top-column'] ; $i++){
                            if ($i == 1 || $i == 5) {
                                $_class .= ' ';
                                if ( is_active_sidebar( 'sidebar-footer-top-' . $i ) ){
                                    echo '<div class="lg-half ' . esc_html($_class) . '">';
                                        dynamic_sidebar( 'sidebar-footer-top-' . $i );
                                    echo "</div>";
                                }
                            }else {?>
                                <?php if ( is_active_sidebar( 'sidebar-footer-top-' . $i ) ){
                                    echo '<div class="col-half ' . esc_html($_class) . '">';
                                        dynamic_sidebar( 'sidebar-footer-top-' . $i );
                                    echo "</div>";
                                } ?>
                            <?php
                            }
                            $_class = 'col-sm-12 col-xs-12 footer-wrap';
                        }
                    }
                    else {
                       for($i = 1 ; $i <= $opt_theme_options['footer-top-column'] ; $i++){
                            if ( is_active_sidebar( 'sidebar-footer-top-' . $i ) ){
                                echo '<div class="' . esc_html($_class) . '">';
                                    dynamic_sidebar( 'sidebar-footer-top-' . $i );
                                echo "</div>";
                            }
                        } 
                    }  
                    ?>
                            </div>
                        </div>
                    </div><!-- #footer-top -->
            <?php endif; ?>  
    <?php  endif; 
}

function acumec_footer_top1(){ 
    global $opt_theme_options;
    global $opt_meta_options;
    /* footer-top */
    if(empty($opt_theme_options['footer-top-column-layout2']))
        return;

    $_class = "";
    $width = 'container';
    if( (isset($opt_theme_options['enable_footer_top']) && $opt_theme_options['enable_footer_top']== '1') ): ?>
        <?php if (!is_page() || (is_page() && isset($opt_meta_options['enable_footer_top']) && $opt_meta_options['enable_footer_top']== '1') ): ?>
            <?php if (!empty($opt_theme_options['enable_full_footer_top']) && $opt_theme_options['enable_full_footer_top'] == '1'){
                $width = 'container-fullwidth';
                } ?>
                <div id="footer-top" class="footer-top">
                    <div class="bg-overlay"></div>
                    <div class="<?php echo esc_attr($width); ?>">
                        <div class="row">          
                    <?php 
                    switch ($opt_theme_options['footer-top-column-layout2']){
                        case '1':
                            $_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 footer-wrap';
                            break;
                        case '2':
                            $_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12 footer-wrap';
                            break;
                        case '3':
                            $_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12 footer-wrap';
                            break;
                        case '4':
                            $_class = 'col-lg-3 col-md-6 col-sm-12 col-xs-12 footer-wrap';
                            break;
                    }
                   for($i = 1 ; $i <= $opt_theme_options['footer-top-column-layout2'] ; $i++){
                        if ( is_active_sidebar( 'sidebar-footer-top-layout2-' . $i ) ){
                            echo '<div class="' . esc_html($_class) . '">';
                                dynamic_sidebar( 'sidebar-footer-top-layout2-' . $i );
                            echo "</div>";
                        }
                    }  
                    ?>
                            </div>
                        </div>
                    </div><!-- #footer-top -->
            <?php endif; ?>  
    <?php  endif; 
}

function acumec_footer_back_to_top(){
    global $opt_theme_options;

    $_back_to_top = true;

    if(isset($opt_theme_options['general_back_to_top']))
        $_back_to_top = $opt_theme_options['general_back_to_top'];

    if($_back_to_top)
        echo '<div class="ef3-back-to-top"><i class="fa fa-angle-up"></i></div>';
}

/**
 * Change number product to show
 */
add_action('after_setup_theme','acumec_update_woo_number_item_in_page');
function acumec_update_woo_number_item_in_page(){
    if(class_exists('EF4Framework')){
        if(class_exists( 'Woocommerce' )){
            add_filter( 'loop_shop_per_page', 'acumec_woocommerce_number_columms', 20 );
        }
    }
}
/*
* Woocomerce shop columms number
*/
function acumec_woocommerce_number_columms(){
    global $opt_theme_options;
    $number_product = ( !empty($opt_theme_options['shop_products']) ) ? $opt_theme_options['shop_products'] : 8; 
    return $number_product;
}
/*
*Change title in single page for posts
*/
add_action('wp_head','acumec_maybe_fix_opt_meta_options',1);
function acumec_maybe_fix_opt_meta_options()
{
    global $opt_meta_options;
    if(!is_array($opt_meta_options))
        $opt_meta_options = [];
    if(function_exists('wc_get_page_id') && is_archive() && is_post_type_archive('product') && is_numeric($id = wc_get_page_id('shop')))
        $real_page = get_post($id);
    else
        $real_page =  get_queried_object();
    if($real_page instanceof WP_Post)
    {
        $id = $real_page->ID;
        if($id == get_the_ID())
            return;
        $post_metas = get_post_meta($id);
        $opt_meta_options = [];
        $prefix_option = 'ef3-';
        foreach ($post_metas  as $key => $value) {
            if(strpos($key,$prefix_option) === 0)
            {
                $opt_meta_options[substr($key,strlen($prefix_option))] = maybe_unserialize( $value[0] );
            }
        }
    }
}