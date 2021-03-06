<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Total;
class Handling extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index(): void {
		$this->load->language('extension/opencart/total/handling');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('total_handling', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/total/handling', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/total/handling', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		if (isset($this->request->post['total_handling_total'])) {
			$data['total_handling_total'] = $this->request->post['total_handling_total'];
		} else {
			$data['total_handling_total'] = $this->config->get('total_handling_total');
		}

		if (isset($this->request->post['total_handling_fee'])) {
			$data['total_handling_fee'] = $this->request->post['total_handling_fee'];
		} else {
			$data['total_handling_fee'] = $this->config->get('total_handling_fee');
		}

		if (isset($this->request->post['total_handling_tax_class_id'])) {
			$data['total_handling_tax_class_id'] = $this->request->post['total_handling_tax_class_id'];
		} else {
			$data['total_handling_tax_class_id'] = $this->config->get('total_handling_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['total_handling_status'])) {
			$data['total_handling_status'] = $this->request->post['total_handling_status'];
		} else {
			$data['total_handling_status'] = $this->config->get('total_handling_status');
		}

		if (isset($this->request->post['total_handling_sort_order'])) {
			$data['total_handling_sort_order'] = $this->request->post['total_handling_sort_order'];
		} else {
			$data['total_handling_sort_order'] = $this->config->get('total_handling_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/total/handling', $data));
	}

	protected function validate(): bool {
		if (!$this->user->hasPermission('modify', 'extension/opencart/total/handling')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}