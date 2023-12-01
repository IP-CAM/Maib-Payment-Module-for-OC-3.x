<?php

class ControllerExtensionPaymentMaib extends Controller {
	private $error = array();

	public function install() {
     $this->load->model('setting/event');
     $this->model_setting_event->addEvent('maib', 'catalog/model/checkout/order/addOrderHistory/before', 'extension/payment/maib/addOrderHistoryBefore');
     $this->model_setting_event->addEvent('maib', 'catalog/model/checkout/order/addOrderHistory/after', 'extension/payment/maib/addOrderHistoryAfter');
	}

	public function uninstall() {
     $this->load->model('setting/event');
     $this->model_setting_event->deleteEventByCode('maib');
	}

	public function index() {
		$this->load->language('extension/payment/maib');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
		
			$this->cache->delete("access_token");
			$this->cache->delete("access_token_expires");

			$this->model_setting_setting->editSetting('payment_maib', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token='
				. $this->session->data['user_token'] . '&type=payment', true));
				
		}
	
		$data['breadcrumbs'] = $this->getBreadCrumbs();

		$data['_form'] = $this->getPostData();
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['_error'] = $this->error;
    	
		$data['action'] = $this->url->link('extension/payment/maib', 'user_token='
    		. $this->session->data['user_token'], 'SSL');
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token='
			. $this->session->data['user_token'] . '&type=payment', true);		
				$this->load->model('localisation/geo_zone');
		
		$catalog = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG; 
        	$data['ok_url'] = $catalog . 'index.php?route=extension/payment/maib/ok';
        	$data['fail_url'] = $catalog . 'index.php?route=extension/payment/maib/fail';
		$data['callback_url'] = $catalog . 'index.php?route=extension/payment/maib/callback';
	
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$template = 'extension/payment/maib';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($template, $data)); 
	}
	
	private function validate() {
		$post_data = $this->request->post;

		if (!$this->user->hasPermission('modify', 'extension/payment/maib')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$required = array('payment_maib_project_id', 'payment_maib_project_secret', 'payment_maib_signature_key');

		foreach ($required as $field) {
			if (empty($post_data[$field])) {
				$this->error[$field] = $this->language->get('error_empty_field');
			}
		}
		
		if (count($this->error)) {
			return false;
		}

		return empty($this->error);
	}	
	
	
	private function getBreadCrumbs() {
		$breadcrumbs = array();

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token='
				. $this->session->data['user_token'], 'SSL'),
			'separator' => false
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token='
				. $this->session->data['user_token'] . '&type=payment', true),
			'separator' => ' :: '
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/maib', 'user_token='
				. $this->session->data['user_token'], 'SSL'),      		
			'separator' => ' :: '
		);
		
		return $breadcrumbs;
	}	
	
	private function getPostData() {
		$defautls = $this->getDefaults();
		foreach ($defautls as $key => $value) {
			$config = $this->config->get($key);
			if ($config !== null) {
				$defautls[$key] = $config;
			}
		}
		return array_merge($defautls, $this->request->post);
	}
	
	private function getDefaults() {
		return array(
           		'payment_maib_title' => 'maib (Visa / Mastercard / Apple Pay / Google Pay)',
			'payment_maib_status' => 1,
			'payment_maib_debug' => 0,
			'payment_maib_sort_order' => 0,
			'payment_maib_geo_zone_id' => '',
			
			'payment_maib_project_id' => '',
			'payment_maib_project_secret' => '',
			'payment_maib_signature_key' => '',
			
			'payment_maib_order_pending_status_id' => 1,
			'payment_maib_order_success_status_id' => 2,
			'payment_maib_order_fail_status_id' => 10,			
			'payment_maib_order_refund_status_id' => 11,
		);
	}	
}
