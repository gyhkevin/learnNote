<?php

/**
* 
*/
class imgCatcher
{
	const FLODER_DIR = 'image/';
	const IMG_TYPE = 'jpg|bmp|png|gif';

	private $floder;
	private $url;
	private $type;
	private $domain;
	private $pic_arr;
	private $pattern;
	function __construct($url,$floder='',$type='')
	{
		$this->url = $url;
		$url_arr = parse_url($url);
		$this->domain = array_shift($url_arr);
		if(trim($floder) == ""){
            $this->folder = FLODER_DIR;
        }else{
            $this->folder = $floder.'/';
        }

        if(trim($type)==""){
            $this->type = IMG_TYPE;
        }else{
            $this->type = $type; 
        }
         
        $this->pic_arr = array();
        if(!is_dir(__DIR__.'/'.$this->folder))
            mkdir(__DIR__.'/'.$this->folder);
	}

	public function get_pic($pattern=''){
        set_time_limit(0);//抓取不受时间限制  
        //获取图片二进制流  
        $data = self::CurlGet($this->url);  
        //利用正则表达式得到图片链接  
        if (empty($pattern)) {
        	$this->pattern = '/<img.*?src\=\"(.*\.('.$this->type.')).*?>/';
        }else{
        	$this->pattern = $pattern;
        }
        $num = preg_match_all($this->pattern, $data, $match_src);  

        $this->pic_arr=$match_src[1];//获得图片数组


        $this->get_name();   

        return 0;  
    } 

    public function get_name(){  
        $pic_arr = $this->pic_arr;
         
        //图片编号和类型  
        $pattern_type = '/.*\/(.*?)$/';  
         
        foreach($pic_arr as $pic_item){//循环取出每幅图的地址  
         
            $num = preg_match_all($pattern_type,$pic_item,$match_type);  
            //以流的形式保存图片  
            $write_fd = fopen($this->folder.$match_type[1][0],"wb");  

            fwrite($write_fd, self::CurlGet($pic_item,$this->url));  
            fclose($write_fd);  
        }

        return 0;  
    } 


     //抓取网页内容  
    static function CurlGet($url,$domain=""){
        if(substr($url, 0, 1) == "/"){
            $url =  $domain.$url;
        }

        if(substr($url, 0, 1) == "."){
            $url =  $domain.substr($url, 1);
        }

        if(substr($url, 0, 2) == ".."){
            $url =  $domain.substr($url, 2);
        }           
        $url=str_replace('&','&',$url);  
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_URL, $url);  
        curl_setopt($curl, CURLOPT_HEADER, false);  
         
        //curl_setopt($curl, CURLOPT_REFERER,$url);  
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; SeaPort/1.2; Windows NT 5.1; SV1; InfoPath.2)");  
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');  
        curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');  
        curl_setopt($curl, CURLOPT_TIMEOUT, 360);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);  
        $values = curl_exec($curl);  
        curl_close($curl);  
        return $values;  
    }     
 
}