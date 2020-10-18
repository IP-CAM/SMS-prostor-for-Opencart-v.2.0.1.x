<?php
class ControllerExtensionModuleSmsprostor extends Controller {
	private $data = array();

	private $error_array = array(
		"error authorization" =>"Ошибка авторизации");

	public function index() {
	    //подключение перевода
		$this->load->language('extension/module/smsprostor');
		//подключение моделей
		$this->load->model('extension/module/smsprostor');
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');

		//установка заголовка страницы
		$this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('view/stylesheet/select2/select2.min.css');
        $this->document->addScript('view/javascript/select2/select2.min.js');

		//анализ POST
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		    //проверка разрешений модуля
			if (!$this->user->hasPermission('modify', 'module/smsprostor')) {
			    //отправка ошибок
				$this->error['warning'] = $this->language->get('error_permission');
				$this->session->data['error'] = $this->language->get('error_permission');
			} else {
			    //сохранение данных POST в таблицу настроек
				$this->model_setting_setting->editSetting('smsprostor', $this->request->post, 0);
				$this->session->data['success'] = $this->language->get('text_success');
			}
			//переадресация на себя же, но уже с новыми настройками
			$this->response->redirect(HTTP_SERVER.'index.php?route=extension/module/smsprostor&token=' . $this->session->data['token']);
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

		//генерация хлебных крошек
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/smsprostor', 'token=' . $this->session->data['token'], 'SSL'),
		);

		//загрузка языковых переменных
        $this->data = array_merge($this->data, $this->load->language('extension/module/smsprostor'));

		//загрузка ссылок
		$this->data['error_warning']  = '';
		$this->data['action']         = $this->url->link('extension/module/smsprostor', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action_process'] = str_replace('amp;','', $this->url->link('extension/module/smsprostor/process_recipients', 'token=' . $this->session->data['token'], 'SSL'));
        $this->data['action_send'] = str_replace('amp;','', $this->url->link('extension/module/smsprostor/process_phone', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['cancel']         = $this->url->link('extension/extension/module', 'token=' . $this->session->data['token'], 'SSL');

		//загрузка настроек
		$this->data['data']           = $this->model_setting_setting->getSetting('smsprostor');

		//токен пользователя
		$this->data['token']          = $this->session->data['token'];

		//если указаны учетные данные, попытка загрузки баланса
		if (isset($this->data['data']['smsprostor-login']) && isset($this->data['data']['smsprostor-password'])) {
			$balance = $this->model_extension_module_smsprostor->get_balance($this->data['data']['smsprostor-login'], $this->data['data']['smsprostor-password']);
			$this->data['balance'] = (in_array('balance', $balance))?$balance['balance']:'-';
            $this->data['senders'] = $this->model_extension_module_smsprostor->get_senders($this->data['data']['smsprostor-login'], $this->data['data']['smsprostor-password']);
		}

        $this->data['customer_groups'] = $this->model_extension_module_smsprostor->get_customer_groups();
        $this->data['customers'] = $this->model_extension_module_smsprostor->get_customers();

		//стандартные контроллеры ОК
		$this->data['header']		= $this->load->controller('common/header');
		$this->data['column_left']	= $this->load->controller('common/column_left');
		$this->data['footer']		= $this->load->controller('common/footer');

		//вывод страницы
		$this->response->setOutput($this->load->view('extension/module/smsprostor.tpl', $this->data));
	}

	//дейтсвия при установке модуля
	public function install() {
	    //подключение модели
		$this->load->model('extension/module/smsprostor');
		//функция инсталла из модели
		$this->model_extension_module_smsprostor->install();
		//подключение менеджера событий
		$this->load->model('extension/event');
		//монтирование событий в реестр ОК
		$this->model_extension_event->addEvent('smsprostor', 'post.order.history.add', 'module/smsprostor/onCheckout');
		//подключение модели настроек
		$this->load->model('setting/setting');
		//создание дефолтных настроек
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
            $this->load->model('extension/module/smsprostor');
            $results = array();
            foreach ($phones as $phone) {
                if ($phone == '*') {
                    $customers = $this->model_extension_module_smsprostor->get_customers();
                    foreach ($customers as $customer) {
                        $results[] = $this->model_extension_module_smsprostor->clear_phone($customer['telephone']);
                    }
                } elseif (substr($phone, 0, 1) == '@') {
                    $groupid = substr($phone, 1);
                    if (!isset($customers)) {
                        $customers = $this->model_extension_module_smsprostor->get_customers();
                    }
                    foreach ($customers as $customer) {
                        if ($customer['customer_group_id'] == $groupid) {
                            $results[] = $this->model_extension_module_smsprostor->clear_phone($customer['telephone']);
                        }
                    }
                } else {
                    $results[] = $this->model_extension_module_smsprostor->clear_phone($phone);
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
            $this->load->model('extension/module/smsprostor');
            $this->load->model('setting/setting');
            $setting = $this->model_setting_setting->getSetting('smsprostor');
            if( isset($setting)
                && ($setting['smsprostor-enabled'])
                && (!empty($setting['smsprostor-login']))
                && (!empty($setting['smsprostor-password']))
            ) {
                print_r($this->model_extension_module_smsprostor->sms_send(
                    $setting['smsprostor-login'],
                    $setting['smsprostor-password'],
                    $this->request->post['phone'],
                    $this->request->post['message'],
                    $setting['smsprostor-sender']
                ));
            }
        }
    }

	public function uninstall() {
        //подключение модели настроек
		$this->load->model('setting/setting');
		//удаление настроек модуля
		$this->model_setting_setting->deleteSetting('smsprostor_module', 0);
		//подключение модели модуля
		$this->load->model('extension/module/smsprostor');
		//запуск анинсталла из модели
		$this->model_extension_module_smsprostor->uninstall();
		//подключение менеджера событий
		$this->load->model('extension/event');
		//удаление событий, связанных с модулем
		$this->model_extension_event->deleteEvent('smsprostor');
	}
}