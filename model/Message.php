<?php

    class Message {
        public function __construct () {}

        public function error_message ($message = 'Somethig went wrong') {
            echo json_encode( ['error' => $message] );
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