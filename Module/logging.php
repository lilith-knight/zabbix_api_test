<?php

################################################################################
#
# [log file] is daily change
# [log folder] is monthly change
#
# log file path -> ./Log/yyyyMM/yyyyMMdd_log.txt
#
# input  -> main_logging( $addr, $data );
# output -> epoch ',' $addr(remoteIP) ',' $data(for writing message ) \r\n
#        -> $addr = filter_input(INPUT_SERVER, 'REMOTE_ADDR')
#        -> $data = request string ?
#
################################################################################

class Logging {

    private $file;
    private $folder;

    function __construct( $values ) {

        foreach ( $values as $key => $value ) {

            $this -> $key = $value ;

        }

    }

    function create_folder_path() {

        $log_folder = $this -> folder . $this -> month;

        $this -> log_folder = $log_folder;

        return $this;

    }

    function get_month() {

        $month = date('Ym');

        $this -> month = $month;

        return $this;

    }

    function folder_check () {

        $fc = file_exists( $this -> log_folder );

        $this -> fc = $fc;

        return $this;

    }

    function create_file_path() {

        $log_file = $this -> folder . $this -> month .'/'. $this -> date . '_' . $this -> file;

        $this -> log_file = $log_file;

        return $this;

    }

    function get_time () {

        $date = date('Ymd');

        $this -> date = $date;

        return $this;

    }

    function file_check () {

        $fc = file_exists( $this -> log_file );

        $this -> fc = $fc;

        return $this;

    }

    function presence_check_folder() {

        # log folder check
        $this -> get_month() -> create_folder_path() -> folder_check();

        if ( $this -> fc === false ) {

            if ( !mkdir( $this -> log_folder, 0755 ) ) {

                throw new Exception('It failed to create a log_folder');

            }

        }

    }

    function presence_check_file() {

        # log file check
        $this -> get_time() -> create_file_path() -> file_check();

        if ( $this-> fc === false ) {

            if ( !touch( $this -> log_file ) ) {

                throw new Exception('It failed to create a log_file');

            }

            if ( !chmod( $this -> log_file, 0755 ) ) {

                throw new Exception('It failed to set permissions');

            }

        }

    }

    function main_logging ( $addr, $data ) {

        try {

            if ( isset( $this -> log_folder ) === false ) {

                $this -> presence_check_folder();

            }

            if ( isset( $this -> log_file ) === false ) {

                $this -> presence_check_file();

            }

            $str = time().','.$addr.','.$data;

            $handle = fopen( $this -> log_file, "a+" );

            if ( $handle === false ) {

                throw new Exception('It failed to open log_file');

            }

            if ( !fwrite( $handle, $str."\r\n" ) ) {

                throw new Exception('It failed to write message');

            }

        } catch (Exception $ex) {

            die( $ex -> getMessage() );

        } finally {

            fclose( $handle );

        }

    }

}

?>