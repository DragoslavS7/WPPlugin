<?php



class ClassPostType{

    public function __construct(){
        add_action( 'init', array($this,'post_type'));
    }

    public function post_type()
    {
        $post_id = isset($_GET['preview_id']);

        $post = get_post( $post_id );

        var_dump($post->ID);

    }

}
$plugin = new ClassPostType;