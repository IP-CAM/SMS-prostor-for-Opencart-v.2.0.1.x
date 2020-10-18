<?php

class ModelToolSmsprostor extends Model {
	
	public function install() {
	// Install Code
	}

	public function uninstall() {
	// Uninstall Code
	}

    public function get_balance($login = '', $password = '') {
	    $response = $this->send_request('http://api.prostor-sms.ru/messages/v2/balance/', array(
            "login"	=>	$login,
            "password" => $password
        ));
        $json['error'] = 0;
        $del = explode(';', $response);
        $json['balance'] = $del[1]." р.";
        return $json;
    }

    public function get_senders($login = '', $password = '') {
        $response = $this->send_request('http://api.prostor-sms.ru/messages/v2/senders/', array(
            "login"	=>	$login,
            "password" => $password
        ));
        $response = explode("\n", $response);
        $senders = array();

        foreach ($response as $sender) {
            $arr = explode(';', $sender);
            $senders[] = $arr[0];
        }
        return $senders;
    }

    private function send_request($url, $params) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function get_customer_groups(){
        $this->load->model('customer/customer_group');
        return $this->model_customer_customer_group->getCustomerGroups();
    }

    public function get_customers(){
        $this->load->model('customer/customer');
        return $this->model_customer_customer->getCustomers();
    }

    public function clear_phone($phone) {
        $original = array('(', ')', '-', ' ', '+');
        $replace = array('','','','', '');
        return str_replace($original, $replace, $phone);
    }

    private function send_request_get($url, $params) {
        $ch = curl_init($url.http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function sms_send($login, $password, $to, $text, $sender='') {
        return $this->send_request_get('http://api.prostor-sms.ru/messages/v2/send/?', array(
            "login"	    =>	$login,
            "password"  =>  $password,
            "phone"		=>	$this->clear_phone($to),
            "text"		=>	$text,
            "sender"    =>	$sender
        ));
    }
}