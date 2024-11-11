<?php

namespace CR;

class DB {
    var $sql;
    protected $config;

    public function __construct( $config ) {
        $this->config = $config;
        $this->sql = new MYSQL( $config );
    }

    public function rebuild() {
        $this->sql->rebuild();
    }

    public function addContent( $content ) {
        $queryString = sprintf( 
            'INSERT INTO "content" (type, hash, rel_url, slug, html, title, description, featured, created_at, modified_at, modified_hash) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
            $this->sql->escapeWithTicks( $content->contentType ),
            $this->sql->escapeWithTicks( $content->unique ),
            $this->sql->escapeWithTicks( $content->relUrl ),
            $this->sql->escapeWithTicks( $content->slug ),
            $this->sql->escapeWithTicks( $content->markdownHtml ),
            $this->sql->escapeWithTicks( $content->title ),
            $this->sql->escapeWithTicks( $content->description ),
            $this->sql->escapeWithTicks( $content->featuredImage ),
            $this->sql->escapeWithTicks( date( 'Y-m-d H:i:s', $content->publishDate ) ),
            $this->sql->escapeWithTicks( date( 'Y-m-d H:i:s', $content->modifiedDate ) ),
            $this->sql->escapeWithTicks( $content->modifiedHash )
        );

        LOG( sprintf( "Importing [%s]", $content->slug ), 1, LOG::INFO );

        $this->sql->query( $queryString );
        $lastRow = $this->sql->getLastRowID();

        foreach( $content->taxonomy as $tax ) {
            $queryString = sprintf( 
                'INSERT INTO "taxonomy" (term, content_id) VALUES (%s, %d)',
                $this->sql->escapeWithTicks( $tax ),
                $this->sql->escape( $lastRow )
            );

            LOG( sprintf( "Importing tax/term [%s]", $tax ), 2, LOG::INFO );

            $this->sql->query( $queryString );   
        }
    }

    public function getAllContent() {
        return $this->sql->query( "SELECT * FROM content" );
    }
}