<?php

header('Content-type: Application/json');

if (session_status() == PHP_SESSION_NONE)
	session_start();

require_once dirname(__FILE__) . '/../model/Connection.php';
require_once dirname(__FILE__) . '/../model/User.php';
require_once dirname(__FILE__) . '/../model/Message.php';

if( !isset($_GET['option']) )
	Message::error_message('option is required');

$_conn = new Connection();

switch ($_GET['option']) {
	case 'authenticate':
		if( !isset($_POST['username'], $_POST['pass']) )
			Message::error_message('Username and password are required');
		$user = new User(null, $_POST['username'], $_POST['pass']);

		Message::successful_operation($user);
		break;

	case 'rest_cash':
		if ( !isset($_SESSION['user_id']) )
			Message::error_message('Session not started');

		if ( !isset($_POST['quantity']) )
			Message::error_message('Quantity to rest is required');

		$user = new User($_SESSION['user_id']);

		$status = $user->extract_cash($_POST['quantity']);

		if ($status == false)
			Message::error_message('Something went wrong. Cannot extract the cash from user');

		Message::successful_operation([
			'status' => true,
			'updated_cash' => $user->cash
		]);
		break;

	case 'logout':
		if ( !isset($_SESSION['user_id']) )
			Message::error_message("Session doesn't exist");

		session_destroy();
		Message::successful_operation(true, 'Logout done');
		break;

	default:
		Message::error_message('Method not available');
		break;
}