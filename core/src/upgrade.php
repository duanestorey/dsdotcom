<?php

namespace CR;

class Upgrade {
    var $config = null;

    var $mainUrl = 'https://raw.githubusercontent.com/duanestorey/crossroads/refs/heads/main/crossroads';
    var $mainZip = 'https://codeload.github.com/duanestorey/crossroads/zip/refs/heads/stable';

    public function __construct( $config ) {
        $this->config = $config;
    }

    public function runUpgrader() {
        $latestVersion = Utils::curlDownloadFile( $this->mainUrl );
        $result = preg_match_all( '#define\( \'CROSSROADS_VERSION\', \'(.*)\'#', $latestVersion, $matches );
        if ( $result ) {
            $currentVersion = $matches[ 1 ][ 0 ];

            LOG( sprintf( _i18n( 'core.class.upgrade.cur_ver' ), CROSSROADS_VERSION ), 1, LOG::INFO );
            LOG( sprintf( _i18n( 'core.class.upgrade.next_ver' ), $currentVersion ), 1, LOG::INFO );

            $compare = version_compare( $currentVersion, CROSSROADS_VERSION );
            if ( $compare != 1 ) {
                LOG( sprintf( _i18n( 'core.class.upgrade.up_to_date' ), $currentVersion ), 1, LOG::INFO );
            } else if ( $compare == 1 ) {
                $zipFile = $this->downloadZipAndExpand();

                if ( $zipFile ) {
                  LOG( _i18n( 'core.class.upgrade.unzip' ), 2, LOG::INFO );

                    $tempDir = sys_get_temp_dir();
                    $destinationDir = $tempDir;

                    @mkdir( $destinationDir );

                    $unzipDirectory =  $destinationDir . '/crossroads-stable';
                    $unzipDirectory = CROSSROADS_BASE_DIR;

                    /*
                    if ( file_exists( $unzipDirectory ) ) {
                        Utils::recursiveRmdir( $unzipDirectory );
                    }
                    */

                    $command = sprintf( 'unzip -d %s %s', CROSSROADS_BASE_DIR, $zipFile );
                    $output = shell_exec( $command );
                    echo $output;
                    
                    if ( file_exists( $unzipDirectory ) && is_dir( $unzipDirectory ) ) {
                        // we can copy the files now

                        LOG( _i18n( 'core.class.upgrade.composer' ), 2, LOG::INFO );

                        exec( 'composer update' ); 
                    }             
                }
            }  
        }
    }

    public function downloadZipAndExpand() {
        LOG( _i18n( 'core.class.upgrade.downloading' ), 2, LOG::INFO );
        
        $zipFile = Utils::curlDownloadFile( $this->mainZip );
        if ( $zipFile ) {
            $tempDir = sys_get_temp_dir();
            $destinationFile = tempnam( Utils::fixPath( $tempDir ), 'crossroads-' ) . '.zip';

            file_put_contents( $destinationFile, $zipFile );

            return $destinationFile;
        }

        return false;
    }
}