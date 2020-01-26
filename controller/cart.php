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
            $cart = Cart::list($_SESSION['user_id']);
            Message::successful_operation($cart);
            break;

        case 'add_product':
            if(!isset($_SESSION['user_id']))
                Message::error_message('Session not started');
            
            if ( !isset($_POST['product_id'], $_POST['quantity']) )
                Message::error_message('product_id and quantity are required fields');

            $product_in_cart = Cart::add_product($_SESSION['user_id'], $_POST['product_id'], $_POST['quantity']);
            Message::successful_operation($product_in_cart);
            break;

        default:
            Message::error_message('Method not available');
            break;
    }