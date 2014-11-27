<?php

namespace Jigoshop\Helper;

use Jigoshop\Core\Options;
use Jigoshop\Core\Pages;
use Jigoshop\Entity\Customer\Guest;
use Jigoshop\Entity\Order\Status;

class Order
{
	/** @var Options */
	private static $options;

	/**
	 * @param Options $options Options object.
	 */
	public static function setOptions($options)
	{
		self::$options = $options;
	}

	public static function getStatus(\Jigoshop\Entity\Order $order)
	{
		$statuses = Status::getStatuses();
		$status = $order->getStatus();
		if (!isset($statuses[$status])) {
			$status = Status::CREATED;
		}
		$text = $statuses[$status];
		return sprintf('<mark class="%s" title="%s">%s</mark>', $status, $text, $text);
	}

	public static function getUserLink($customer)
	{
		if ($customer instanceof Guest) {
			return $customer->getName();
		}

		return sprintf('<a href="%s">%s</a>', get_edit_user_link($customer->getId()), $customer->getName());
	}

	/**
	 * @param $order \Jigoshop\Entity\Order
	 * @return string
	 */
	public static function getCancelLink($order)
	{
		$args = array(
			'action' => 'cancel_order',
			'nonce' => wp_create_nonce('cancel_order'),
			'id' => $order->getId(),
			'key' => '', // TODO: Implement order key security $order->getKey(),
		);
		$url = add_query_arg($args, get_permalink(self::$options->getPageId(Pages::CART)));

		return apply_filters('jigoshop_get_cancel_order', $url);
	}
}
