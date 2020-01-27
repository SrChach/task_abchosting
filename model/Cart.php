<?php

require_once dirname(__FILE__) . '/Message.php';

class Cart {

    public $user_id = null;

    public function __construct ($user_id) {
        global $_conn;
        if( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $this->user_id = $user_id;
    }

    public function list () {
        global $_conn;

        $cart = $_conn->fetchAll("SELECT cart.id AS cart_id, product.id AS product_id, product.name AS product,
                product.price AS single_price, cart.quantity, (cart.quantity * product.price) as total_price 
            FROM
                cart
            JOIN
                product ON cart.product_id = product.id
            WHERE user_id=? AND purchase_id IS NULL", [$this->user_id]);
        return $cart;
    }

    private function exists ($product_id) {
        global $_conn;
        if( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $product = $_conn->fetchOne("SELECT * FROM cart WHERE user_id=? AND product_id=? AND purchase_id IS NULL", [$this->user_id, $product_id]);
        return $product;
    }

    public function add_product ($product_id, $quantity) {
        global $_conn;

        if($quantity < 1)
            Message::error_message('Quantity cannot be equals or less than 1');

        $in_cart = $this->exists($product_id);

        if($in_cart == null)
            $affected = $_conn->execute("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)", [$this->user_id, $product_id, $quantity]);
        else{
            $new_quantity = $in_cart['quantity'] + $quantity;
            $affected = $this->update_quantity($new_quantity, $in_cart['id']);
        }

        return $affected;
    }

    public function remove_product ($product_id, $quantity) {
        global $_conn;

        if($quantity < 1)
            Message::error_message('Quantity cannot be equals or less than 1');

        $in_cart = $this->exists($product_id);
        if(!$in_cart)
            Message::error_message('This product does not exists in your cart');

        if($quantity < $in_cart['quantity']){
            $new_quantity = $in_cart['quantity'] - $quantity;
            $affected = $this->update_quantity($new_quantity, $in_cart['id']);
        } else
            $affected = $_conn->exec("DELETE FROM cart WHERE id = {$in_cart['id']}");

        return $affected;
    }

    private function update_quantity ($quantity, $cart_id) {
        global $_conn;
        $affected = $_conn->execute("UPDATE cart SET quantity=? WHERE id=?", [$quantity, $cart_id]);
        return $affected;
    }

    public function empty () {
        global $_conn;

        if( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $affected = $_conn->execute("DELETE FROM cart WHERE purchase_id IS NULL and user_id=?", [$this->user_id]);
        return $affected;
    }

}