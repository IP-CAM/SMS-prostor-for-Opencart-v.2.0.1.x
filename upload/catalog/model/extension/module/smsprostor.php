<?php
class ModelExtensionModuleSmsprostor extends Model {

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

    private function clear_phone($phone) {
        $original = array('(', ')', '-', ' ');
        $replace = array('','','','');
        return str_replace($original, $replace, $phone);
    }
}