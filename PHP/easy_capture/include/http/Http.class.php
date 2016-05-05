<?php
/**
 * php http client
 * @author pankai<530911044@qq.com>
 */
class Http {
	public static $time_out = 60;
	public static function post( $_url, $_array ) {
		$urlArr = parse_url($_url);
        $urlArr['path'] = ( $urlArr['path']=='' ) ? '/' : $urlArr['path'];
        $urlArr['port'] = ( $urlArr['port']=='' ) ? '80' : $urlArr['port'];
		$handle = fsockopen( $urlArr['host'], $urlArr['port'], $errorno, $err_str, self::$time_out );
    	if ( $handle == FALSE )
        	return FALSE;
		$_arguments = '';
        foreach ( $_array as $_name => $_value ) {
            $_item = $_name.'='.$_value;
            $_arguments .= ( $_arguments == '' ) ? $_item : '&'.$_item;
        }
		/* create request header */
        $_out  = "POST ".$urlArr['path']." HTTP/1.0\r\n";
        $_out .= "Accept: */*\r\n";
        $_out .= "Host: ".$urlArr['host']."\r\n";
        $_out .= "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0\r\n";
        $_out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $_out .= "Content-Length: ".strlen( $_arguments )."\r\n";
        $_out .= "Connection: Close\r\n";
        $_out .= "\r\n";
        $_out .= $_arguments."\r\n\r\n";
        
        /* send request message */
        if ( fwrite( $handle, $_out ) == FALSE ) {
            fclose( $handle );
            return FALSE; 
        }
		/* get the back feed */
        $_return = '';
        while ( ! feof( $handle ) ) {
        	$_return .= fgets( $handle, 2048 );
        }
		$rArr = array();
        /* cut the header message */
        $_pos = stripos($_return, "\r\n\r\n");
        $rArr['head'] = trim( substr( $_return, 0, $_pos ) );
        $rArr['body'] = trim( substr( $_return, $_pos ) );
        fclose( $handle );
        return $rArr;
	}
	public static function get( $_url ) {
        $urlArr = parse_url( $_url );
        $urlArr['path'] = ! isset( $urlArr['path'] ) ? '/' : $urlArr['path'];
        $urlArr['port'] = ! isset( $urlArr['port'] ) ? '80' : $urlArr['port'];
        $handle = fsockopen( $urlArr['host'], $urlArr['port'], $errorno, $err_str, self::$time_out );
        if ( $handle == FALSE )
            return FALSE;
        
        $_query = $urlArr['path'] . ( ! isset( $urlArr['query'] ) ? '' : '?'.$urlArr['query'] );
        $_query = $_query . ( ! isset( $urlArr['fragment'] ) ? '' : '#'.$urlArr['fragment'] );
        /* create request header */
        $_out  = "GET ".$_query." HTTP/1.0\r\n";
        $_out .= "Accept: */*\r\n";
        $_out .= "Host: ".$urlArr['host']."\r\n";
        $_out .= "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0\r\n";
	 /* $_out .= "Referer: $urlArr['host']/\r\n"; */
        $_out .= "Connection: Close\r\n";
        $_out .= "\r\n";    
        /* send request message */
        if ( fwrite( $handle, $_out ) == FALSE ) {
            fclose( $handle );
            return FALSE; 
        }
        /* get the back feed */
        $_return = '';
        while ( ! feof( $handle ) ) {
        	$_return .= fgets( $handle, 2048 );
        }
        $rArr = array();
        /* cut the header message */
        $_pos = stripos( $_return, "\r\n\r\n" );
        $rArr['head'] = trim( substr( $_return, 0, $_pos ) );
        $rArr['body'] = trim( substr( $_return, $_pos ) );
        fclose( $handle );
        return $rArr;
	 }
}
?>