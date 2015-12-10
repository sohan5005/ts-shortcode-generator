<?php
/**
* TS Shortcode generator core
*  
* @tsframework
* @ ver 1.0
* 
* Author: Sohan Zaman (Theme Stones)
*/
if( !defined( 'TS_SCG_PATH' ) ) {
    define( 'TS_SCG_PATH', plugin_dir_path( __FILE__ ) );
}

if( !class_exists( 'TS_Shortcode_Generator' ) ) :
   
    class TS_Shortcode_Generator {

        public $name = '';
        public $version = 1.0;
        public $url = '';
        public $template;
        public $title;
        public $icon;

        /**
         * TS_Shortcode_Generator::__construct()
         * Used as constructor
         *
         * @args - array of accepted arguments
         *
         * @return void
         */
        public function __construct( $args ) {
            
            global $ts_shortcode_generator_instances;
            
            if( !is_array( $ts_shortcode_generator_instances ) ) {
                $ts_shortcode_generator_instances = array();
            }
            
            $args = wp_parse_args( $args, array(
                'name' => 'ts_shortcode_generator',
                'title' => __( 'TS Shortcode Generator', 'ts_core' ),
                'template' => null,
                'author' => 'Theme Stones',
                'website' => 'http://themestones.net/',
                'icon' => plugins_url( 'assets/img/icon.png', __FILE__ ),
                'version' => 1.0
            ));
            
            $this->name = $args['name'];
            $this->version = $args['version'];
            $this->url = TS_SCG_PATH;
            $this->template = $args['template'];
            $this->title = $args['title'];
            $this->icon = $args['icon'];
            
            unset( $args['template'] );
            
            $ts_shortcode_generator_instances[] = $args;

            add_filter( 'tiny_mce_version', array( $this, 'ts_change_tmc_version' ) );

            add_action( 'init', array( $this, 'ts_add_generator_buttons' ) );

            add_action( 'wp_ajax_ts_shortcode_generator_' . $this->name , array( $this, 'ts_ajax_tinymce_callback' ) );
            add_action( 'wp_ajax_ts_ajax_module_view_' . $this->name , array( $this, 'ts_ajax_module_view_callback' ) );
            
        }

        /**
         * TS_Shortcode_Generator::ts_add_generator_buttons()
         * We will add the button using the function
         *
         * @return void
         */
        public function ts_add_generator_buttons() {
            
            if( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
                return;
            }
            
            if( get_user_option('rich_editing') == 'true' ) {
                
                add_filter( 'mce_external_plugins', array( &$this, 'ts_add_tmc_plugin' ), 5 );
                add_filter( 'mce_buttons', array( &$this, 'ts_register_button' ), 5 );
            }
        }

        /**
         * TS_Shortcode_Generator::ts_register_button()
         * Push the button into the array
         *
         * @return void
         */
        public function ts_register_button( $buttons ) {
            array_push( $buttons, 'separator', $this->name );
            return $buttons;
        }

        /**
         * TS_Shortcode_Generator::ts_add_tmc_plugin()
         * Include our js file as a plugin of tinyMCE
         *
         * @return void
         */
        public function ts_add_tmc_plugin( $plugin_array ) {
            $plugin_array['ts_sc_generator'] = plugins_url( 'assets/js/core.js', __FILE__ );
            $plugin_array[$this->name] = plugins_url( 'assets/js/plugin-empty.js', __FILE__ );
            return $plugin_array;
        }

        /**
         * TS_Shortcode_Generator::ts_change_tmc_version()
         * We should change the version of tinyMCE after adding our plugin
         *
         * @return void
         */
        public function ts_change_tmc_version( $version ) {
            $version = $version + $this->version;
            return $version;
        }

        /**
         * TS_Shortcode_Generator::ts_ajax_tinymce_callback()
         * The ajax action which is called for initialization
         * This action will fetch the popup canvas markup
         *
         * @return html
         */
        public function ts_ajax_tinymce_callback() {

            if( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) {
                die( __( 'You do not have rights to do this!', 'ts_core' ) );
            }
            
            $template = $this->template;

            require_once( plugin_dir_path( __FILE__ ) . '/views/popup.php' );

            die();

        }

        /**
         * TS_Shortcode_Generator::ts_ajax_module_view_callback()
         * The ajax action called for fetching and parsing template
         * This action will fetch the template array and apply conversion on it
         *
         * @return html
         */
        public function ts_ajax_module_view_callback() {

            if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) {
                die( __( 'You do not have rights to do this!', 'ts_core' ) );
            }

            $template = $this->template;

            $shortcodes = array();

            foreach( $template as $group ) {

                foreach( $group['shortcodes'] as $tag_args ) {

                    $shortcodes[$tag_args['tag']] = $tag_args;

                }

            }

            $requested = $_REQUEST['ts_tag'];

            $this->tsg_parse_field_view_circle_head( $shortcodes[$requested] );

            die();

        }

        /**
         * TS_Shortcode_Generator::tsg_parse_field_view_circle_head()
         * Takes php array and converts it to html markup
         *
         * @return html
         */
        public function tsg_parse_field_view_circle_head( $args ) {

            $add_more_txt = sprintf( __( 'Add more %s', 'ts_core' ), $args['title'] );

            $remove_this_txt = sprintf( __( 'Remove this %s', 'ts_core' ), $args['title'] );

            $root_atts = array();

            $root_classes = array();

            $root_classes[] = 'ts-scg-shortcode-item-root';

            $root_atts[] = sprintf( 'data-shortcode-tag="%s"', esc_attr( $args['tag'] ) );

            if( isset( $args['repeatable'] ) && $args['repeatable'] == true ) {
                $root_atts[] = 'data-repeat="true"';
                $repeatable_footer = sprintf( '<input type="button" class="repeat-add button" value="%s">', $add_more_txt );
                $root_classes[] = 'tsg-repeatable-module';
                $repeatable_header = '<div class="tsg-repeatable-remover"><i class="fa fa-times"></i></div>';
            } else {
                $root_atts[] = 'data-repeat="false"';
                $repeatable_footer = '';
                $repeatable_header = '';
            }

            if( isset( $args['sortable'] ) && $args['sortable'] == true ) {
                $root_atts[] = 'data-sortable="true"';
                $sortable_handle = sprintf( '<div title="%s" class="tsg-sorter-handle"><i class="fa fa-arrows"></i></div><div class="tsg-sorter-collapse"><i class="fa fa-bars"></i></div>', $remove_this_txt );
                $root_classes[] = 'tsg-sortable-module';
            } else {
                $root_atts[] = 'data-sortable="false"';
                $sortable_handle = '';
            }

            if( isset( $args['selftag'] ) && $args['selftag'] == true ) {
                $root_atts[] = 'data-no-ending-tag="true"';
                $root_classes[] = 'tsg-selftag-module';
            } else {
                $root_atts[] = 'data-no-ending-tag="false"';
            }

            if( isset( $args['content'] ) ) {
                if( is_array( $args['content'] ) ) {
                    if( isset( $args['content']['tag'] ) ) {
                        $root_atts[] = 'data-content="shortcode"';
                    } else {
                        $root_atts[] = 'data-content="mixed"';
                    }
                } else {
                    $root_atts[] = sprintf( 'data-content="%s"', esc_attr( $args['content'] ) );
                }
            } else {
                $root_atts[] = 'data-content="false"';
            }

            $root_atts[] = sprintf( 'class="%s"', esc_attr( implode( ' ', $root_classes ) ) );

            if( isset( $args['nomore'] ) && $args['nomore'] == 'true' ) {
                foreach( $args['atts'] as $attr_index => $attr_args ) {
                    if( isset( $attr_args['advanced'] ) && $attr_args['advanced'] == 'true' ) {
                        unset( $args['atts'][ $attr_index ] );
                    }
                }
            }

            ?>
            <div <?php echo implode( ' ', $root_atts ); ?>>
                <div class="tsg-modules-wrapper">
                    <div class="tsg-module-inner">
                        <div class="tsg-shortcode-main-title">
                            <span class="label"><?php echo $args['title']; ?></span>
                            <span class="desc"><?php echo $args['desc']; ?></span>
                            <?php echo $repeatable_header; ?>
                        </div>

                        <?php if( isset( $args['atts'] ) && !isset( $args['noatts'] ) ) { ?>
                        <div class="ts-scg-shortcode-item-atts">

                            <?php foreach( $args['atts'] as $name => $atts_args ) {
                                $atts_args['parent_tag'] = $args['tag'];
                                $this->tsg_get_attr_field_elems( $name, $atts_args );
                            } ?>
                            <div class="ts-scg-shortcode-item-more-atts tsg-more-atts-closed">More <i class="fa fa-angle-double-down"></i><i class="fa fa-angle-double-up"></i></div>
                        </div>
                        <?php }
                        if( isset( $args['content'] ) ) {

                            $cont_title = ( isset( $args['content_title'] ) ? $args['content_title'] : __( 'Contents', 'ts_core' ) );
                            $cont_desc = ( isset( $args['content_desc'] ) ? $args['content_desc'] : __( 'Insert your contents from here', 'ts_core' ) );

                        ?>
                        <div class="ts-scg-shortcode-item-content">

                            <div class="tsg-shortcode-main-title">
                                <span class="label"><?php echo $cont_title; ?></span>
                                <span class="desc"><?php echo $cont_desc; ?></span>
                            </div>

                            <?php
                            if( is_array( $args['content'] ) ) {
                                if( isset( $args['content']['tag'] ) ) {
                                    $this->tsg_parse_field_view_circle_tale( $args['content'] );
                                } else {
                                    foreach( $args['content'] as $mx_content ) {
                                        if( is_array( $mx_content ) ) {
                                            echo '<div class="tsg-mixed-shortcode-content-module" data-type="shortcode">';
                                            $this->tsg_parse_field_view_circle_tale( $mx_content );
                                        } else {
                                            echo sprintf( '<div class="tsg-mixed-shortcode-content-module" data-type="%s">', $mx_content );
                                            ?>
                                            <div class="tsg-shortcode-content-field">
                                            <?php require( dirname(__FILE__) . sprintf( '/views/fields/%s.php', $mx_content ) ); ?>
                                            </div>
                                            <?php
                                        }
                                        echo '</div>';
                                    }
                                }
                            } else {
                            ?>
                            <div class="tsg-shortcode-content-field">
                                <?php require( dirname(__FILE__) . sprintf( '/views/fields/%s.php', $args['content'] ) ); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php }
                    echo $sortable_handle; ?>
                    </div>
                </div>
                <?php echo $repeatable_footer; ?>
            </div>
            <?php
        }

        /**
         * TS_Shortcode_Generator::tsg_parse_field_view_circle_tale()
         * Alias of ::tsg_parse_field_view_circle_head()
         *
         * @return html
         */
        public function tsg_parse_field_view_circle_tale( $args ) {
            $this->tsg_parse_field_view_circle_head( $args );
        }

        /**
         * TS_Shortcode_Generator::tsg_get_attr_field_elems()
         * Used only on attribute fields
         *
         * @return html
         */
        public function tsg_get_attr_field_elems( $name, $args, $sbs = true ) {
            
            $def_root = $args['default'];

            $field_type = $args['type'];

            if( $sbs ) {
                $layout_class = ' normal-sbs-view';
            } else {
                $layout_class = '';
            }

            if( isset( $args['advanced'] ) && $args['advanced'] == true ) {
                $layout_class .= ' ts-shortcode-advanced-atts';
            }

            if( isset( $args['default'] ) ) {
                $data_def = '';
                $data_def .= ( is_array( $args['default'] ) ? implode( ', ', $args['default'] ) : $args['default'] );
                if( $def_root == false && $field_type == 'toggle' ) {
                    $data_def = 'no';
                } elseif( $def_root == true && $field_type == 'toggle' ) {
                    $data_def = 'yes';
                }
            } else {
                $data_def = '';
            }

            ?>
            <div data-attr-default="<?php echo esc_attr( $data_def ); ?>" data-field-type="<?php echo esc_attr( $field_type ); ?>" data-attr-name="<?php echo esc_attr( $name ) ?>" class="ts-scg-attr-item<?php echo $layout_class; ?>">
                <div class="ts-scg-attr-item-label">
                    <span class="label"><?php echo $args['title'] ?></span>
                    <?php if( $args['desc'] ) { ?>
                        <span class="desc"><?php echo $args['desc'] ?></span>
                    <?php } ?>
                </div>
                <div class="ts-scg-attr-item-value">
                    <?php require( dirname(__FILE__) . '/views/fields/' . $field_type . '.php' ); ?>
                </div>
            </div>
            <?php
        }

    }
   
