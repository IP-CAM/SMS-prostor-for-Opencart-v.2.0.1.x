<?php
class ControllerToolSmsprostor extends Controller {
	private $data = array();

	private $error_array = array(
		"error authorization" =>"Ошибка авторизации");

	public function index() {
	    //подключение перевода
		$this->load->language('tool/smsprostor');
		//подключение моделей
		$this->load->model('tool/smsprostor');
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');

		//установка заголовка страницы
		$this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('view/stylesheet/select2/select2.min.css');
        $this->document->addScript('view/javascript/select2/select2.min.js');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (!$this->user->hasPermission('modify', 'tool/smsprostor')) {
				$this->error['warning'] = $this->language->get('error_permission');
				$this->session->data['error'] = $this->language->get('error_permission');
			} else {
				$this->model_setting_setting->editSetting('smsprostor', $this->request->post, 0);
				$this->session->data['success'] = $this->language->get('text_success');
			}
			$this->response->redirect(HTTP_SERVER.'index.php?route=tool/smsprostor&token=' . $this->session->data['token']);
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('tool', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/smsprostor', 'token=' . $this->session->data['token'], 'SSL'),
		);

		//загрузка языковых переменных
        $this->data = array_merge($this->data, $this->load->language('tool/smsprostor'));

		//загрузка ссылок
		$this->data['error_warning']  = '';
		$this->data['action']         = $this->url->link('tool/smsprostor', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action_process'] = str_replace('amp;','', $this->url->link('tool/smsprostor/process_recipients', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['action_send'] = str_replace('amp;','', $this->url->link('tool/smsprostor/process_phone', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['cancel']         = $this->url->link('extension/tool', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['data']           = $this->model_setting_setting->getSetting('smsprostor');

		$this->data['token']          = $this->session->data['token'];

		if (isset($this->data['data']['smsprostor-login']) && isset($this->data['data']['smsprostor-password'])) {
			$balance = $this->model_tool_smsprostor->get_balance($this->data['data']['smsprostor-login'], $this->data['data']['smsprostor-password']);
			$this->data['balance'] = (in_array('balance', $balance))?$balance['balance']:'-';
            $this->data['senders'] = $this->model_tool_smsprostor->get_senders($this->data['data']['smsprostor-login'], $this->data['data']['smsprostor-password']);
		}

        $this->data['customer_groups'] = $this->model_tool_smsprostor->get_customer_groups();
        $this->data['customers'] = $this->model_tool_smsprostor->get_customers();

		$this->data['header']		= $this->load->controller('common/header');
		$this->data['column_left']	= $this->load->controller('common/column_left');
		$this->data['footer']		= $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/smsprostor.tpl', $this->data));
	}

	public function install() {
		$this->load->model('tool/smsprostor');
		$this->model_tool_smsprostor->install();
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('smsprostor', 'post.order.history.add', 'module/smsprostor/onCheckout');
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('smsprostor', array(
            'smsprostor-sender' => '', //сиимвольное обозначение администратора
            'smsprostor-phone' => '', //телефон администратора
            'smsprostor-login' => '', //логин
            'smsprostor-password' => '', //пароль
            'smsprostor-message-customer' => '{firstname}! Спасибо за покупку в нашем магазине. Ваш номер заказа {orderid}',//текст сообщения клиенту
            'smsprostor-message-admin' => 'Новый заказ #{orderid} от {firstname} {lastname} на сумму {total}',//текст сообщения админу
            'smsprostor-send-customer' => 0, //отправлять клиенту при заказе
            'smsprostor-send-admin' => 0, //отправлять админу при заказе
            'smsprostor-enabled' => 0 //модуль включен/выключен
        ), 0);
	}

	public function process_recipients(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && (isset($this->request->post['data']))) {
            $phones = json_decode(str_replace('&quot;','"', $this->request->post['data']));
            $this->load->model('tool/smsprostor');
            $results = array();
            foreach ($phones as $phone) {
                if ($phone == '*') {
                    $customers = $this->model_tool_smsprostor->get_customers();
                    foreach ($customers as $customer) {
                        $results[] = $this->model_tool_smsprostor->clear_phone($customer['telephone']);
                    }
                } elseif (substr($phone, 0, 1) == '@') {
                    $groupid = substr($phone, 1);
                    if (!isset($customers)) {
                        $customers = $this->model_tool_smsprostor->get_customers();
                    }
                    foreach ($customers as $customer) {
                        if ($customer['customer_group_id'] == $groupid) {
                            $results[] = $this->model_tool_smsprostor->clear_phone($customer['telephone']);
                        }
                    }
                } else {
                    $results[] = $this->model_tool_smsprostor->clear_phone($phone);
                }
            }
            $results = array_unique($results);
            echo json_encode($results);
        }
    }

    public function process_phone(){
        if (
            ($this->request->server['REQUEST_METHOD'] == 'POST')
            && (isset($this->request->post['phone']))
            && (isset($this->request->post['message']))
        ) {
            $this->load->model('tool/smsprostor');
            $this->load->model('setting/setting');
            $setting = $this->model_setting_setting->getSetting('smsprostor');
            if( isset($setting)
                && ($setting['smsprostor-enabled'])
                && (!empty($setting['smsprostor-login']))
                && (!empty($setting['smsprostor-password']))
            ) {
                $this->model_tool_smsprostor->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $this->request->post['phone'],
                    $this->request->post['message'],
                    $setting['smsprostor-sender']
				);
            }
        }
    }

	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('smsprostor_module', 0);
		$this->load->model('tool/smsprostor');
		$this->model_tool_smsprostor->uninstall();
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('smsprostor');
	}
}