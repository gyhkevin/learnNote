<?php
/**
 * parse the request url, grab image constantly 
 * 		by getting html tag <a>
 * @author pankai<530911044@qq.com>
 */
class Pivotal {
	private static $_Config = array();
	
	private static $_CapSite = NULL;
	
	/* acceptable icon */
	private static $_imgAccept = NULL;
 /* private $_request_url = NULL; */
	private static $_savePath = NULL;
	
	private static $_pregMatch = NULL;
	
	private $_source_file = NULL;
	
	private $_tagA = array();
	private $_tagImg = array();
	
	private $_mark = TRUE;
	private static $_markTime = 1;
	private static $_interval = NULL;
	
	public static $_accompImg = array();
	
	/* initialize the Pivotal class */
	public function __construct( &$_Cfg, &$_source ) {
	/*	$this->_request_url = $_site['request_url']; */
		self::$_Config = &$_Cfg;
		
		self::$_CapSite = require $_Cfg['site'];
		
		self::$_savePath = self::$_CapSite['save_path'];
		self::$_interval = self::$_CapSite['save_img_interval'];
		self::$_imgAccept = self::$_CapSite['accept_type'];
		
		self::$_pregMatch = require $_Cfg['preg'];
		$this->_source_file = &$_source;
	}
	
	/**
	 * save image associating with specified image source
	 * @param $_image_src
	 * @param $_file_name
	 */
	private function grabImage( $_image_src, $_file_name = NULL ) {
		$_final_name = NULL; $_ext = NULL;
		if( $_image_src == NULL ) {
			return NULL;			
		}
		$_final_name = strtolower( end( explode( '/', $_image_src ) ) );
		if( $_file_name === NULL ) {
			/* 'Img'.date( "YmdHis" ).'.'.$_ext */
			$_file_name = $_final_name;
		}
		if( in_array( $_file_name, self::$_accompImg ) ) {
			return NULL;
		}
		
		$_ext = strtolower( end( explode( '.', $_image_src ) ) );		
		if( ! in_array( $_ext, self::$_imgAccept ) ) {
			return FALSE;
		}
		
		if( ! is_dir( self::$_savePath ) ) {
			mkdir( self::$_savePath, 0777 );
		}
		if( ! is_readable( self::$_savePath ) ) {
			chmod( self::$_savePath, 0777 );
		}
		
		$_Length =  count( self::$_accompImg );
		$_prefix = isset( self::$_CapSite['partition_name'] ) ? self::$_CapSite['partition_name'] : '';
		$_dir =  self::$_savePath.$_prefix.( intval( $_Length / self::$_CapSite['dir_file_limit'] ) ).DIRECTORY_SEPARATOR;
		if( file_exists( $_dir ) === FALSE ) {
			mkdir( $_dir, 0777 );
		}
		$_file_path = $_dir.$_file_name;
		
		$this->_mark = FALSE;
		
		ob_start();
		readfile( $_image_src );
		$_img = ob_get_contents();
		ob_end_clean();
		$_size = strlen( $_img );
		
	    /**
		 * equals:
		 * 	 $_fp = fopen( $_file_path, 'wb' );
		 *   $_return_size = fwrite( $_fp, $_img, $_size );
		 *   fclose( $_fp );
		 */ 
		$_return_size = OperateFile::setText( $_file_path, $_img );
		
		if( $_return_size === $_size ) {
			$this->_mark = TRUE; 
		}
		
		self::$_accompImg[] = $_file_name;
		if( count( self::$_accompImg ) % self::$_CapSite['serialize_img_size'] == 0 ) {
			OperateFile::setText( self::$_Config['accompImg'], serialize( self::$_accompImg ) );
		}
		
		return $_file_path;
	}
	
	private function replaceSource() {
		return preg_replace( array_keys( self::$_pregMatch ),
			 self::$_pregMatch, $this->_source_file['body'] );
	}
	
	private function pregTag() {
		$_replace_html = $this->replaceSource();
		/**
		 * unset( $variable ): when the value of variable own storage beyond 256 bytes, 
		 * 		it could be freed;
		 */
		unset( $this->_source_file['body'] );
		$this->_source_file['body'] = &$_replace_html;
		
		$_reg_tagA = '/<a(.*?)href=[\'|"](\s*http:\/\/.*?)[\'|"](.*?)>/i';
		preg_match_all( $_reg_tagA, $this->_source_file['body'], $_matchesA );
		$this->_tagA = &$_matchesA[2];
		
		$_reg_tagImg = '/<img(.*?)src=[\'|"](\s*http:\/\/.*?)[\'|"](.*?)\/?>/i';
		preg_match_all( $_reg_tagImg, $this->_source_file['body'], $_matchesImg );
		$this->_tagImg = &$_matchesImg[2];
	}
	
	public function parseUrl() {
		$this->pregTag();
		$_Img_Length = count( $this->_tagImg );
		for( $i = 0; $i < $_Img_Length; $i ++ ) {
			if( stripos( $this->_tagImg[$i], self::$_CapSite['domain_name'] ) === FALSE ) {
				continue;
			}
			do {
				if( ( $_file_path = $this->grabImage( $this->_tagImg[$i] ) ) == NULL ) {
					break;
				}
				echo '正在抓取: '.$this->_tagImg[$i].'\n\r';
				/**
				 * sleep( $second )
				 * Delays the program execution for the given number of seconds
				 */
				sleep( self::$_interval * self::$_markTime );
				if( $this->_mark === TRUE ) {
					self::$_markTime = self::$_interval;
					break;
				}
				if( @unlink( $_file_path ) === FALSE ) {
					break;
				}
				self::$_markTime *= 2;
			} while( true );
		}
		return $this->_tagA;
	}
}
?>
