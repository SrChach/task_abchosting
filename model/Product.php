<?php

require_once dirname(__FILE__) . '/../model/Message.php';

class Product {
    public function __construct() {}

    public function listAll() {
        global $_conn;
        if ( !isset($_conn) )
            Message::error_message('Cannot connect to database');

        $products = $_conn->fetchAll('SELECT * FROM product');
        return $products;
    }
}