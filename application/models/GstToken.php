<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
class GstToken extends CI_Model
{
    private $key = "d3e32d4d6f5c6a1e2a072f92168f80a8";
    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('GstApi');
    }

    public function createToken($data_token)
    {
        return JWT::encode($data_token, $this->key);
    }

    public function validateToken()
    {
        $headers = apache_request_headers();
    
        if(!isset($headers['Authorization'])){
            return false;
        }

        try {
            JWT::$leeway = 60; // $leeway in seconds
            $data_token = JWT::decode($headers['Authorization'], $this->key, array('HS256'));
    
        } catch (\Exception $e) {
            return false;
        }

        if(!isset($data_token->user_id) || !isset($data_token->email) || !isset($data_token->passwd)){
            return false;
        }
        $objCliente = $this->GstApi->getUserById($data_token->user_id);
        if(!$objCliente){
            return false;
        }

        if($objCliente->email != $data_token->email){
            return false;
        }

        $parts = explode(':', $data_token->passwd);
        $truePass = $parts[1];
        $partsDb = explode(':', $objCliente->password);
        $passDb = $partsDb[1];
        
        if ($passDb != $truePass) {
            return false;
        }

        return true;
        
    }

    public function getUserToken()
    {
        $headers = apache_request_headers();
    
        if(!isset($headers['Authorization'])){
            return false;
        }

        try {
            JWT::$leeway = 60; // $leeway in seconds
            $data_token = JWT::decode($headers['Authorization'], $this->key, array('HS256'));
        } catch (\Exception $e) {
            return false;
        }
        
        $objCliente = $this->GstApi->getUserById($data_token->user_id);
        
        if(!$objCliente){
            return false;
        }

        return $objCliente;

    }
}    