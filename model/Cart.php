<?php

require_once dirname(__FILE__) . '/Message.php';

class Cart {

    public function __construct () {
        
    }

    public function list ($user_id) {
        global $_conn;
        if( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $cart = $_conn->fetchAll("SELECT cart.id AS cart_id, product.id AS product_id, product.name AS product,
                product.price AS single_price, cart.quantity, (cart.quantity * product.price) as total_price 
            FROM
                cart
            JOIN
                product ON cart.product_id = product.id
            WHERE user_id=? AND purchase_id IS NULL", [$user_id]);
        return $cart;
    }

    private function exists ($user_id, $product_id) {
        global $_conn;
        if( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $product = $_conn->fetchOne("SELECT * FROM cart WHERE user_id=? AND product_id=? AND purchase_id IS NULL", [$user_id, $product_id]);
        return $product;
    }

    public function add_product ($user_id, $product_id, $quantity) {
        global $_conn;

        if($quantity <= 0)
            Message::error_message('Quantity cannot be equals or less than 0');

        $in_cart = Self::exists($user_id, $product_id);

        if($in_cart == null)
            $affected = $_conn->execute("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)", [$user_id, $product_id, $quantity]);
        else{
            $new_quantity = $in_cart['quantity'] + $quantity;
            $affected = Self::update_quantity($new_quantity, $in_cart['id']);
        }

        return $affected;
    }

    public function remove_product ($user_id, $product_id, $quantity) {
        global $_conn;

        if($quantity <= 0)
            Message::error_message('Quantity cannot be equals or less than 0');

        $in_cart = Self::exists($user_id, $product_id);
        if(!$in_cart)
            Message::error_message('This product does not exists in your cart');

        if($quantity < $in_cart['quantity']){
            $new_quantity = $in_cart['quantity'] - $quantity;
            $affected = Self::update_quantity($new_quantity, $in_cart['id']);
        } else
            $affected = $_conn->exec("DELETE FROM cart WHERE id = {$in_cart['id']}");

        return $affected;
    }

    private function update_quantity ($quantity, $cart_id) {
        global $_conn;
        $affected = $_conn->execute("UPDATE cart SET quantity=? WHERE id=?", [$quantity, $cart_id]);
        return $affected;
    }

}