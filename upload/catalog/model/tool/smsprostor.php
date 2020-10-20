<?php
class ModelToolSmsprostor extends Model {

    public function checkout($order_id, $order_status) {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('smsprostor');
		if( isset($setting)
            && ($setting['smsprostor-enabled'])
            && (!empty($setting['smsprostor-login']))
            && (!empty($setting['smsprostor-password']))
        ) {
            if ((isset($setting['smsprostor-send-customer'])) && ($order_status == $setting['smsprostor-status1'])) {
                $original = array(
                    "{storename}",
                    "{orderid}",
                    "{firstname}",
                    "{lastname}"
                );
                $replace = array(
                    $this->config->get('config_name'),
                    $order_id,
                    $order_info['firstname'],
                    $order_info['lastname']
                );
                $message = str_replace($original, $replace, $setting['smsprostor-message-customer']);
                $this->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $order_info['telephone'],
                    $message,
                    $setting['smsprostor-sender']
                );
            }
            if ((isset($setting['smsprostor-send-admin'])) && ($order_status == $setting['smsprostor-status2'])) {
                $original = array(
                    "{storename}",
                    "{orderid}",
                    "{firstname}",
                    "{lastname}",
                    "{email}",
                    "{total}"
                );
                $replace = array(
                    $this->config->get('config_name'),
                    $order_id,
                    $order_info['firstname'],
                    $order_info['lastname'],
                    $order_info['email'],
                    $this->currency->format($order_info['total'], $this->session->data['currency'])
                );
                $message = str_replace($original, $replace, $setting['smsprostor-message-admin']);
                $this->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $setting['smsprostor-phone'],
                    $message,
                    $setting['smsprostor-sender']
                );
            }
		}
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

    private function clear_phone($phone) {
        $original = array('(', ')', '-', ' ');
        $replace = array('','','','');
        return str_replace($original, $replace, $phone);
    }
}