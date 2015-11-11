<?php

function sort_array( $s_flag, $data ) {

    switch ( $s_flag ) {

        case 1:
            $sort = array_fill_keys( array(
                'name' , 'hostid'),
                    null );
            break;

        case 2:
            $sort = array_fill_keys( array(
                'name' , 'key_' , 'itemid' , 'value_type' ,
                'status' , 'hostid'
                    ), null );
            break;

    }

    return array_replace( $sort, $data );

}

?>