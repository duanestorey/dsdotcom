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

    public function renderSinglePage( $entry, $templateFiles ) {
        // set up page specific stuff like the page titel
        $params = $this->_getDefaultRenderParams( $entry->relUrl, [ $entry->contentType, $entry->contentType . '-' . $entry->className ] );
        $params->content = $entry;
        $params = $this->pluginManager->templateParamFilter( $params );

        $params->isSingle = true;

        $templateName = $this->templateEngine->locateTemplate( $templateFiles );
        if ( $templateName ) {
            $renderedHtml = $this->templateEngine->render( $templateName, $params );
            // check directory
            $info = pathinfo( CROSSROADS_PUBLIC_DIR . $params->content->relUrl );
            if ( $info ) {
                if ( !file_exists( $info[ 'dirname' ] ) ) {
                    @mkdir( $info[ 'dirname' ], 0755, true );
                }
            }

            file_put_contents( CROSSROADS_PUBLIC_DIR . $params->content->relUrl, $renderedHtml );

            LOG( sprintf( _i18n( 'core.class.renderer.output' ), CROSSROADS_PUBLIC_DIR . $params->content->relUrl ), 4, LOG::DEBUG );
        }    
    }

    public function renderIndexPage( $entries, $contentType, $path, $templateFiles ) {
        $totalPages = 0;

        // this is wrong, but fix later
        $contentPerPage = $this->config->get( 'options.content_per_page', 10 ); 

        $pagination = new \stdClass;
        $pagination->currentPage = 1;
        $pagination->curPageLink = '';
        $pagination->prevPageLink = '';
        $pagination->nextPageLink = '';
        
        if ( count( $entries ) % $contentPerPage == 0 ) {
            $pagination->totalPages = intdiv( count( $entries ), $contentPerPage );
        } else {    
            $pagination->totalPages = intdiv( count( $entries ), $contentPerPage ) + 1;
        }

        $pagination->links = $this->_getPaginationLinks( $path, $pagination->totalPages );

        $templateName = $this->templateEngine->locateTemplate( $templateFiles );
        if ( $templateName ) {
            while ( $pagination->currentPage <= $pagination->totalPages ) {
                if ( $pagination->currentPage == 1 ) {
                    $filename = CROSSROADS_PUBLIC_DIR . $path . '/index.html';
                    $pagination->curPageLink = $path . '/index.html';
                } else {
                    $filename = CROSSROADS_PUBLIC_DIR . $path . '/index-page-' . $pagination->currentPage . '.html';
                    $pagination->curPageLink = $path . '/index-page-' . $pagination->currentPage . '.html';
                }

                if ( $pagination->currentPage != $pagination->totalPages ) {
                    $pagination->nextPageLink = $path . '/index-page-' . ( $pagination->currentPage + 1 ). '.html';
                } else {
                    $pagination->nextPageLink = '';
                }

                $is_home = ( $pagination->currentPage == 1 && $path == '' );
                $body_class_array = ( $is_home ? [ 'home' ] : [ $contentType ] );

                $params = $this->_getDefaultRenderParams( $pagination->curPageLink, $body_class_array );
                
                $params->page->title = $this->config->get( 'site.title' ); 
                $params->page->description = $this->config->get( 'site.description' ); 
                $params->content = array_slice( $entries, ( $pagination->currentPage - 1 ) * $contentPerPage, $contentPerPage );

                $params->isHome = $is_home;
                $params->pagination = $pagination;

                $renderedHtml = $this->templateEngine->render( $templateName, $params );
                file_put_contents( $filename, $renderedHtml );  

                LOG( sprintf( _i18n( 'core.class.renderer.output' ), CROSSROADS_PUBLIC_SLUG . $pagination->curPageLink ), 3, LOG::DEBUG );

                $totalPages++;

                $pagination->currentPage++;

                $pagination->prevPageLink = $pagination->curPageLink;
            }
        }    

        return $totalPages;
    }

    public function render404Page() {
        $templateName = $this->templateEngine->locateTemplate( [ '404' ] );
        if ( $templateName ) {
            $relUrl = CROSSROADS_PUBLIC_SLUG . '/404.html';

            $params = $this->_getDefaultRenderParams( $relUrl, [ '404' ] );
                
            $params->page->title = $this->config->get( 'site.title' ); 
            $params->page->description = $this->config->get( 'site.description' );   

            $renderedHtml = $this->templateEngine->render( $templateName, $params );
            file_put_contents( CROSSROADS_PUBLIC_DIR . '/404.html', $renderedHtml );  

            LOG( sprintf( _i18n( 'core.class.renderer.output' ), $templateName ), 3, LOG::DEBUG );
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

    private function _getDefaultRenderParams( $currentPage, $bodyClasses = [] ) {
        $params = new \stdClass;
        $params->site = new \stdClass;
        $params->site->title = $this->config->get( 'site.name' ); 

        $params->site->lang = $this->config->get( 'site.lang', 'en' );
        $params->site->charset = $this->config->get( 'site.charset', 'utf-8' );

        $params->menu = $this->menu->build( 'main', $currentPage );

        $params->page = new \stdClass;
        $params->page->assetUrl = '/assets';
        $params->page->assetHash = $this->theme->getAssetHash();
        $params->page->bodyClassesRaw = $bodyClasses;
        $params->page->bodyClasses = implode( ' ', $params->page->bodyClassesRaw );    

        $params->isSingle = false;
        $params->isHome = false;

        $params->renderTime = $this->startTime;

        return $params;
    }    
}