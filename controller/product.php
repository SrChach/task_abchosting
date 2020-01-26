<?php
    header('Content-type: Application/json');

    require_once dirname(__FILE__) . '/../model/Connection.php';
    require_once dirname(__FILE__) . '/../model/Product.php';
    require_once dirname(__FILE__) . '/../model/Message.php';

    if( !isset($_GET['option']) )
        Message::error_message('option is required');

    $_conn = new Connection();

    switch ($_GET['option']) {
        case 'list':
            $products = Product::listAll();
            Message::successful_operation($products);
            break;
        
        default:
            Message::error_message('Method not available');
            break;
    }