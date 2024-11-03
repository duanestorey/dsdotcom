<?php
/*
    All code copyright (c) 2024 by Duane Storey - All rights reserved
    You may use, distribute and modify this code under the terms of GPL version 3.0 license or later
*/

namespace CR;

class Renderer {
    var $config = false;
    var $templateEngine = false;
    var $pluginManager = false;
    var $menu = false;
    var $theme = false;
    var $startTime = false;
    
    public function __construct( $config, $templateEngine, $pluginManager, $menu, $theme ) {
        $this->config = $config;
        $this->templateEngine = $templateEngine;
        $this->pluginManager = $pluginManager;
        $this->menu = $menu;
        $this->theme = $theme;

        $this->startTime = time();
    }

    public function renderSinglePage( $entry, $template_files ) {
        // set up page specific stuff like the page titel
        $params = $this->_getDefaultRenderParams( $entry->contentType, $entry->slug, [ $entry->contentType . '-' . $entry->className ] );
        $params->content = $entry;
        $params = $this->pluginManager->templateParamFilter( $params );

        $params->isSingle = true;

        $template_name = $this->templateEngine->locateTemplate( $template_files );
        if ( $template_name ) {
            $rendered_html = $this->templateEngine->render( $template_name, $params );
            file_put_contents( CROSSROAD_PUBLIC_DIR . $params->content->slug, $rendered_html );

            echo "......outputting template file " . CROSSROAD_PUBLIC_DIR . $params->content->slug . "\n";
        }    
    }

    public function renderIndexPage( $entries, $contentType, $path, $template_files ) {
        // this is wrong, but fix later
        $content_per_page = 10;
        if ( isset( $this->config[ 'options' ][ 'content_per_page' ] ) ) {
            $content_per_page = $this->config[ 'options' ][ 'content_per_page' ];
        }

        $pagination = new \stdClass;
        $pagination->current_page = 1;
        $pagination->cur_page_link = '';
        $pagination->prev_page_link = '';
        $pagination->next_page_link = '';
        
        if ( count( $entries ) % $content_per_page == 0 ) {
            $pagination->total_pages = intdiv( count( $entries ), $content_per_page );
        } else {    
            $pagination->total_pages = intdiv( count( $entries ), $content_per_page ) + 1;
        }

        $pagination->links = $this->_getPaginationLinks( $path, $pagination->total_pages );

        $template_name = $this->templateEngine->locateTemplate( $template_files );
        if ( $template_name ) {
            while ( $pagination->current_page <= $pagination->total_pages ) {
                if ( $pagination->current_page == 1 ) {
                    $filename = CROSSROAD_PUBLIC_DIR . $path . '/index.html';
                    $pagination->cur_page_link = $path . '/index.html';
                } else {
                    $filename = CROSSROAD_PUBLIC_DIR . $path . '/index-page-' . $pagination->current_page . '.html';
                    $pagination->cur_page_link = $path . '/index-page-' . $pagination->current_page . '.html';
                }

                if ( $pagination->current_page != $pagination->total_pages ) {
                    $pagination->next_page_link = $path . '/index-page-' . ( $pagination->current_page + 1 ). '.html';
                } else {
                    $pagination->next_page_link = '';
                }

                $is_home = ( $pagination->current_page == 1 && $path == '' );
                $body_class_array = ( $is_home ? [ 'home' ] : [] );

                $params = $this->_getDefaultRenderParams( $contentType, $pagination->cur_page_link, $body_class_array );
                
                $params->page->title = $this->config[ 'site' ][ 'title' ];
                $params->page->description = $this->config[ 'site' ][ 'description' ];
                $params->content = array_slice( $entries, ( $pagination->current_page - 1 ) * $content_per_page, $content_per_page );

                $params->isHome = $is_home;
                $params->pagination = $pagination;

                $rendered_html = $this->templateEngine->render( $template_name, $params );
                file_put_contents( $filename, $rendered_html );  

                echo "......outputting template file " . $filename . "\n";

                $pagination->current_page++;

                $pagination->prev_page_link = $pagination->cur_page_link;
            }
        }    
    }

    private function _getPaginationLinks( $path, $totalPages ) {
        $links = array();

        for ( $i = 0; $i < $totalPages; $i++ ) {
            $page = new \stdClass;

            $page->num = $i + 1;
            $page->url = $i == 0 ? $path . '/index.html' : $path . '/index-page-' . ( $i+1 ) . '.html';

            $links[] = $page;
        }

        return $links;
    }    

    private function _getDefaultRenderParams( $contentType, $current_page, $extra_body_classes = [] ) {
        $params = new \stdClass;
        $params->site = new \stdClass;
        $params->site->title = $this->config[ 'site' ][ 'name' ];

        $params->site->lang = 'en';
        if ( isset( $this->config[ 'site' ][ 'lang' ] ) ) {
            $params->site->lang = $this->config[ 'site' ][ 'lang' ];
        }

        $params->site->charset = 'utf-8';
        if ( isset( $this->config[ 'site' ][ 'charset' ] ) ) {
            $params->site->charset = $this->config[ 'site' ][ 'charset' ];
        }

        $params->menu = $this->menu->build( 'main', $current_page );

        $params->page = new \stdClass;
        $params->page->assetUrl = '/assets';
        $params->page->assetHash = $this->theme->getAssetHash();
        $params->page->bodyClassesRaw = array_merge( [ $contentType ], $extra_body_classes );
        $params->page->bodyClasses = implode( ' ', $params->page->bodyClassesRaw );    

        $params->isSingle = false;
        $params->isHome = false;

        $params->renderTime = $this->startTime;

        return $params;
    }    
}