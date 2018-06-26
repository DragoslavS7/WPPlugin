<?php



class ClassPostType{

    public function __construct(){
        add_action( 'post_row_actions',array($this,'post_type'));
        add_action( 'admin_action_rd_duplicate_post_as_draft', array($this,'rd_duplicate_post_as_draft') );
    }



    function rd_duplicate_post_as_draft(){

           global $wpdb;

           $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

           $post = get_post( $_GET['post'] );

           $current_user = wp_get_current_user();
           $new_post_author = $current_user->ID;

            if (isset( $post ) && $post != null) {

                $args = array(
                    'comment_status' => $post->comment_status,
                    'ping_status'    => $post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $post->post_content,
                    'post_excerpt'   => $post->post_excerpt,
                    'post_name'      => $post->post_name,
                    'post_parent'    => $post->post_parent,
                    'post_password'  => $post->post_password,
                    'post_status'    => 'draft',
                    'post_title'     => $post->post_title,
                    'post_type'      => $post->post_type,
                    'to_ping'        => $post->to_ping,
                    'menu_order'     => $post->menu_order
                );

                $new_post_id = wp_insert_post( $args );


                $taxonomies = get_object_taxonomies($post->post_type);
                foreach ($taxonomies as $taxonomy) {
                    $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                    wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                }

                wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );

                exit;
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