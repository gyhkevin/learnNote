<?php
/**
 * files input and output
 * @author pankai<530911044@qq.com>
 */
class OperateFile {
	/**
	 * file I/O operate, set context into specified file
	 * @param $_file_name
	 * @param $_context
	 * @return mixed int or bool
	 */
	public static function setText( $_file_name, $_context, $_IOType = 'wb' ) {
		/**
		 * fopen() 		return resource or FALSE
		 * flock()		return bool
		 * fwrite()		return int or FALSE
		 * fclose()		return bool
		 */
		if( ( $_fp = fopen( $_file_name, $_IOType ) ) != FALSE ) {
			$_Length = strlen( $_context );
			if( flock( $_fp, LOCK_EX ) ) {
				if( ( $_size = fwrite( $_fp, $_context, $_Length ) ) === FALSE ) {
					flock( $_fp, LOCK_UN );
					fclose( $_fp );
					return FALSE;
				}
				flock( $_fp, LOCK_UN );
			}
			fclose( $_fp );
			return $_size;
		}
	}
	
	/**
	 * file I/O opearate, read the given length from the file
	 * @param $_file_name
	 * @param $_length
	 * @return mixed $_contents or FALSE
	 */
	public static function readText( $_file_name, $_length, $_IOType = 'r' ) {
		if( $_length <= 0 ) {
			return FALSE;
		}
		if( ( $_fp = fopen( $_file_name, $_IOType ) ) !== FALSE ) {
			if( flock( $_fp, LOCK_UN ) ) {
				if( ( $_contents =  fread( $_fp, $_length ) ) === FALSE ) {
					flock( $_fp, LOCK_UN );
					fclose( $_fp );
					return FALSE;
				}
				flock( $_fp, LOCK_UN );
			}
			fclose( $_fp );
			return $_contents;
		}
	}
}
?>