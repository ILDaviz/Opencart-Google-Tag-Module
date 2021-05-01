<?php
class ControllerExtensionModuleTagmanager extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/tagmanager');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_tagmanager', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/tagmanager', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/tagmanager', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['module_tagmanager_head_code'])) {
			$data['module_tagmanager_head_code'] = $this->request->post['module_tagmanager_head_code'];
		} else {
			$data['module_tagmanager_head_code'] = $this->model_setting_setting->getSettingValue('module_tagmanager_head_code');
		}

		if (isset($this->request->post['module_tagmanager_body_code'])) {
			$data['module_tagmanager_body_code'] = $this->request->post['module_tagmanager_body_code'];
		} else {
			$data['module_tagmanager_body_code'] = $this->model_setting_setting->getSettingValue('module_tagmanager_body_code');
		}

		if (isset($this->request->post['module_tagmanager_status'])) {
			$data['module_tagmanager_status'] = $this->request->post['module_tagmanager_status'];
		} else {
			$data['module_tagmanager_status'] = $this->model_setting_setting->getSettingValue('module_tagmanager_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/tagmanager', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/tagmanager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_tagmanager_head_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->request->post['module_tagmanager_body_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('tagmanager_head', 'catalog/view/common/header/after', 'extension/module/tagmanager/insertHeadCodeAfter');
		$this->model_setting_event->addEvent('tagmanager_body', 'catalog/view/common/header/after', 'extension/module/tagmanager/insertBodyCodeAfter');
        $this->model_setting_event->addEvent('tagmanager_save_session_order', 'catalog/controller/checkout/success/before', 'extension/module/tagmanager/saveOrderConfirmPreConfirmPageBefore');
	}

	public function uninstall() {
		$this->load->model('setting/event');
		$this->model_setting_event->deleteEventByCode('tagmanager_head');
		$this->model_setting_event->deleteEventByCode('tagmanager_body');
        $this->model_setting_event->deleteEventByCode('tagmanager_save_session_order');
	}
}