endif;

/**
 * Required function ts_generator_add_dummy_elements()
 * 
 * Adds dummy element to the DOM
 *
 * DO NOT REMOVE OR MODIFY THIS FUNCTION
 * 
 * return void
 */
add_action( 'admin_footer', 'ts_generator_add_dummy_elements', 999 );

if( !function_exists( 'ts_generator_add_dummy_elements' ) ) {
    function ts_generator_add_dummy_elements() {

        echo '<div id="ts-generator-overlay"><div class="tsg-loader ts-animated ts-animate-loader"></div></div><div id="ts-generator-root"></div>';

    }
}

/**
 * Required function ts_generator_scripts_styles()
 * 
 * Enqueue required scripts and styles
 *
 * DO NOT REMOVE OR MODIFY THIS FUNCTION
 * 
 * return void
 */
add_action( 'admin_enqueue_scripts', 'ts_generator_scripts_styles' );

if( !function_exists( 'ts_generator_scripts_styles' ) ) {
    function ts_generator_scripts_styles() {

        global $ts_shortcode_generator_instances;
            
        if( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }

        if( get_user_option('rich_editing') != 'true' ) {
            return;
        }

        wp_enqueue_style( 'ts-generator-styles', plugins_url( 'assets/', __FILE__ ) . 'css/generator.css', '1.0' );
        wp_enqueue_style( 'ts-font-awesome', plugins_url( 'assets/', __FILE__ ) . 'css/font-awesome.min.css', '4.4.0' );
        wp_enqueue_style( 'ts-select2-css', plugins_url( 'assets/', __FILE__ ) . 'css/select2.css', '1.1' );
        wp_enqueue_style( 'ts-bootstrap-colorpicker-css', plugins_url( 'assets/', __FILE__ ) . 'css/bootstrap-colorpicker.min.css', '1.1' );
        wp_register_script( 'ts-kia-metabox', plugins_url( 'assets/', __FILE__ ) . 'js/kia-metabox.js', array( 'jquery' ), '1.0', true );
        wp_register_script( 'ts-bootstrap-colorpicker', plugins_url( 'assets/', __FILE__ ) . 'js/bootstrap-colorpicker.min.js', array( 'jquery' ), '1.0', true );
        wp_register_script( 'ts-select2', plugins_url( 'assets/', __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'ts-generator-scripts', plugins_url( 'assets/', __FILE__ ) . 'js/generator.js', array( 'jquery', 'ts-bootstrap-colorpicker', 'ts-kia-metabox', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'ts-select2' ), '1.0', true );
        wp_enqueue_script( 'ts-generator-localizer', plugins_url( 'assets/', __FILE__ ) . 'js/plugin-empty.js', '', '1.0', false );

        wp_localize_script( 'ts-generator-localizer', 'ts_shortcode_generator_instances', $ts_shortcode_generator_instances );

    }
}

/**
 * Helper function ts_get_fa_icons_array()
 * 
 * Get list of font awesome icon classes
 *
 * Used from files available in this framework.
 * If you wish to modify this function, do not delete the files inside. They are used to print icons in the UI.
 * 
 * return array()
 */
if( !function_exists( 'ts_get_fa_icons_array' ) ) :
    function ts_get_fa_icons_array() {
        $pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s*{\s*content/';
        $subject = file_get_contents( plugin_dir_path( __FILE__ ) . '/assets/css/font-awesome.css' );

        preg_match_all( $pattern, $subject, $matches, PREG_SET_ORDER );

        $icons = array();

        foreach( $matches as $match ) {
            $icons[] = $match[1];
        }
        return $icons;
    }
endif;

/**
 * Helper function return_terms_for_tsg_template()
 * 
 * Get list of all terms of certain taxonomy
 *
 * @param       taxonomy name   default "category"
 * @param       return id?      default true, use false to return slug
 * 
 * return array()
 */
if( !function_exists('return_terms_for_tsg_template') ) :
    function return_terms_for_tsg_template( $tax = 'category', $id = true ) {
        $terms = get_terms( $tax, array( 'hide_empty' => true) );
        $output = array();
        foreach( $terms as $term ) {
            $output[] = array(
                'val' => ( $id ? $term->term_id : $term->slug ),
                'label' => $term->name . ' (' . $term->count . ')',
            );
        }
        return $output;
    }
endif;

/**
 * Helper function return_posts_for_tsg_template()
 * 
 * Get list of all posts
 *
 * @param       post type       default "post"
 * @param       return id?      default true, use false to return post title
 * 
 * return array()
 */
if( !function_exists('return_posts_for_tsg_template') ) :
    function return_posts_for_tsg_template( $type = 'post', $id = true ) {
        $posts = get_posts( array(
            'posts_per_page' => -1,
            'post_type' => $type,
            'post_status' => 'publish',
        ));
        $output = array();
        foreach( $posts as $post ) {
            $output[] = array(
                'val' => ( $id ? $post->ID : $post->post_name ),
                'label' => $post->post_title,
            );
        }
        return $output;
    }
endif;

/**
 * Helper function ts_return_cf7_array_for_scg()
 * 
 * Get available contact form names for Contact Form 7 plugin
 * 
 * return array()
 */
if( !function_exists( 'ts_return_cf7_array_for_scg' ) ) {
    function ts_return_cf7_array_for_scg() {
        $args = array(
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1
        );
        $forms = get_posts( $args );
        $output = array();
        if( $forms ) {
            foreach( $forms as $form ) {
                $output[] = array(
                    'val' => $form->post_title,
                    'label' => $form->post_title,
                );
            }
        }
        return $output;
    }
}

/**
 * Helper function return_wc_attributes_for_tsg_template()
 * 
 * Get product attributes for WooCommerce plugin
 * 
 * return array()
 */
if( !function_exists('return_wc_attributes_for_tsg_template') ) :
    function return_wc_attributes_for_tsg_template() {
        
        global $wpdb;
        
        $prefix = $wpdb->prefix;
        $taxonomies = $wpdb->get_results( 'SELECT * FROM ' . $prefix . 'woocommerce_attribute_taxonomies' );
        
        $output = array();
        
        if( $taxonomies ) {
            foreach( $taxonomies as $taxonomy ) {
                $array = get_object_vars( $taxonomy );
                $label = $array['attribute_label'];
                $slug = $array['attribute_name'];
                $terms = get_terms( 'pa_' . $slug, array( 'hide_empty' => true) );
                $add = array();
                
                $add['label'] = $label == '' ? $slug : $label;
                $add['opts'] = array();
                
                foreach( $terms as $term ) {
                    $add_opt = array(
                        'val' => $term->slug,
                        'label' => $term->name,
                    );
                    $add['opts'][] = $add_opt;
                }
                
                $output[] = $add;
            }
        }
        return $output;
        
    }
endif;

/**
 * Helper function ts_get_css3_intro_animations()
 * 
 * Get classes for animate.css if you want to use it
 * 
 * return array()
 */
if( !function_exists('ts_get_css3_intro_animations') ) :
    function ts_get_css3_intro_animations() {
        
        $output = array();
        
        $anims = array(
            '',
            'bounceIn',
            'bounceInDown',
            'bounceInLeft',
            'bounceInRight',
            'bounceInUp',
            'fadeIn',
            'fadeInDown',
            'fadeInDownBig',
            'fadeInLeft',
            'fadeInLeftBig',
            'fadeInRight',
            'fadeInRightBig',
            'fadeInUp',
            'fadeInUpBig',
            'flipInX',
            'flipInY',
            'rotateIn',
            'rotateInDownLeft',
            'rotateInDownRight',
            'rotateInUpLeft',
            'rotateInUpRight',
            'slideInUp',
            'slideInDown',
            'slideInLeft',
            'slideInRight',
            'zoomIn',
            'zoomInDown',
            'zoomInLeft',
            'zoomInRight',
            'zoomInUp',
            'rollIn',
            'lightSpeedIn',
        );
        
        foreach( $anims as $anim ) {
            $add = array();
            $add['val'] = $anim;
            $add['label'] = $anim;
            $output[] = $add;
        }
        
        return $output;
        
    }
endif;