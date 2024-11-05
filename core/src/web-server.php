<?php

namespace CR;

class WebServer {
    var $socket = null;
    var $boundPort = 0;
    var $address = "127.0.0.1";
    var $shutdown = false;

    public function __construct() {
    }

    public function init() {
        $this->socket = socket_create( AF_INET, SOCK_STREAM, 0 );
        if ( $this->socket ) {
            socket_bind( $this->socket, '127.0.0.1', $this->boundPort ); 
            
            socket_getsockname( $this->socket, $address, $this->boundPort );

            if ( $this->boundPort ) {
                LOG( "Web server successfully started at http://127.0.0.1:" . $this->boundPort, 1, LOG::INFO );
            }
        }
    }

    public function serve() {
        $connection = false;

        socket_listen( $this->socket, 0 );
        socket_set_nonblock( $this->socket );

        declare(ticks = 1);
        pcntl_signal( SIGINT, array( $this, '_shutdown' ) ); 
        pcntl_signal( SIGTERM, array( $this, '_shutdown' ) ); 

        while ( true ) {
            $connection = socket_accept( $this->socket );
            if ( $connection !== false ) {
                $this->_handle_client( $connection );
            } else if ( $connection === false ) {
                usleep( 100 );
            } else {
                break;
            }

            if ( $this->shutdown ) {
                LOG( "Shutting down web server", 0, LOG::INFO );
                socket_close( $this->socket );
                break;
            }
        }
    }

    private function _handle_client( $socket ) {
        $input = socket_read( $socket, 8192 );
        if ( $input ) {
            if ( preg_match( '#(.*) (.*) HTTP/(.*)#', $input, $matches ) ) {
                $protocol = strtolower( $matches[ 1 ] );
                $url = $matches[ 2 ];

                LOG( "Incoming HTTP request from URL [" . $url . "]", 2, LOG::DEBUG );

                $parsedUrl = parse_url( $url );    
                $filePath = $parsedUrl[ 'path' ];

                if ( $protocol == 'get' ) {
                    $localUrl = CROSSROAD_PUBLIC_DIR . $filePath;
                    if ( is_dir( $localUrl ) ) {
                        // directory
                        if ( file_exists( $localUrl . 'index.html' ) ) {
                            $this->_sendFile( $socket, $localUrl . 'index.html' );
                        }
                    } else if ( file_exists( $localUrl ) ) {
                        $this->_sendFile( $socket, $localUrl );
                    } else {
                        // 400 Ok
                        $this->_sendResponse( $socket, 404 );
                    }
                }            
            }
        } 

        socket_close( $socket );
    }

    public function _shutdown() {
        $this->shutdown = true;  
    }

    private function _sendFile( $socket, $file ) {
        $contents = file_get_contents( $file );
        if ( $contents ) {
            $extension = pathinfo( $file, PATHINFO_EXTENSION );

            $contentType = match( $extension ) {
                'html' => 'text/html',
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'css' => 'text/css',
                'webp' => 'image/webp',
                'js' => 'text/javascript',
                'avif' => 'image/avif',
                'ico' => 'image/x-icon',
                default => 'application/octet-stream'
            };

            $headers = array(
                'Content-type' => $contentType,
                'Content-length' => strlen( $contents )
            );

            $this->_sendResponse( $socket, 200, $headers, $contents );
        }
    }

    private function _sendResponse( $socket, $responseCode = 200, $headers = [], $content = false ) {
        $response = match( $responseCode ) {
            200 => "HTTP/1.1 200 OK",
            404 => "HTTP/1.1 404 NOT FOUND"
        };

        $response = $response . "\n";

        foreach( $headers as $name => $value ) {
            $response = $response . $name . ': ' . $value . "\n";
        }

        $response = $response . "\n";

        if ( $content ) {
            $response = $response . $content;
        }

        LOG( "Sending response [" . $responseCode . "]", 3, LOG::DEBUG );

        $totalSent = 0;
        $toSend = strlen( $response );
        while ( true ) {
            LOG( "Sending " . $totalSent . ' bytes of ' . $toSend, 3, LOG::DEBUG );
            $sent = socket_send( $socket, $response, min( 16*1024, $toSend), 0 );
            if ( $sent ) {
                $totalSent += $sent;

                if ( $totalSent == $toSend ) {
                    break;
                } 

                $toSend -= $sent;

                $response = substr( $response, $sent );
            } else {
                break;
            }
        }
        
    }
}