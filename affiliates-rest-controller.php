<?php

class Affiliates_REST_Controller extends WP_REST_Controller {
    public function __construct() {
        $this->namespace = 'affiliates/v1';
    }

    // Register the custom routes for the REST API
    public function register_routes() {

        // This endpoint is for getting all jobs
        register_rest_route( $this->namespace, '/jobs', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_jobs' ),
            'permission_callback' => array( $this, 'get_jobs_permissions_check'),
        ) );

        // This endpoint is for creating a new job
        register_rest_route( $this->namespace, '/jobs', array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'create_job' ),
            'permission_callback' => array( $this, 'create_job_permissions_check'),
        ) );
        
        // This endpoint is for getting a specific job by ID
        register_rest_route( $this->namespace, '/jobs/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_job' ),
            'permission_callback' => array( $this, 'get_job_permissions_check' ),
        ) );

        // This endpoint is for editing a specific job by ID
        register_rest_route( $this->namespace, '/jobs/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => array( $this, 'edit_job' ),
            'permission_callback' => array( $this, 'edit_job_permissions_check' ),
        ) );

        // This endpoint is for deleting a specific job by ID
        register_rest_route( $this->namespace, '/jobs/(?P<id>\d+)', array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array( $this, 'delete_job' ),
            'permission_callback' => array( $this, 'delete_job_permissions_check' ),
        ) );
    }

    // Callback function to get all jobs
    public function get_jobs( $request ) {
        $args = array(
            'post_type' => 'job',
        );

        $jobs = get_posts( $args );

        if ( empty( $jobs ) ) {
            return rest_ensure_response( array() );
        }

        $data = array();

        foreach ( $jobs as $job ) {
            $data[] = array(
                'id'      => $job->ID,
                'title'   => $job->post_title,
                'author'  => get_the_author_meta( 'display_name', $job->post_author ),
                'job_description' => $job->post_content,
                'contact' => get_post_meta( $job->ID, 'contact', true ),
            );
        }

        return rest_ensure_response( $data );
    }

    public function create_job( $request ) {
        // Required fields
        $required_fields = array( 'title', 'job_description', 'contact' );
        $missing_fields = array();

        // Check for missing fields
        foreach ( $required_fields as $field ) {
            if ( empty( $request[$field] ) ) {
                $missing_fields[] = $field;
            }
        }

        // If there are missing fields, return an error
        if ( ! empty( $missing_fields ) ) {
            return new WP_Error(
                'missing_fields',
                __( 'The following fields are missing: ', 'affiliates-portal' ) . implode( ', ', $missing_fields ),
                array( 'status' => 400 )
            );
        }

        // Use current user id if no author is provided
        $author_id = ! empty( $request['author'] ) ? intval( $request['author'] ) : get_current_user_id();

        $job_data = array(
            'post_title'   => sanitize_text_field( $request['title'] ),
            // Use "job_description" field from the request
            'post_content' => sanitize_textarea_field( $request['job_description'] ),
            'post_type'    => 'job',
            'post_status'  => 'publish',
            'post_author'  => $author_id,
        );

        $job_id = wp_insert_post( $job_data );

        if ( is_wp_error( $job_id ) ) {
            return new WP_Error( 'cant-create', __( 'Cannot create job', 'affiliates-portal' ), array( 'status' => 500 ) );
        }

        // Save the contact information as a custom field
        if ( ! empty( $request['contact'] ) ) {
            update_post_meta( $job_id, 'contact', sanitize_text_field( $request['contact'] ) );
        }

        return rest_ensure_response( array( 'id' => $job_id, 'title' => $request['title'] ) );
    }

    public function get_jobs_permissions_check( $request ) {
        return current_user_can( 'read' );
    }

    public function create_job_permissions_check( $request ) {
        return current_user_can( 'edit_posts' );
    }
}