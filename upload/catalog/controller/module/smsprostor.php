<?php
class ControllerModuleSmsprostor extends Controller {

	public function onCheckout($order_id) {
	    //загрузка ордера
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		//загрузка модели
        $this->load->model('module/smsprostor');
        //загрузка настроек модуля
		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('smsprostor');
        //проверка корректности
		if( isset($setting)
            && ($setting['smsprostor-enabled'])
            && (!empty($setting['smsprostor-login']))
            && (!empty($setting['smsprostor-password']))
        ) {
		    //отправка сообщения покупателю
            if (isset($setting['smsprostor-send-customer'])) {
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
                $this->model_module_smsprostor->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $order_info['telephone'],
                    $message,
                    $setting['smsprostor-sender']
                );
            }
            //отправка сообщения админу
            if (isset($setting['smsprostor-send-admin'])) {
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
                    $this->currency->format($order_info['total'])
                );
                $message = str_replace($original, $replace, $setting['smsprostor-message-customer']);
                $this->model_module_smsprostor->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $setting['smsprostor-phone'],
                    $message,
                    $setting['smsprostor-sender']
                );
            }
		}
    }

}