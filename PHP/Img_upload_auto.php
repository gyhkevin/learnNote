<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Author:	  Martin
 * @DateTime:	2015-01-06 17:27:14
 * @Description: 文件上传类
 */
class img_upload_auto {
	var $CI;
	var $thumb_width ;
	var $thumb_height ;
	var $create_thumb;
	var $input_name;
	public function __construct(){
  		$this->CI =& get_instance();
        $this->CI->load->helper(array('form', 'url'));
 	}
 	/**
 	 * 初始化
 	 * @param  boolean $create_thumb 是否生成缩略图
 	 * @param  integer $thumb_width  缩略图宽度
 	 * @param  integer $thumb_height 缩略图高度
 	 * @return array                
 	 */
 	public function make_by_w($create_thumb = FALSE,$thumb_width =0,$thumb_height=0,$input_name = 'img'){
 		//初始化参数
 		$this->thumb_width = $thumb_width;
        $this->thumb_height = $thumb_height;
        $this->create_thumb = $create_thumb;
        $this->input_name = $input_name;

 		$result = $this->img_upload_by_w();
 		return $result;
 	}
 	public function makeFold($dir){ 
       return is_dir($dir) or ($this->makeFold(dirname($dir)) and mkdir($dir, 0777)); 
	}

	public function img_upload_by_w(){
		//写入数据库
		$result = $this->get_upload_by_w();
		return $result;
	}
 	public function get_upload_by_w(){
 		$fold = date('Ym', time());
		$config['upload_path'] = './public/upload/images/'.$fold.'/';
		$config['allowed_types'] = 'gif|jpg|png';
		//$config['file_name'] = md5(time()).'_'.sprintf('%02d', rand(0,99));
		$config['file_name'] = md5(time()).mt_rand(1,1000000);
		$config['max_size'] = 1024*5;
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		
		$foldinfo = $this->makeFold($config['upload_path']);
		$this->CI->load->library('upload', $config);
		if ( !$this->CI->upload->do_upload($this->input_name)){
			$result['status'] = '1';
	  		$result['text']= $this->CI->upload->display_errors(); //[error] => <p>You did not select a file to upload.</p>
	 	}else{
	  		$info= array('data' => $this->CI->upload->data());
	  		//原图路径
	  		$original = '/public/upload/images/'.$fold.'/'.$info['data']['raw_name'].$info['data']['file_ext']; 
	  		$result['status'] = '0';
	  		$result['text'] = $original;
	  		//如果是JPG则尝试修正旋转
	  		if ($info['data']['file_ext'] == '.jpg'){
	  			$this->correctImageOrientation(BASEPATH.'..'.$original);
	  		}
	  		//生成缩略图
	  		$destination = '/public/upload/images/'.$fold.'/thumb_'.$info['data']['raw_name'].$info['data']['file_ext'];
	  		$thumb_check = $this->create_thumb_img(BASEPATH.'..'.$original,BASEPATH.'..'.$destination);
	  		//如果创建缩略图成功
	  		if ($thumb_check){
	  			$result['text'] = $destination;
	  		}
	 	}
	 	return $result;
	}
	public function create_thumb_img($source,$destination){
		if ($this->create_thumb === TRUE){
	  		$check = $this->image_resize_by_w($source , $destination , $this->thumb_width ,$this->thumb_height);
		}
		return $check;
	}
	public function image_resize_by_w($f, $t, $tw){
		$temp = array(1=>'gif', 2=>'jpeg', 3=>'png');
 
        list($fw, $fh, $tmp) = getimagesize($f);
 
        if(!$temp[$tmp]){
                return false;
        }
        $tmp = $temp[$tmp];
        $infunc = "imagecreatefrom$tmp";
        $outfunc = "image$tmp";
        $fimg = $infunc($f);
        // 使缩略后的图片不变形，并且限制在 缩略图宽高范围内
        $th = $tw*($fh/$fw);
        $timg = imagecreatetruecolor($tw, $th);
        imagecopyresampled($timg, $fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
        if($outfunc($timg, $t)){
                return true;
        }else{
                return false;
        }
}
//更正图片旋转
private function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
        $img = imagecreatefromjpeg($filename);
        $deg = 0;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename 
        imagejpeg($img, $filename, 95);
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}


	public function imageCreateFromAny($filepath) { 
	    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
	    $allowedTypes = array( 
	        1,  // [] gif 
	        2,  // [] jpg 
	        3,  // [] png 
	        6   // [] bmp 
	    ); 
	    if (!in_array($type, $allowedTypes)) { 
	        return false; 
	    } 
	    switch ($type) { 
	        case 1 : 
	            $im = imageCreateFromGif($filepath); 
	        break; 
	        case 2 : 
	            $im = imageCreateFromJpeg($filepath); 
	        break; 
	        case 3 : 
	            $im = imageCreateFromPng($filepath); 
	        break; 
	        case 6 : 
	            $im = imageCreateFromBmp($filepath); 
	        break; 
	    }    
	    return $im;  
	} 

}