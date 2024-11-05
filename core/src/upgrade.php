<?php

namespace CR;

class Upgrade {
    var $config = null;

    var $mainUrl = 'https://raw.githubusercontent.com/duanestorey/dsdotcom/refs/heads/main/crossroads';
    var $mainZip = 'https://github.com/duanestorey/crossroads/archive/refs/heads/stable.zip';

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function runUpgrader() {
        $latestVersion = Utils::curlDownloadFile( $this->mainUrl );
        $result = preg_match_all( '#define\( \'CROSSROADS_VERSION\', \'(.*)\'#', $latestVersion, $matches );
        if ( $result ) {
            $currentVersion = $matches[ 1 ][ 0 ];

            LOG( sprintf( _i18n( 'core.upgrade.cur_ver' ), CROSSROADS_VERSION ), 1, LOG::INFO );
            LOG( sprintf( _i18n( 'core.upgrade.next_ver' ), $currentVersion ), 1, LOG::INFO );

            $compare = version_compare( $currentVersion, CROSSROADS_VERSION );
            if ( $compare != 0 ) {
                LOG( sprintf( _i18n( 'core.upgrade.up_to_date' ), $currentVersion ), 1, LOG::INFO );
            } else if ( $compare == 0 ) {
                $this->downloadZipAndExpand();

                LOG( _i18n( 'core.upgrade.composer' ), 1, LOG::INFO );
                exec( 'composer update' );  
            }  
        }
    }

    public function downloadZipAndExpand() {
        LOG( _i18n( 'core.upgrade.downloading' ), 1, LOG::INFO );
        $zipFile = Utils::curlDownloadFile( $this->mainZip );
        if ( $zipFile ) {
            
            $tempDir = sys_get_temp_dir();
            $destinationFile = Utils::fixPath( $tempDir ) . '/' . basename( $zipFile );
        }
    }
}