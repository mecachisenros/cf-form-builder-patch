<?php 
/*
--------------------------------------------------------------------------------
Plugin Name: Respect CF Porcessor Order
Description: Overrides the core CF form-builder with a patched version to fix the order of processors not saving (issue since 1.9.0).
Author: Andrei Mondoc
Version: 0.1
--------------------------------------------------------------------------------
*/

class CF_Form_Builder_Patch {

    /**
     * Our script handle.
     *
     * @var string
     */
    const SCRIPT_HANDLE = 'cf-form-builder-patched';

    /**
     * Our script handle.
     *
     * @var string
     */
    const CORE_SCRIPT_HANDLE = 'cf-form-builder';

    /** 
     * Constructor.
     */
    public function __construct() {

        $this->register_scripts();
        add_action( 'caldera_forms_edit_start', [ $this, 'overrride_form_builder_script' ] );

    }

    /**
     * Registers our scripts in order 
     * to later override CF core script.
     */
    public function register_scripts() {

        wp_register_script(
			static::SCRIPT_HANDLE,
			plugins_url( 'build/index.js', __FILE__ ),
			[ 'wp-element', 'wp-components', 'wp-dom-ready', 'wp-api-fetch' ],
			filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
        );

        /**
         * Localize script.
         *
         * Copied from Caldera_Forms_Admin_Assets::maybe_register_all_admin()
         * @see https://github.com/CalderaWP/Caldera-Forms/blob/master/classes/admin/assets.php#L305
         */
        wp_localize_script(
            static::SCRIPT_HANDLE, 
            'CF_FORM_BUILDER', 
            [
                'strings' => [
                    'if'=> esc_html__( 'If', 'caldera-forms'),
                    'and'=> esc_html__( 'And', 'caldera-forms'),
                    'name'=> esc_html__('Name', 'caldera-forms'),
                    'disable'=> esc_html__( 'Disable', 'caldera-forms'),
                    'type'=> esc_html__('Type', 'caldera-forms'),
                    'add-conditional-group'=> esc_html__( 'Add Rule', 'caldera-forms'),
                    'applied-fields'=> esc_html__( 'Applied Fields', 'caldera-forms'),
                    'select-apply-fields'=> esc_html__( 'Select the fields to apply this condition to.', 'caldera-forms'),
                    'remove-condition'=> esc_html__( 'Remove Condition', 'caldera-forms'),
                    'remove-condfirm' => esc_html__('Are you sure you would like to remove this conditional group', 'caldera-forms'),
                    'show'=> esc_html__('Show', 'caldera-forms'),
                    'hide' => esc_html__( 'Hide', 'caldera-forms'),
                    'new-conditional'=> esc_html__( 'New Condition', 'caldera-forms'),
                    'fields' => esc_html__('Fields', 'caldera-forms'),
                    'add-condition' => esc_html__('Add Line', 'caldera-forms')
                ]
            ]
        );

    }

    /**
     * Dequeues the core cf-form-builder script
     * and enqueues our own patched version.
     */
    public function overrride_form_builder_script() {

        if ( wp_script_is( static::CORE_SCRIPT_HANDLE ) ) {
            wp_dequeue_script( static::CORE_SCRIPT_HANDLE );
            wp_enqueue_script( static::SCRIPT_HANDLE );
        }

    }

}

add_action( 'caldera_forms_admin_init', function() {
    new CF_Form_Builder_Patch;
} );