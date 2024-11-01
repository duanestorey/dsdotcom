<?php

namespace CR;

class Renderer {
    var $config = false;
    var $template_engine = false;
    
    public function __construct( $config, $template_engine ) {
        $this->config = $config;
        $this->template_engine = $template_engine;
    }

    public function render_single_page( $params, $template_files ) {
        // set up page specific stuff like the page titel
        $template_name = $this->template_engine->locate_template( $template_files );
        if ( $template_name ) {
            $rendered_html = $this->template_engine->render( $template_name, $params );
            file_put_contents( CROSSROAD_PUBLIC_DIR . $params->content->slug, $rendered_html );

            echo "......outputting template file " . CROSSROAD_PUBLIC_DIR . $params->content->slug . "\n";
        }    
    }
}