<?php

class base {

    private $user;
    private $pass;
    private $host;
    private $id;
    private $header;
    private $auth;

    function __construct( $params ) {

        $this -> set_value($params);

    }

    function set_value ( $params ) {

        foreach ( $params as $key => $value ) {

            $this -> $key = (string)filter_var( $value );

        }

    }

    function get_contents() {

        $contents = file_get_contents( $this -> host, false, $this -> context );

        $result = json_decode( $contents, true );

        return $result;

    }

    function create_request() {

        $options['http'] = array(
            'method'    =>  'POST',
            'header'    =>  $this -> header,
            'content'   =>  json_encode( $this -> data )
        );

        $values = array( 'folder' => './Log/' , 'file' => 'log.txt' );

        $log = new Logging($values);

        $log -> main_logging( filter_input(INPUT_SERVER, 'REMOTE_ADDR'), json_encode($options) );

        $this -> context = stream_context_create( $options );

        return $this;

    }

    function zapi_login() {

        $data = array(
            'method'    =>  'user.login',
            'params'    =>  array(
                'user'      =>  $this -> user,
                'password'  =>  $this -> pass
            ),
            'id'        =>  $this -> id,
            'jsonrpc'   =>  '2.0'
        );

        $this -> data = $data;

        return $this;

    }

    function  zapi_logout() {

        $data = array(
            'method'    =>  'user.logout',
            'params'    =>  array(),
            'id'        =>  $this ->id,
            'auth'      =>  $this -> auth,
            'jsonrpc'   =>  '2.0'
        );

        $this -> data = $data;

        return $this;

    }

    function zapi_hostget(){

        $data = array(
            'method'    =>  'host.get',
            'params'    =>  array(
                "output"    =>  array(
                    'name','hostid'
                )
            ),
            'id'        =>  $this -> id,
            'auth'      =>  $this -> auth,
            'jsonrpc'   =>  '2.0'
        );

        $this -> data = $data;

        return $this;

    }

    function zapi_itemget( $hostid ){

        $data = array(
            'method'    =>  'item.get',
            'params'    =>  array(
                "output"    =>  array(
                    'name' , 'itemid' , 'key_' ,
                    'value_type' , 'status' , 'hostid'
                ),
                "hostids"   =>  $hostid
            ),
            'id'        =>  $this -> id,
            'auth'      =>  $this -> auth,
            'jsonrpc'   =>  '2.0'
        );

        $this -> data = $data;

        return $this;

    }

}

?>