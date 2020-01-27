<?php

require_once dirname(__FILE__) . '/Message.php';
require_once dirname(__FILE__) . '/User.php';
require_once dirname(__FILE__) . '/Cart.php';

class Purchase {

	public function __construct () {
		global $_conn;

		if( !isset($_conn) )
			Message::error_message('Cannot connect to database');
	}

	public function list_transport_types () {
		global $_conn;

		$transport_types = $_conn->fetchAll("SELECT * FROM transport_type");
		return $transport_types;
	}

	private function get_transport_type ($transport_type_id = 0) {
		global $_conn;

		$transport_type = $_conn->fetchOne("SELECT * FROM transport_type WHERE id=?", [$transport_type_id]);
		return $transport_type;
	}

	private function insert_purchase ($user_id, $transport_type_id) {
		global $_conn;

		$purchase_id = $_conn->executeReturnId("INSERT INTO purchase (user_id, transport_id) VALUES (?, ?)", [$user_id, $transport_type_id]);
		if ( !$purchase_id )
			Message::error_message("Purchase couldn't be completed");
		return $purchase_id;
	}

	private function finish_purchase ($purchase_id, $user_id) {
		global $_conn;

		$affected = $_conn->execute("UPDATE cart SET purchase_id=? WHERE user_id=? AND purchase_id IS NULL", [$purchase_id, $user_id]);
		if ( !$affected )
			Message::error_message("Purchase failed. Something went so wrong. Please take a screenshot and contact support ASAP");
		return true;
	}

	public function purchase ($user_id, $transport_type_id) {
		global $_conn;

		$transport_type = $this->get_transport_type($transport_type_id);
		if( !$transport_type )
			Message::error_message('Invalid transport type selected');
		
		$user = new User($user_id);
		$cart = new Cart($user_id);

		$cart_list = $cart->list();

		if ( count($cart_list) == 0 )
			Message::error_message('Nothing to buy');
		
		$cart_price = 0;
		foreach ($cart_list as $key => $value)
			$cart_price += $value['total_price'];
		
		$total_price = $cart_price + $transport_type['price'];

		if( $total_price > $user->cash ){
			Message::error_message(
				"You don't have enough money (\${$user->cash}) to make the purchase (\${$cart_price} of cart + \${$transport_type['price']} of transport)."
			);
		}

		// Easy to hunt-down failed transactions: Inserted into 'purchase' table without any item buyed
		$purchase_id = $this->insert_purchase($user_id, $transport_type_id);

		$status_extract = $user->extract_cash($total_price);		
		if($status_extract === false)
			Message::error_message("We could'nt subtract the money from your account");

		$this->finish_purchase($purchase_id, $user_id);
		
		Message::successful_operation(
			[
				'buyed_items' => $cart_list,
				'cart_price' => $cart_price,
				'transport_price' => $transport_type['price'],
				'total_price' => $total_price,
				'your_cash' => $user->cash
			],
			'Thanks for your purchase'
		);
	}

}