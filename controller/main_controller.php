<?php

header('Content-type: Application/json');
if( !isset($_GET['option']) )
    die( json_encode(['error' => 'option is required']) );
if ( !in_array($_GET['option'], ['authenticate', 'rest_cash']) )
    die( json_encode(['error' => 'Method not available']) );

require '../model/Connection.php';
require '../model/User.php';

$_conn = new Connection();

switch ($_GET['option']) {
    case 'authenticate':
        if( !isset($_POST['username'], $_POST['pass']) )
            die( json_encode(['error' => 'Username and password are required']) );
        $user = new User(null, $_POST['username'], $_POST['pass']);

        echo json_encode(['data' => $user]);
        break;
    
    case 'rest_cash':
        if ( !isset($_SESSION['user_id']) )
            die( json_encode(['error' => 'Session not started']) );

        if ( !isset($_POST['quantity']) )
            die( json_encode(['error' => 'Quantity to rest is required']) );

        $user = new User($_SESSION['user_id']);

        $status = $user->extract_cash($_POST['quantity']);

        if ($status == false)
            die( json_encode(['error' => 'Something went wrong. Cannot extract the cash from user']) );    
        
        echo json_encode([
            'data' => [
                'status' => true,
                'updated_cash' => $user->cash
            ]
        ]);
        break;
}