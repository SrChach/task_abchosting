<?php
    header('Content-type: Application/json');

    if (session_status() == PHP_SESSION_NONE)
        session_start();

    require_once dirname(__FILE__) . '/../model/Connection.php';
    require_once dirname(__FILE__) . '/../model/Cart.php';
    require_once dirname(__FILE__) . '/../model/Message.php';

    if( !isset($_GET['option']) )
        Message::error_message('option is required');

    $_conn = new Connection();

    switch ($_GET['option']) {
        case 'list':
            if(!isset($_SESSION['user_id']))
                Message::error_message('Session not started');
            $cart = new Cart($_SESSION['user_id']);
            $cart_list = $cart->list();
            Message::successful_operation($cart_list);
            break;

        case 'add_product':
            if(!isset($_SESSION['user_id']))
                Message::error_message('Session not started');
            
            if ( !isset($_POST['product_id'], $_POST['quantity']) )
                Message::error_message('product_id and quantity are required fields');

            $cart = new Cart($_SESSION['user_id']);
            $product_in_cart = $cart->add_product($_POST['product_id'], $_POST['quantity']);
            if(!$product_in_cart)
                Message::error_message("Product couldn't be added");
            Message::successful_operation($product_in_cart, 'Product added');
            break;

        case 'remove_product':
            if(!isset($_SESSION['user_id']))
                Message::error_message('Session not started');
            
            if ( !isset($_POST['product_id'], $_POST['quantity']) )
                Message::error_message('product_id and quantity are required fields');

            $cart = new Cart($_SESSION['user_id']);

            $removed_product = $cart->remove_product($_POST['product_id'], $_POST['quantity']);
            if(!$removed_product)
                Message::error_message("Product couldn't be removed");
            Message::successful_operation(true, 'Product(s) removed');
            break;

        case 'empty':
            if(!isset($_SESSION['user_id']))
                Message::error_message('Session not started');

            $cart = new Cart($_SESSION['user_id']);
            $status = $cart->empty();
            if($status === false) Message::error_message('Sonething went wrong');
            if(!$status) Message::successful_operation(true, 'Nothing to clear');
            Message::successful_operation(true, 'Cart cleared');
            break;

        default:
            Message::error_message('Method not available');
            break;
    }