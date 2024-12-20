<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int Subscription ID
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *   'order_id'             => $order_info['order_id'],
	 *   'store_id'             => $order_info['store_id'],
	 *   'customer_id'          => $order_info['customer_id'],
	 *   'payment_address_id'   => $order_info['payment_address_id'],
	 *   'payment_method'       => $order_info['payment_method'],
	 *   'shipping_address_id'  => $order_info['shipping_address_id'],
	 *   'shipping_method'      => $order_info['shipping_method'],
	 *   'subscription_plan_id' => $order_subscription_info['subscription_plan_id'],
	 *   'price'                => $order_subscription_info['price'],
	 *   'frequency'            => $order_subscription_info['frequency'],
	 *   'cycle'                => $order_subscription_info['cycle'],
	 *   'duration'             => $order_subscription_info['duration'],
	 *   'comment'              => $order_info['comment'],
	 *   'affiliate_id'         => $order_info['affiliate_id'],
	 *   'marketing_id'         => $order_info['marketing_id'],
	 *   'tracking'             => $order_info['tracking'],
	 *   'language_id'          => $order_info['language_id'],
	 *   'currency_id'          => $order_info['currency_id']
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $subscription_id = $this->model_checkout_subscription->addSubscription($subscription_data);
	 */
	public function addSubscription(array $data): int {
		if ($data['duration']) {
			$remaining = $data['duration'] - 1;
		} else {
			$remaining = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape(date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']))) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `language_id` = '" . (int)$data['language_id'] . "', `currency_id` = '" . (int)$data['currency_id'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		$subscription_id = $this->db->getLastId();

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
		}

		return $subscription_id;
	}

	/**
	 * Edit Subscription
	 *
	 * @param int                  $subscription_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *   'order_id'             => $order_info['order_id'],
	 *   'store_id'             => $order_info['store_id'],
	 *   'customer_id'          => $order_info['customer_id'],
	 *   'payment_address_id'   => $order_info['payment_address_id'],
	 *   'payment_method'       => $order_info['payment_method'],
	 *   'shipping_address_id'  => $order_info['shipping_address_id'],
	 *   'shipping_method'      => $order_info['shipping_method'],
	 *   'subscription_plan_id' => $order_subscription_info['subscription_plan_id'],
	 *   'trial_price'          => $order_subscription_info['trial_price'],
	 *   'trial_frequency'      => $order_subscription_info['trial_frequency'],
	 *   'trial_cycle'          => $order_subscription_info['trial_cycle'],
	 *   'trial_duration'       => $order_subscription_info['trial_duration'],
	 *   'trial_status'         => $order_subscription_info['trial_status'],
	 *   'price'                => $order_subscription_info['price'],
	 *   'frequency'            => $order_subscription_info['frequency'],
	 *   'cycle'                => $order_subscription_info['cycle'],
	 *   'duration'             => $order_subscription_info['duration'],
	 *   'comment'              => $order_info['comment'],
	 *   'affiliate_id'         => $order_info['affiliate_id'],
	 *   'marketing_id'         => $order_info['marketing_id'],
	 *   'tracking'             => $order_info['tracking'],
	 *   'language_id'          => $order_info['language_id'],
	 *   'currency_id'          => $order_info['currency_id']
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addSubscription($subscription_id, $subscription_data);
	 */
	public function editSubscription(int $subscription_id, array $data): void {
		if ($data['duration']) {
			$remaining = $data['duration'] - 1;
		} else {
			$remaining = 0;
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape(date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']))) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `language_id` = '" . (int)$data['language_id'] . "', `currency_id` = '" . (int)$data['currency_id'] . "', `date_modified` = NOW() WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteProducts($subscription_id);

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
		}
	}

	/**
	 * Edit Subscription Status
	 *
	 * @param int  $subscription_id
	 * @param bool $subscription_status_id
	 *
	 * @return void
	 */
	public function editSubscriptionStatus(int $subscription_id, bool $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Remaining
	 *
	 * @param int $subscription_id
	 * @param int $remaining
	 *
	 * @return void
	 */
	public function editRemaining(int $subscription_id, int $remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `remaining` = '" . (int)$remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Date Next
	 *
	 * @param int    $subscription_id
	 * @param string $date_next
	 *
	 * @return void
	 */
	public function editDateNext(int $subscription_id, string $date_next): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Delete Subscription By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Subscriptions
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSubscriptions(array $data): array {
		$sql = "SELECT `s`.`subscription_id`, `s`.*, CONCAT(`o`.`firstname`, ' ', `o`.`lastname`) AS `customer`, (SELECT `ss`.`name` FROM `" . DB_PREFIX . "subscription_status` `ss` WHERE `ss`.`subscription_status_id` = `s`.`subscription_status_id` AND `ss`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `subscription_status` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = `o`.`order_id`)";

		$implode = [];

		if (!empty($data['filter_subscription_id'])) {
			$implode[] = "`s`.`subscription_id` = '" . (int)$data['filter_subscription_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`s`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_order_product_id'])) {
			$implode[] = "`s`.`order_product_id` = '" . (int)$data['filter_order_product_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`o`.`firstname`, ' ', `o`.`lastname`) LIKE '" . $this->db->escape($data['filter_customer'] . '%') . "'";
		}

		if (!empty($data['filter_date_next'])) {
			$implode[] = "DATE(`s`.`date_next`) = DATE('" . $this->db->escape($data['filter_date_next']) . "')";
		}

		if (!empty($data['filter_subscription_status_id'])) {
			$implode[] = "`s`.`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`s`.`date_added`) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`s`.`date_added`) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			's.subscription_id',
			's.order_id',
			's.reference',
			'customer',
			's.subscription_status',
			's.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `s`.`subscription_id`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Add Product
	 *
	 * @param int   $subscription_id
	 * @param array $product
	 *
	 * @return void
	 */
	public function addProduct(int $subscription_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_product` SET `subscription_id` = '" . (int)$subscription_id . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `order_id` = '" . (int)$data['order_id'] . "', `product_id` =  '" . (int)$data['product_id'] . "', `option` = '" . $this->db->escape($data['option'] ? json_encode($data['option']) : '') . "', `quantity` = '" . (int)$data['quantity'] . "', `price` = '" . (float)$data['price'] . "'");
	}

	/**
	 * Delete Product
	 *
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteProducts(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Get Subscription By Order Product ID
	 *
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array<string, mixed>
	 */
	public function getProductByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		if ($query->num_rows) {
			return ['option' => $query->row['option'] ? json_decode($query->row['option'], true) : ''] + $query->row;
		}

		return [];
	}

	/**
	 * Add History
	 *
	 * @param int    $subscription_id
	 * @param int    $subscription_status_id
	 * @param string $comment
	 * @param bool   $notify
	 *
	 * @return void
	 */
	public function addHistory(int $subscription_id, int $subscription_status_id, string $comment = '', bool $notify = false): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");
	}
}
