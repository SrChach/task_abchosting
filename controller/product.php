<?php
    header('Content-type: Application/json');

    if (session_status() == PHP_SESSION_NONE)
	    session_start();

    require_once dirname(__FILE__) . '/../model/Connection.php';
    require_once dirname(__FILE__) . '/../model/Product.php';
    require_once dirname(__FILE__) . '/../model/Message.php';

    if( !isset($_GET['option']) )
        Message::error_message('option is required');

    $_conn = new Connection();

    switch ($_GET['option']) {
        case 'list':
            $product = new Product();
            $products = $product->listAll();
            Message::successful_operation($products);
            break;

        case 'rate':
            if( !isset($_SESSION['user_id']) )
                Message::error_message('Session not started', 1);

            if( !isset($_POST['product_id'], $_POST['rate']) )
                Message::error_message('rate and product_id fields are required');

            $product = new Product();
            $is_rated = $product->rate($_SESSION['user_id'], $_POST['product_id'], $_POST['rate']);

            if ( !$is_rated )
                Message::error_message('Something went wrong. Product not rated');
            Message::successful_operation($is_rated, 'Product successfully rated');
            break;

        default:
            Message::error_message('Method not available');
            break;
    }