<?php

    class Message {
        public function __construct () {}

        public function error_message ($message = 'Somethig went wrong', $error_code = 0) {
            echo json_encode( ['error' => $message, 'error_code' => $error_code] );
            die();
        }

        public function successful_operation ($data = [], $message = null) {
            echo json_encode (
                [
                    'message' => $message,
                    'data' => $data
                ]
            );
            die();
        }
    }