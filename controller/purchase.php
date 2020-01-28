<?php

	header('Content-type: Application/json');

	if (session_status() == PHP_SESSION_NONE)
	    session_start();

	require_once dirname(__FILE__) . '/../model/Connection.php';
	require_once dirname(__FILE__) . '/../model/Purchase.php';

	$_conn = new Connection();

	if( !isset($_GET['option']) )
		Message::error_message('option is required');

	switch ($_GET['option']) {
		case 'list_transport_types':
			$purchase = new Purchase();
			$transport_types = $purchase->list_transport_types();
			Message::successful_operation($transport_types);
			break;

		case 'purchase':
			if ( !isset($_SESSION['user_id']) )
				Message::error_message('Session not started', 1);

			if(  !isset($_POST['transport_type_id']) )
				Message::error_message('transport type is required');

			$purchase = new Purchase();
			$transport_types = $purchase->purchase($_SESSION['user_id'], $_POST['transport_type_id']);
			Message::successful_operation($transport_types, 'Purchase completed successful');
			break;

		default:
            Message::error_message('Method not available');
            break;

	}