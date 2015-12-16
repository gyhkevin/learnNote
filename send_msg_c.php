<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Signup extends CI_Controller { 
    public function __construct(){
    parent::__construct();
    $this->load->model('comm_model');
    $this->load->library('message');
    }
  private function template($main,$data = array()){
    $this->load->view($main,$data);

  }
  
  
        
       
        //获取短信验证码
        public function sms_validate(){
        $type = $this->input->post('types');//发送验证码原因
        $phonenum = $this->input->post('phonenum');//手机号
        if(!preg_match("/1[34578]{1}\d{9}$/",$phonenum)){  
        $result = array('status' => 1,'text' =>'手机号码不正确' ); 
         $this->output->set_header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            die();
        }
        if (($type==FALSE) or ($phonenum==FALSE)) {
          return FALSE;
        }else{
          $valadatanum = $this->pass_num(6);//验证码字符串
          $this->session->set_userdata('reg_verifi', $valadatanum);//session验证码字符串
          $this->session->set_userdata('reg_phone', $phonenum);
          $requery = $this->phone_sms($phonenum,$valadatanum,$type);
          if ($requery) {
            $result = array('status' => 0,'text' =>'' );
            $this->output->set_header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
          }else{
            $result = array('status' => 1,'text' =>'短信发送失败' );
            $this->output->set_header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
              }
            }
          }
        //发送短信
        private function phone_sms($phonenum,$valadatanum,$type)
        {
        $this->load->library('sendsms');
        return $this->sendsms->send($phonenum,$valadatanum,$type);
        }
        //获取短信验证随机数
        private function pass_num($num) {
        $return_string = '';
        $tmpstr = '0123456789';
        for ($i=0;$i<$num;$i++) {
          $return_string .= $tmpstr{mt_rand(0,strlen($tmpstr)-1)};
        }
        return $return_string;
        }
        //自动生成唯一编号 32位
        private function build_order_no(){
            if (function_exists('com_create_guid')){
              return com_create_guid();
            }else{
              mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
              $charid = strtoupper(md5(uniqid(rand(), true)));
              return $charid;
            }
        }

        
    }