<?php



class ClassPostType{

    public function __construct(){
        add_action( 'get_sidebar', array($this,'post_type'));
    }

    public function post_type()
    {
        $args = array(
            'post_type'=> array( 'post', 'page' ),
            'post_status'=>'publish',
            'posts_per_page'=>-1,
        );

        $post = new WP_Query($args);

        if ( $post->have_posts() ) {


        } else {
            esc_html_e( 'Sorry, no posts matched your criteria.' );
        }


    }

}
$plugin = new ClassPostType;