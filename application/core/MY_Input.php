<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Input extends CI_Input {

    public function __construct()
    {
        parent::__construct();
    }

    function post($index = '', $xss_clean = true)
    {
        $post_result = $this->_fetch_from_array($_POST, $index, $xss_clean);
        
        if(is_array($post_result)){
            foreach ($post_result as $key => $value) {
                $post_result[$key] = trim($value);
            }
        }else{
            $post_result = trim($post_result);
        }
       
        return $post_result;
    }
}