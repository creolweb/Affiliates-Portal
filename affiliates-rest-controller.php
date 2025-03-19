class Affiliates_REST_Controller extends WP_REST_Controller {
    public function __construct() {
        $this->namespace = 'affiliates/v1';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/jobs', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_jobs' ),
        ) );
    }

    public function get_jobs( $request ) {
        $args = array(
            'categories' => 'jobs',
        );

        $jobs = get_posts( $args );

        if ( empty( $jobs ) ) {
            return rest_ensure_response( array() );
        }

        $data = array();

        foreach ( $jobs as $job ) {
            $data[] = array(
                'id' => $job->ID,
                'title' => $job->post_title,
                'content' => $job->post_content,
            );
        }

        return rest_ensure_response( $data );
    }
}