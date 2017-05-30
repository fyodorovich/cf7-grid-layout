<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://syllogic.in
 * @since      1.0.0
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/admin
 * @author     Aurovrata V. <vrata@syllogic.in>
 */
class Cf7_Grid_Layout_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
    $screen = get_current_screen();
    if ('wpcf7_contact_form' != $screen->post_type){
      return;
    }
    $plugin_dir = plugin_dir_url( __DIR__ );
    switch( $screen->base ){
      case 'post':
        wp_enqueue_style( "cf7-grid-post-css", $plugin_dir . 'admin/css/cf7-grid-layout-post.css', array(), $this->version, 'all' );
        //dynamic tag
        wp_enqueue_style('cf7sg-dynamic-tag-css', $plugin_dir . 'admin/css/cf7sg-dynamic-tag.css', array(), $this->version, 'all' );
        //benchmark tag
        wp_enqueue_style('cf7sg-benchmark-tag-css', $plugin_dir . 'admin/css/cf7sg-benchmark-tag.css', array(), $this->version, 'all' );
        //codemirror
        wp_enqueue_style( "codemirror-css", $plugin_dir . 'assets/codemirror/codemirror.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-theme-css", $plugin_dir . 'assets/codemirror/theme/paraiso-light.css', array(), $this->version, 'all' );
        wp_enqueue_style( "codemirror-foldgutter-css", $plugin_dir . 'assets/codemirror/addon/fold/foldgutter.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'smart-grid-css', $plugin_dir . 'assets/css.gs/smart-grid.min.css', array(), $this->version, 'all');
        wp_enqueue_style('dashicons');
        wp_enqueue_style('select2-style', $plugin_dir . 'assets/select2/css/select2.min.css', array(), $this->version, 'all' );
        break;
      case 'edit':
        wp_enqueue_style( $this->plugin_name, $plugin_dir . 'admin/css/cf7-grid-layout-admin.css', array(), $this->version, 'all' );

        break;
    }

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($page) {

    //debug_msg($screen, $this->custom_type );
    $plugin_dir = plugin_dir_url( __DIR__ );
    if('toplevel_page_wpcf7' == $page){
      wp_enqueue_script( $this->plugin_name, $plugin_dir . 'admin/js/cf7-grid-layout-admin.js', array( 'jquery' ), $this->version, false );
      return;
    }
    $screen = get_current_screen();
    if ('wpcf7_contact_form' != $screen->post_type){
      return;
    }
    switch( $screen->base ){
      case 'post':
		    //for the future
        $this->setup_cf7_object();
        //enqueue the cf7 scripts.
        wpcf7_admin_enqueue_scripts( 'wpcf7' );
        //force cf7 script
        // wp_enqueue_script( 'wpcf7-admin-js',
      	// 	wpcf7_plugin_url( 'admin/js/scripts.js' ),
      	// 	array( 'jquery', 'jquery-ui-tabs' ),
      	// 	WPCF7_VERSION, true );
        // wp_enqueue_script('wpcf7-admin-tag-js', wpcf7_plugin_url( 'admin/js/tag-generator.js' ),
    		// array( 'jquery', 'thickbox', 'wpcf7-admin' ), WPCF7_VERSION, true);

        wp_enqueue_script( 'cf7-grid-codemirror-js', $plugin_dir . 'admin/js/cf7-grid-codemirror.js', array( 'jquery', 'jquery-ui-tabs' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, $plugin_dir . 'admin/js/cf7-grid-layout-admin.js', array('cf7-grid-codemirror-js', 'jquery-ui-sortable', 'jquery-ui-draggable' ), $this->version, false );
        wp_enqueue_script( 'cf7sg-dynamic-tag-js', $plugin_dir . 'admin/js/cf7sg-dynamic-tag.js', array('jquery','wpcf7-admin-taggenerator' ), $this->version, true );
        wp_enqueue_script( 'cf7-benchmark-tag-js', $plugin_dir . 'admin/js/cf7-benchmark-tag.js', array('jquery','wpcf7-admin-taggenerator' ), $this->version, true );
        wp_localize_script(
          $this->plugin_name,
          'cf7_grid_ajaxData',
          array(
            'url' => admin_url( 'admin-ajax.php' )
          )
        );

        //codemirror script
        wp_enqueue_script( 'codemirror-js',
          $plugin_dir . 'assets/codemirror/codemirror.js',
          array(), $this->version, false
        );
        //fold code
        wp_enqueue_script( 'codemirror-foldcode-js',
          $plugin_dir . 'assets/codemirror/addon/fold/foldcode.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-foldgutter-js',
          $plugin_dir . 'assets/codemirror/addon/fold/foldgutter.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-indent-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/indent-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-xml-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/xml-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-brace-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/brace-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-comment-fold-js',
          $plugin_dir . 'assets/codemirror/addon/fold/comment-fold.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-mixed-js',
          $plugin_dir . 'assets/codemirror/mode/htmlmixed/htmlmixed.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-javascript-js',
          $plugin_dir . 'assets/codemirror/mode/javascript/javascript.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-xml-js',
          $plugin_dir . 'assets/codemirror/mode/xml/xml.js',
          array('codemirror-js'), $this->version, false
        );
        wp_enqueue_script( 'codemirror-css-js',
          $plugin_dir . 'assets/codemirror/mode/css/css.js',
          array('codemirror-js'), $this->version, false
        );
        //overlay for shortcode highligh
        wp_enqueue_script( 'codemirror-overlay-js',
          $plugin_dir . 'assets/codemirror/addon/mode/overlay.js',
          array('codemirror-js'), $this->version, false
        );
        //js beautify
        wp_enqueue_script( 'beautify-js',
          $plugin_dir . 'assets/beautify/beautify.js',
          array('jquery'), $this->version, false
        );
        wp_enqueue_script( 'beautify-html-js',
          $plugin_dir . 'assets/beautify/beautify-html.js',
          array('beautify-js'), $this->version, false
        );
        wp_enqueue_script('jquery-select2', $plugin_dir . 'assets/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );
        break;
      case 'edit':
        //wp_enqueue_script( $this->plugin_name.'-quick-edit', plugin_dir_url( __FILE__ ) . 'js/cf7-grid-layout-quick-edit.js', false, $this->version, true );

        break;
    }
	}
//   public function inspect_scripts() {
//     global $wp_scripts;
//     debug_msg($wp_scripts->queue);
// }

  /**
  * Adds a new sub-menu to replace the 'Add New' CF7 menu
  * Add a new sub-menu to the Contact main menu, as well as remove the current default
  *
  */
  public function add_cf7_sub_menu(){

    $hook = add_submenu_page(
      'wpcf7',
      __( 'Edit Contact Form', 'contact-form-7' ),
      __( 'Add New', 'contact-form-7' ),
      'wpcf7_edit_contact_forms',
      'post-new.php?post_type=wpcf7_contact_form'
    );

    //initial cf7 object when creating new form
    add_action( 'load-' . $hook, array($this, 'setup_cf7_object'));
    //remove_submenu_page( $menu_slug, $submenu_slug );
    remove_submenu_page( 'wpcf7', 'wpcf7-new' );
    remove_meta_box('slugdiv', 'wpcf7_contact_form', 'normal');
  }
  /**
  * Change the submenu order
  * @since 1.0.0
  */
  public function change_cf7_submenu_order( $menu_ord ) {
      global $submenu;
      // Enable the next line to see all menu orders
      if(!isset($submenu['wpcf7']) ){
        return $menu_ord;
      }
      if( is_network_admin() ){
        return $menu_ord;
      }
      $arr = array();
      foreach($submenu['wpcf7'] as $menu){
        switch($menu[2]){
          case 'post-new.php?post_type=wpcf7_contact_form':
            //push to the front
            array_unshift($arr, $menu);
            break;
          default:
            $arr[]=$menu;
            break;
        }
      }
      $submenu['wpcf7'] = $arr;
      return $menu_ord;
  }
  /**
  * Modify the regsitered cf7 post tppe
  * THis function enables public capability and amind UI visibility for the cf7 post type. Hooked late on `register_post_type_args`
  * @since 1.0.0
  * @param    Array     $args   array of post parameters being registered
  * @param    String    $post_type  post type breing resgistered
  */

  public function modify_cf7_post_type_args($args, $post_type){
    if(class_exists('WPCF7_ContactForm') &&  $post_type === WPCF7_ContactForm::post_type  ) {
      //debug_msg($args, 'pre-args');
      $system_dropdowns = get_option('_cf7sg_dynamic_dropdown_system_taxonomy',array());
      $system_taxonomy = array();
      if(!empty($system_dropdowns)){
        foreach($system_dropdowns as $id=>$list){
          $system_taxonomy = array_merge($system_taxonomy, $list);
        }
        if(!empty($args['taxonomies'])){
          $system_taxonomy = array_merge($args['taxonomies'], $system_taxonomy);
        }
        $system_taxonomy = array_unique($system_taxonomy);
        $args['taxonomies'] = $system_taxonomy;
      }
      $args['public'] = false;
      $args['show_ui']= true;
      $args['show_in_menu']= 'wpcf7';
      $args['supports'] = array('title', 'author');
      $args['labels']['add_new_item'] = 'Add New Form';
      $args['labels']['edit_item'] = 'Edit Form';
      $args['labels']['new_item'] = 'New Form';
      $args['labels']['view_item'] = 'View Form';
      $args['labels']['view_items'] = 'View Forms';
      $args['labels']['search_items'] = 'Search Forms';
      $args['labels']['not_found'] = 'No forms found.';
      $args['labels']['not_found_in_trash'] = 'No forms found in Trash.';
      $args['labels']['parent_item_colon'] = '';
      $args['labels']['attributes'] = 'Form Attributes';
      $args['labels']['insert_into_item'] = 'Insert into form';
      $args['labels']['uploaded_to_this_item'] = 'Uploaded to this form';
      $args['labels']['filter_items_list'] = 'Filter forms list';
      $args['labels']['items_list_navigation'] = 'Forms list navigation';
      $args['labels']['items_list'] = 'Forms list';
    }

    return $args;
  }
  /**
   * Register custom taxonomy for dynamic dropdown lists
   * Hooked on 'init'
   * @since 1.0.0
   *
  **/
  public function register_dynamic_dropdown_taxonomy(){
    //register the dynamic dropdown taxonomies.
    $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());
    $created = array();
    foreach($dropdowns as $post_lists){
      foreach($post_lists as $slug=>$taxonomy){
        if(!isset($created[$slug])){
          if(is_array($taxonomy)){
            $this->register_dynamic_dropdown($taxonomy);
            $created[$slug] = $slug;
          }
        }
      }
    }
  }

  /**
  * Function to add the metabox to the cf7 post edit screen
  * This adds the main editor, hooked on 'add_meta_boxes'
  * @since 1.0.0
  */
  public function main_editor_meta_box() {
    //add_meta_box( string $id, string $title, callable $callback, string $screen, string $context, string $priority, array $callback_args)
    if(class_exists('WPCF7_ContactForm') &&  post_type_exists( WPCF7_ContactForm::post_type ) ) {
      add_meta_box( 'meta-box-main-cf7-editor',
        __( 'Edit Contact Form', 'contact-form-7' ),
        array($this , 'main_editor_metabox_display'),
        WPCF7_ContactForm::post_type,
        'normal',
        'high'
      );
    }
  }
  /**
   * Function called on page load when new form is created
   * hooked to 'load-{page-hook}'
   * @since 1.0.0
  **/
  public function setup_cf7_object(){
    WPCF7_ContactForm::get_template( array(
			'locale' => isset( $_GET['locale'] ) ? $_GET['locale'] : null ) );
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function main_editor_metabox_display($post){
    if('auto-draft' !== $post->post_status){
      wpcf7_contact_form($post); //set the post
    }
    $post_id = $post->ID;
    $cf7_form = wpcf7_get_current_contact_form();

  	if ( ! $cf7_form ) {
  		$cf7_form = WPCF7_ContactForm::get_template();
  	}

  	// if(empty($post)){
    //   $post_id = -1;
    // }else{
    //   $post_id = $post->ID;
    // }
  	require_once WPCF7_PLUGIN_DIR . '/admin/includes/editor.php';
  	require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-admin-editor-display.php';
  }

  /**
  * Function to add the metabox to the cf7 post edit screen
  * This adds the main editor, hooked on 'add_meta_boxes'
  * @since 1.0.0
  */
  public function info_meta_box() {
    //add_meta_box( string $id, string $title, callable $callback, string $screen, string $context, string $priority, array $callback_args)
    if(class_exists('WPCF7_ContactForm') &&  post_type_exists( WPCF7_ContactForm::post_type ) ) {
      add_meta_box( 'meta-box-cf7-info',
        __( 'Information', 'contact-form-7' ),
        array($this , 'info_metabox_display'),
        WPCF7_ContactForm::post_type,
        'side',
        'high'
      );
    }
  }
  /**
   * Re-introduces the wordpress 'wpcf7_admin_misc_pub_section' for plugins to add their fields for submission
   * Hooked to 'post_submitbox_misc_actions' which fires after post dat/time parameters are printed
   * @since 1.0.0
   * @param      WP_Post    $post    post object being edited/created.
  **/
  public function cf7_post_submit_action($post){
    if('wpcf7_contact_form' == $post->post_type){
      do_action( 'wpcf7_admin_misc_pub_section', $post->ID );
    }
  }
  /**
  * Callback function to disolay the main editor meta box
  * @since 1.0.0
  */
  public function info_metabox_display($post){
  	require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-info-metabox-display.php';
  }
  /**
   * Display the editor panels (wpcf7 / codemirror / grid)
   *
   * @since 1.0.0
   * @param      string    $p1     .
   * @return     string    $p2     .
  **/
  public function grid_editor_panel($form_post){
    require_once plugin_dir_path( __FILE__ )  . '/partials/cf7-grid-layout-admin-display.php';
  }
  /**
   * Save cf7 post using ajax
   * Hooked to 'save_post_wpcf7_contact_form'
   * @since 1.0.0
  **/
  public function save_post($post_id, $post, $update){
    //make sure this is the edit page and not a quick-edit.
    if( !isset($_POST['_wpcf7nonce']) ){
      return;
    }
    //validate the nonce.
    check_admin_referer( 'wpcf7-save-contact-form_' . $post_id, '_wpcf7nonce');

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
      return;
    }

    switch($post->post_status){
      case 'auto-draft':
      case 'trash':
        return;
      default:
        break;
    }

    //debug_msg($_POST, 'submitted ');
    $args = $_REQUEST;
		$args['id'] = $post_id;

		$args['title'] = isset( $_POST['post_title'] )
			? $_POST['post_title'] : null;

		$args['locale'] = isset( $_POST['wpcf7-locale'] )
			? $_POST['wpcf7-locale'] : null;

		$args['form'] = isset( $_POST['wpcf7-form'] )
			? $_POST['wpcf7-form'] : '';

		$args['mail'] = isset( $_POST['wpcf7-mail'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail'] )
			: array();

		$args['mail_2'] = isset( $_POST['wpcf7-mail-2'] )
			? wpcf7_sanitize_mail( $_POST['wpcf7-mail-2'] )
			: array();

		$args['messages'] = isset( $_POST['wpcf7-messages'] )
			? $_POST['wpcf7-messages'] : array();

		$args['additional_settings'] = isset( $_POST['wpcf7-additional-settings'] )
			? $_POST['wpcf7-additional-settings'] : '';

    //need to unhook this function so as not to loop infinitely
    //debug_msg($args, 'saving cf7 posts');
    remove_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    $contact_form = wpcf7_save_contact_form( $args );
    add_action('save_post_wpcf7_contact_form', array($this, 'save_post'), 10,3);
    //flag as a grid form
    update_post_meta($post_id, 'cf7_grid_form', true);
    //save sub-forms if any
    $sub_forms = json_decode(stripslashes($_POST['cf7sg-embeded-forms']));
    if(empty($sub_forms)) $sub_forms = array();
    update_post_meta($post_id, '_cf7sg_sub_forms', $sub_forms);
    //save form fields which are in tabs or tables.
    $tt_fields = json_decode(stripslashes($_POST['cf7sg-table-fields']));
    if(empty($tt_fields)) $tt_fields = array();
    update_post_meta($post_id, '_cf7sg_grid_table_names', $tt_fields);
    //tabs
    $tt_fields = json_decode(stripslashes($_POST['cf7sg-tabs-fields']));
    if(empty($tt_fields)) $tt_fields = array();
    update_post_meta($post_id, '_cf7sg_grid_tabs_names', $tt_fields);
    //flag tab & tables for more efficient front-end display.
    $has_tabs =  ( 'true' === $_POST['cf7sg-has-tabs']) ? true : false;
    update_post_meta($post_id, '_cf7sg_has_tabs', $has_tabs);
    $has_tables = ( 'true' === $_POST['cf7sg-has-tables']) ? true : false;
    update_post_meta($post_id, '_cf7sg_has_tables', $has_tables);
  }
  /**
   * Ajax function to return the content of a cf7 form
   * Hooked on 'wp_ajax_get_cf7_content'
   * @since 1.0.0
   * @return     String    cf7 form content.
  **/
  public function get_cf7_content(){
    if( !isset($_POST['nonce']) ){
      echo 'error, nonce failed, try to reload the page.';
      wp_die();
    }
    if(!wp_verify_nonce($_POST['nonce'], 'wpcf7-save-contact-form_' . $_POST['id'], '_wpcf7nonce')){
      echo 'error, nonce failed, try to reload the page.';
      wp_die();
    }

    $cf7_key = $_POST['cf7_key'];
    $sub_forms = get_posts(array(
      'post_type' => 'wpcf7_contact_form',
      'post_name__in' => array($cf7_key)
    ));
    if(!empty($sub_forms)){
      if(isset($_POST['update'])){
        $parent = get_post($_POST['id']);
        if( strtotime($parent->post_modified) < strtotime($sub_forms[0]->post_modified) ){
          $form = get_post_meta($sub_forms[0]->ID, '_form', true);;
          echo $form;
        }else{
          echo '';
        }
      }else{
        $form = get_post_meta($sub_forms[0]->ID, '_form', true);;
        echo $form;
      }
      //$cf7_form = wpcf7_contact_form($post[0]->ID);
      //echo $cf7_form->prop( 'form' );
    }else{
      echo 'unable to find form '.$cf7_key;
    }
    wp_die();
  }
  /**
   * Adds a [taxonomy] tag to cf7 forms
   * Hooked on 'wpcf7_admin_init'
   * @since 1.0.0
  **/
  public function cf7_shortcode_tags(){
    if ( class_exists( 'WPCF7_TagGenerator' ) ) {
      $tag_generator = WPCF7_TagGenerator::get_instance();
      $tag_generator->add(
        'dynamic_select', //tag id
        __( 'dynamic-dropdown', 'cf7_2_post' ), //tag button label
        array($this,'dynamic_tag_generator') //callback
      );
      $tag_generator->add(
        'benchmark', //tag id
        __( 'benchmark', 'cf7_2_post' ), //tag button label
        array($this,'benchmark_tag_generator') //callback
      );
    }
  }
  /**
	 * Dynamic select screen displayt.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function dynamic_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/cf7-dynamic-tag-display.php');
	}
  /**
	 * Benchmark input screen displayt.
	 *
	 * This function is called by cf7 plugin, and is registered with a hooked function above
	 *
	 * @since 1.0.0
	 * @param WPCF7_ContactForm $contact_form the cf7 form object
	 * @param array $args arguments for this form.
	 */
	function benchmark_tag_generator( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
		include( plugin_dir_path( __FILE__ ) . '/partials/cf7-benchmark-tag.php');
	}
  /**
   * Print hiddend field on cf7 post submit box
   * Hooked on 'wpcf7_admin_misc_pub_section'
   * @since 1.0.0
   * @param      string    $post_id    cf7 form post id .
  **/
  public function dynamic_select_choices($post_id){
    echo '<input id="cf72post-dynamic-select" type="hidden" name="cf72post_dynamic_select_taxonomies" />';
  }
  /**
   * CF7 Form saved from backend, check if dynamic-select are used
   * Hooked on 'wpcf7_save_contact_form'
   * @since 1.0.0
   * @param  WPCF7_Contact_Form $cf7_form  cf7 form object
  **/
  public function save_factory_metas($cf7_form){
    $cf7_post_id = $cf7_form->id();
    //get the tags used in this form
    if( isset($_POST['cf72post_dynamic_select_taxonomies']) ){
      $created_taxonomies = array();
      $taxonomies = json_decode(str_replace('\"','"',$_POST['cf72post_dynamic_select_taxonomies']), true);
      if(empty($taxonomies)){
        $taxonomies = array();
      }
      foreach($taxonomies as $taxonomy){
        $created_taxonomies[$taxonomy['slug']] = $taxonomy;
      }
      //debug_msg($created_taxonomies);
      $tags = $cf7_form->scan_form_tags(); //get your form tags
      $post_lists = $saved_lists = $system_list = array();

      $dropdowns = get_option('_cf7sg_dynamic_dropdown_taxonomy',array());

      foreach($dropdowns as $id => $lists){
        $saved_lists = array_merge($saved_lists, $lists);
      }
      foreach($tags as $tag){
        if('dynamic_select' == $tag['basetype']){
          if(isset($tag['values'])){
            foreach($tag['values'] as $values){
              if(0 == strpos($values, 'slug:') ){
                $slug = str_replace('slug:', '', $values);
                break;
              }
            }
            //is slug newly created?
            if(isset($created_taxonomies[$slug])){
              //store this taxonomy.
              $post_lists[$slug] = $created_taxonomies[$slug];
            }else if(isset($saved_lists[$slug])){
              //retain previously saved list if we are stil using it.
              $post_lists[$slug] = $saved_lists[$slug];
            }else{ //system taxonomy.
              $system_list[] = $slug;
            }
            //store the taxonomy slug in the cf7 metas.
            //$post_lists[$slug] = null;
          }
        }
      }
      //list of taxonomy to register.
      //unset the old value.
      unset($dropdowns[$cf7_post_id]);
      //unshift new value to top of array.
      $dropdowns = array($cf7_post_id => $post_lists) + $dropdowns ;

      update_option('_cf7sg_dynamic_dropdown_taxonomy', $dropdowns);
      //list of system taxonomy to register
      $system_dropdowns = get_option('_cf7sg_dynamic_dropdown_system_taxonomy',array());
      $system_dropdowns[$cf7_post_id] = $system_list;
      update_option('_cf7sg_dynamic_dropdown_system_taxonomy', $system_dropdowns);

    }
  }

  /**
   * function to regsiter dyanmic dropdown taxonomies
   *
   * @since 1.0.0
   * @param      Object    $taxonomy_object     std object with parameters $taxonomy_object->slug, $taxonomy_object->singular, $taxonomy_object->plural, $taxonomy_object->hierarchical .
  **/
  protected function register_dynamic_dropdown($taxonomy_array){
    $slug = $taxonomy_array['slug'];
    $name = $taxonomy_array['singular'];
    $plural = $taxonomy_array['plural'];
    $is_hierarchical = $taxonomy_array['hierarchical'];

    $labels = array(
  		'name'                       =>  $plural,
  		'singular_name'              =>  $name,
  		'menu_name'                  =>  $plural,
  		'all_items'                  =>  'All '.$plural,
  		'parent_item'                =>  'Parent '.$name,
  		'parent_item_colon'          =>  'Parent '.$name.':',
  		'new_item_name'              =>  'New '.$name.' Name',
  		'add_new_item'               =>  'Add New '.$name,
  		'edit_item'                  =>  'Edit '.$name,
  		'update_item'                =>  'Update '.$name,
  		'view_item'                  =>  'View '.$name,
  		'separate_items_with_commas' =>  'Separate '.$plural.' with commas',
  		'add_or_remove_items'        =>  'Add or remove '.$plural,
  		'choose_from_most_used'      =>  'Choose from the most used',
  		'popular_items'              =>  'Popular '.$plural,
  		'search_items'               =>  'Search '.$plural,
  		'not_found'                  =>  'Not Found',
  		'no_terms'                   =>  'No '.$plural,
  		'items_list'                 =>  $plural.' list',
  		'items_list_navigation'      =>  $plural.' list navigation',
  	);
    //labels can be modified post registration
  	$args = array(
  		'labels'                     => $labels,
  		'hierarchical'               => $is_hierarchical,
  		'public'                     => false,
  		'show_ui'                    => true,
  		'show_admin_column'          => false,
  		'show_in_nav_menus'          => false,
  		'show_tagcloud'              => false,
      'show_in_quick_edit'         => false,
      'description'                => 'Contact Form 7 dynamic dropdown taxonomy list',
  	);

    register_taxonomy( $slug, WPCF7_ContactForm::post_type, $args );
  }
}
