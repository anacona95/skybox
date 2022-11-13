<?php

/**
 * Clase de configuracion de envio de correo
 */
class ConfigEmail extends CI_Model
{

    protected $config = [];

    public function __construct()
    {

        $this->load->modelORM('McConfig');
        $this->load->library('email');

        $objConfig = McConfig::find(1);

        $this->config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => $objConfig->smtp_user,
            'smtp_pass' => $objConfig->smtp_pass,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];
        $this->email->initialize($this->config);
        $this->email->from($objConfig->smtp_from, $objConfig->smtp_from_name);
        parent::__construct();

    }

    public function from($email, $name)
    {
        $this->email->from($email, $name);
    }

    public function to($to)
    {
        $this->email->to($to);
    }

    public function subject($subject)
    {
        $this->email->subject($subject);
    }

    public function message($message)
    {
        $this->email->message($message);
    }

    public function send()
    {
        return $this->email->send();
    }
    public function attach($path)
    {
        return $this->email->attach($path);
    }
    public function print_debugger()
    {
        return $this->email->print_debugger();
    }

}
