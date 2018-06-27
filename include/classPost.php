<?php



class ClassPostType{

    public function __construct(){
        add_action( 'post_row_actions',array($this,'post_type'));
        add_action( 'admin_action_rd_duplicate_post_as_draft', array($this,'rd_duplicate_post_as_draft') );
    }



    function rd_duplicate_post_as_draft(){

           $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

           $post = get_posts( $_GET['post'] );

           $current_user = wp_get_current_user();

           $new_post_author = $current_user->ID;

            if (isset( $post ) && $post != null) {

                foreach ($post as $posts){
                    $args = array(
                        'comment_status' => $posts->comment_status,
                        'ping_status'    => $posts->ping_status,
                        'post_author'    => $new_post_author,
                        'post_content'   => $posts->post_content,
                        'post_excerpt'   => $posts->post_excerpt,
                        'post_name'      => $posts->post_name,
                        'post_parent'    => $posts->post_parent,
                        'post_password'  => $posts->post_password,
                        'rewrite'        => array( 'slug' => 'release' ),
                        'post_status'    => array('draft'),
                        'post_title'     => $posts->post_title,
                        'post_type'      => $posts->post_type,
                        'to_ping'        => $posts->to_ping,
                        'menu_order'     => $posts->menu_order
                    );

                    $new_post_id = wp_insert_post( $args );

                    $taxonomies = get_object_taxonomies($posts->post_type);

                    $post_terms = wp_get_object_terms($post_id, $taxonomies, array('fields' => 'slugs'));

                    wp_set_object_terms($new_post_id, $post_terms, $taxonomies, array('fields' => 'slugs'));

                    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
                    die;
                }

            } else {
                wp_die('Post creation failed, could not find original post: ' . $post_id);
            }

    }




    function post_type( $actions, $post ) {

        $posttypes = get_post_types(array('public' => true), 'names', 'and');

        foreach ($posttypes as $post_type)
        {
            $posttype[] = $post_type;

        }

        if (in_array($posttypes['post'], $posttype))
        {
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';

        }

        return $actions;

    }

}


$plugin = new ClassPostType;