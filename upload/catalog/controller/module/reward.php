<?php
class ControllerModuleReward extends Controller {
	public function index() {
		$data = array();
		
		$this->load->language('module/reward');
					
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;
		
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}
					
		$data['heading_title'] = sprintf($this->language->get('heading_title'), $points);
		
		$data['text_loading'] = $this->language->get('text_loading');
	
		$data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);
		
		$data['button_reward'] = $this->language->get('button_reward');
		
		$data['status'] = ($points && $points_total && $this->config->get('reward_status'));
		
		if (isset($this->session->data['reward'])) {
			$data['reward'] = $this->session->data['reward'];
		} else {
			$data['reward'] = '';
		}
					
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/reward.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/reward.tpl', $data);
		} else {
			return $this->load->view('default/template/module/reward.tpl', $data);
		}		
	}
	
	public function reward() {
		$this->load->language('voucher/reward');
		
		$json = array();
		
		$points = $this->customer->getRewardPoints();
		
		$points_total = 0;
		
		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}	
				
		if (empty($this->request->post['reward'])) {
			$json['error'] = $this->language->get('error_reward');
		}
	
		if ($this->request->post['reward'] > $points) {
			$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}
		
		if ($this->request->post['reward'] > $points_total) {
			$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}
		
		if (!$json) {
			$this->session->data['reward'] = abs($this->request->post['reward']);
				
			$this->session->data['success'] = $this->language->get('text_reward');
				
			$json['redirect'] = $this->url->link('checkout/cart');		
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}