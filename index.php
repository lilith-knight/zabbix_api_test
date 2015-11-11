<!DOCTYPE html>
<html>

    <head>

        <meta charset="UTF-8" />

        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css" />

        <link rel="stylesheet" href="./Css/tooltip.css" />

        <title>Zabbix API test</title>

    </head>

    <body>

    <?php

    include './Module/functions.php';
    require './Module/base.php';

    /*
     * 動作パラメーター
     */
    $params = array(
        'user'      =>  'Admin',
        'pass'      =>  'zabbix',
        'host'      =>  'http://192.168.2.44/zabbix/api_jsonrpc.php',
        'id'        =>  '10',
        'header'    =>  'Content-Type:application/json-rpc'

    );
    $mode = 0;

    /* login確認 */
    if (!isset($auth)) {

        $obj = new base( $params );

        $obj
            ->  zapi_login()
            ->  create_request();

        $auth = $obj -> get_contents();

        if ( isset( $auth["error"] ) ) {

            echo '<div><label>';

            echo 'Login name or password is incorrect';
            #var_dump( $auth );

            echo '</label></div>';

        }

        if ( isset( $auth["result"] ) ) {

            if ( $mode != 0 ) {

                echo $auth["result"]."<br />\r\n";

            }

            # 取得したSIDを送り返す
            $obj -> set_value( array( 'auth' => $auth["result"] ) );

        }

    }

    echo '<div class="pure-g">';
    echo '<div class="pure-u-1-6">';

    /* host一覧取得 */
    $obj
        ->  zapi_hostget()
        ->  create_request();

    $hostids = $obj -> get_contents();

    foreach ( $hostids["result"] as $key1 => $value ) {

        # apiからの出力順番を弄れないので配列の中身を入れ替え
        $val1 = sort_array( 1, $value );

        foreach ( $val1 as $key2 => $val2 ) {

            $val2 = str_replace( " ", "_", $val2 );

            if ( $key2 === 'name' ) {

                echo '<form class="pure-form" method="GET" name="host_get" value="host_get" action="">'."\r\n";

                echo '<button type="submit" style="width:210px ; margin:9px" class="pure-button">'.$val2."\r\n";

            }

            if ( $key2 === 'hostid' ) {

                echo '<input type="hidden" class="pure-button" name="'.$key2.'" value="'.$val2.'">';

				echo '<input type="hidden" class="pure-button" name="search" value="hostid" />'."\r\n";

                echo '</button></form>'."\r\n";

            }

        }

    }

    echo '</div>'."\r\n";

    echo '<div class="pure-u-1-3">';

    if ( filter_input( INPUT_GET, 'search' ) != '' ) {

        if  ( filter_input( INPUT_GET, 'hostid' ) != '' ) {

            $hostid = filter_input( INPUT_GET, 'hostid' );

			var_dump($hostid);

            /* アイテム一覧取得 */
            $obj
                ->  zapi_itemget($hostid)
                ->  create_request();

            $itemids = $obj -> get_contents();

            foreach ( $itemids["result"] as $key1 => $value ) {

                # apiからの出力順番を弄れないので配列の中身を入れ替え
                $val1 = sort_array( 2, $value );

                foreach ( $val1 as $key2 => $val2 ) {

                    echo $key2.' => '.$val2."<br />\r\n";

                }

            }

        }

    }

    var_dump($_GET);

    echo '</div>';

    echo '<div class="pure-u-1-2">';
    echo '</div>';

    echo '</div>';

    /* ログアウト処理 */
    $obj
        ->  zapi_logout()
        ->  create_request();

    $response = $obj -> get_contents();

    if ($mode != 0) {

        var_dump($response);

    }

    unset($obj);

    ?>

    </body>

</html>
