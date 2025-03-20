<?php

function register_custom_post_types() {

    /**
     * Array of labels for the custom post type.
     * 
     * The second parameter in the __() function, 'affiliates-portal', is the text domain for translation.
     * It is used to make the strings translatable and should match the text domain defined in the plugin or theme.
     */
    $labels = array(
        'name' => __( 'Jobs', 'affiliates-portal' ),
        'singular_name' => __( 'Job', 'affiliates-portal' ),
        'menu_icon' => 'dashicons-briefcase',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true, // This enables support for the WordPress REST API
        /**
         * Specifies the features supported by a custom post type.
         * 
         * Valid entries for the 'supports' array include:
         * - 'title'          : Enables the title field for the post type.
         * - 'editor'         : Enables the content editor for the post type.
         * - 'author'         : Enables the author selection for the post type.
         * - 'thumbnail'      : Enables featured image support for the post type.
         * - 'excerpt'        : Enables the excerpt field for the post type.
         * - 'trackbacks'     : Enables trackbacks for the post type.
         * - 'custom-fields'  : Enables custom fields for the post type.
         * - 'comments'       : Enables comments for the post type.
         * - 'revisions'      : Enables revisions for the post type.
         * - 'page-attributes': Enables page attributes like menu order for hierarchical post types.
         * - 'post-formats'   : Enables post formats for the post type.
         */
        'supports' => array( 'title', 'editor', 'author', 'custom-fields' ),
        'has_archive' => true,
    );

    // Register the "Job" CPT
    register_post_type( 'job', $args );

    // Register any additional CPTs following the same pattern above
}
add_action( 'init', 'register_custom_post_types' );