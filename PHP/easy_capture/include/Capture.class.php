<?php
/**
 * The main class
 * @author pankai<530911044@qq.com>
 * @date 2013-08-10
 */
class Capture {
	private static $_Config = array();
	
	private static $_CapSite = NULL;
	private static $_CapPreg = NULL;
	
	private static $_overURL = array();
	
	private $_mark = FALSE;
	private static $_markTime = 1;
	/**
	 * initialize the main class: Capture
	 * @param $_cfg array
	 */
	public function __construct( &$_cfg ) {
		self::$_Config = &$_cfg;
		
		self::$_CapSite = require $_cfg['site'];
		self::$_CapPreg = require $_cfg['preg'];
		
		foreach( self::$_CapPreg as $_key => $_value ) {
			self::$_CapPreg[$_key] = str_replace( '_request_site', self::$_CapSite['request_site'], $_value );
		}
		
		self::import( 'file.OperateFile' );
		if( file_exists( $_cfg['overURL'] ) && filesize( $_cfg['overURL'] ) > 0 ) {
			$_contents = OperateFile::readText( $_cfg['overURL'], filesize( $_cfg['overURL'] ) );
			self::$_overURL = unserialize( $_contents );
		}
		
		self::import('pivotal.Pivotal');
		if( file_exists( $_cfg['accompImg'] ) && filesize( $_cfg['accompImg'] ) > 0 ) {
			$_contents = OperateFile::readText( $_cfg['accompImg'], filesize( $_cfg['accompImg'] ) );
			Pivotal::$_accompImg = unserialize( $_contents );
		}
		
	}
	/**
	 * load class, follow Java pragrammer(package): import com.jUnion.Capture
	 * @param $_class
	 */
	public static function import( $_class ) {
		require_once __Home__.'include'.__Os__.str_replace( '.', __Os__, $_class ).'.class.php';
	}
	
	/**
	 * create an instance of Pivotal class
	 * @param $_source
	 */
	private function getCapInstance( &$_source ) {
		$this->_mark = FALSE;
		
		$_Captal = new Pivotal( self::$_Config, $_source );
		$_tagA = $_Captal->parseUrl();
		
		$this->_mark = TRUE;
		
		return $_tagA;
	}
	
	/**
	 * go forward one by one
	 * @param $_tagArr
	 */
	private function roundTagA( &$_tagArr ) {
		if( $_tagArr == NULL ) {
			return;
		}
		$_tagArrLength = count( $_tagArr );
		for( $i = 0; $i < $_tagArrLength; $i ++ ) {
			if( is_array( $_tagArr[ $i ] ) ) {
				$this->roundTagA( $_tagArr[ $i ] );  
			}
			else {
				if( stripos( $_tagArr[$i], self::$_CapSite['domain_name'] )
					=== FALSE ) {
						continue;
					}
				if( in_array( $_tagArr[$i], self::$_overURL ) ) {
					continue;
				}
				self::$_overURL[] = $_tagArr[$i];
				if( count( self::$_overURL ) % self::$_CapSite['serialize_url_size'] == 0 ) {
					OperateFile::setText( self::$_Config['overURL'], serialize( self::$_overURL ) );
				}
				do {
					$_tagA = $this->getCapInstance( Http::get( $_tagArr[$i] ) );
					sleep( self::$_CapSite['preform_page_time'] * self::$_markTime );
					if( $this->_mark === TRUE ) {
						self::$_markTime = self::$_CapSite['preform_page_time'];
						break;
					}
					self::$_markTime *= 2;
				} while( true );
				/* parse the main page and return next page */
				$this->roundTagA( $_tagA );
			}
		}
	}
	
	public function parseQuestUrl() {
		self::import('http.Http');
		$request_url = Http::get( self::$_CapSite['request_url'] );
		$_round_Arr = $this->getCapInstance( $request_url );
		$this->roundTagA( $_round_Arr ); 
	}
}
?>