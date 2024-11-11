<?php

namespace CR;

function cr_sort( $a, $b ) {
    if ( $a->publishDate == $b->publishDate ) {
        return 0;
    }

    return ( $b->publishDate < $a->publishDate ) ? -1 : 1;
}

class Content {
    // configuration data
    public $config = null;
    public $contentConfig = null;
    public $contentType = null;

    public $title = '';
    public $originalTitle = '';
    public $publishDate = 0;
    public $modifiedDate = 0;
    public $url = '';
    public $markdownFile = '';
    public $html = '';
    public $originalHtml = '';
    public $featuredImage = false;
    public $featuredImageData = null;
    public $description = '';
    public $slug = '';
    public $taxonomy = [];


    // Calculated fields
    public $taxonomyLinks = [];
    public $className = '';
    public $readingTime = '';
    public $words = '';
    public $relUrl = '';
    public $unique = '';
    public $modifiedHash = '';

    public function __construct( $config, $contentType, $contentConfig ) {
        $this->config = $config;
        $this->contentConfig = $contentConfig;
        $this->contentType = $contentType;

        $this->publishDate = time();
    }

    public function calculate() {
        $this->originalHtml = $this->html;
        $this->originalTitle = $this->title;

        $this->words = str_word_count( strip_tags( $this->html ) );
        $minutes = intdiv( $this->words, 225 );
        if ( $minutes <= 1 ) {
            $this->readingTime = _i18n( 'core.class.entries.reading_time.s' );
        } else {
            $this->readingTime = sprintf( _i18n( 'core.class.entries.reading_time.p' ), $minutes );
        }   

        $this->unique = md5( $this->contentType . '/' . $this->slug ); 
        $this->className = $this->slug;
        $this->modifiedDate = filemtime( $this->markdownFile );
        $this->modifiedHash = md5( filemtime( $this->markdownFile ) . $this->html );

        if ( isset( $this->contentConfig[ 'base' ] ) ) {
            $contentLink =  Utils::fixPath( $this->contentConfig[ 'base' ] ) . '/' . $this->slug . '.html';
        } else {
            $contentLink = '/' . $this->contentType . '/' . $this->slug . '.html';
        }
        
        $this->url = Utils::fixPath( $this->config->get( 'site.url' ) ) . $contentLink;
        $this->relUrl = $contentLink;        
    }

    public function setTitle( $title ) {
        $this->title = $title;
    }

    public function excerpt( $length = 600, $includeEllipsis = true ) {
        $str = '';
        $words = explode( ' ', strip_tags( $this->html ) );

        $len = 0;
        for ( $i = 0; $i < count( $words ); $i++ ) {
            $str = $str . $words[ $i ] . ' ';
            $len += strlen( $words[ $i ] ) + 1;

            if ( $len >= $length ) {
                break;
            }
        }
    
        if ( $includeEllipsis ) {
            $str = $str . '...';
        }

        return rtrim( $str );
    }
}