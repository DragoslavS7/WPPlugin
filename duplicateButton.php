<?php
/*
 * Plugin Name:     Duplicate Plugin
 * Description:     This is my first plugin
 * Author:          Dragoslav Predojevic
 * Version:         1.0
 *
*/

require 'include/classPost.php';

class DuplicateButton{

    public function __construct(){
        add_action('plugins_loaded',array($this,'reg_post_duplicate'));
    }

    public function reg_post_duplicate(){

        $this->init();

    }

    function init() {

        return new ClassPostType( );

    }
}

