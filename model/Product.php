<?php

require_once dirname(__FILE__) . '/../model/Message.php';

class Product {
    public function __construct() {
        global $_conn;
        if ( !isset($_conn) )
            Message::error_message('Cannot connect to database');
    }

    public function listAll() {
        global $_conn;

        $products = $_conn->fetchAll(
            "SELECT product.*, rate.rate_prom AS rate, 0 AS to_cart
                FROM
                    product
                LEFT JOIN
                    (
                        SELECT product_id, ROUND(SUM(rate) / COUNT(*), 1) AS rate_prom
                            FROM rate
                            GROUP BY product_id
                    ) rate
                ON product.id = rate.product_id"
        );
        return $products;
    }

    public function getProduct($product_id){
        global $_conn;

        $product = $_conn->fetchOne("SELECT * FROM product WHERE id=?", [$product_id]);
        return $product;
    }

    private function is_rated ($user_id, $product_id) {
        global $_conn;

        $rated = $_conn->fetchOne("SELECT COUNT(*) is_rated FROM rate WHERE user_id=? AND product_id=?", [$user_id, $product_id]);
        if ( $rated['is_rated'] == 0 )
            return false;
        return true;
    }

    public function rate_list ($user_id) {
        global $_conn;
        
        $products = $_conn->fetchAll("SELECT product.*, rate.rate
                    FROM
                        product
                    JOIN
                        rate ON product.id = rate.product_id
                    WHERE
                        product.id IN (
                            SELECT product_id FROM cart WHERE user_id=? AND purchase_id IS NOT NULL
                        )
                        AND rate.user_id=?
            UNION
                SELECT product.*, NULL AS rate
                    FROM product
                    WHERE
                        id IN (
                            SELECT product_id FROM cart WHERE user_id=? AND purchase_id IS NOT NULL
                        )
                        AND
                        id NOT IN (
                            SELECT product_id FROM rate WHERE user_id =?
                        )
        ", [$user_id, $user_id, $user_id, $user_id]
        );

        return $products;
    }

    public function rate ($user_id, $product_id, $rate){
        global $_conn;

        if ($rate < 1 || $rate > 5)
            Message::error_message('Please select a valid rating');

        $times_buyed = $_conn->fetchOne("SELECT COUNT(*) AS times_buyed 
                FROM cart
                WHERE user_id=? AND product_id=? AND purchase_id IS NOT NULL
            ", [$user_id, $product_id]
        );

        if( $times_buyed['times_buyed'] == 0 )
            Message::error_message("You cannot rate this product until you buy it");

        $is_rated = $this->is_rated($user_id, $product_id);
        if ( $is_rated )
            Message::error_message("You have already rated this product");

        $affected = $_conn->execute("INSERT INTO rate (user_id, product_id, rate) VALUES (?, ?, ?)", [$user_id, $product_id, $rate]);
        if ( !$affected )
            return false;
        return true;
    }
}