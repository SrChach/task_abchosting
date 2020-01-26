<?php

session_start();

class User {

    public $id = null;
    public $cash = null;
    public $username = null;

    public function __construct ($id = null, $username = null, $pass = null) {
        global $_conn;
        if( !isset($_conn) )
            die( json_encode(['error' => 'Cannot connect to database']) );

        if ($id == null)
            $user = $this->start_session($username, $pass);
        else
            $user = $this->get_by_user_id($id);

        $this->id = $user['id'];
        $this->cash = $user['cash'];
        $this->username = $user['username'];
    }

    private function start_session($username, $pass) {
        global $_conn;

        $user = $_conn->fetchOne("SELECT * FROM user WHERE username=? AND pass=?", [$username, $pass]);

        if (!$user)
            die( json_encode(['error' => 'User not found']) );

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['cash'] = $user['cash'];

        return $user;
    }

    private function get_by_user_id ($id) {
        global $_conn;

        $user = $_conn->fetchOne("SELECT id, username, cash FROM user WHERE id=?", [$id]);

        if (!$user)
            die( json_encode(['error' => 'User not found']) );

        return $user;
    }

    public function check_cash () {
        global $_conn;
        $data = $_conn->fetchOne('SELECT cash FROM user WHERE id=?', [$this->id]);
        $this->cash = $data['cash'];
        return $cash['cash'];
    }

    public function extract_cash ($quantity = 0) {
        if ($quantity <= 0)
            die( json_encode(['error' => 'Quantity must be numeric, and cannot be less than or equals 0']) );

        global $_conn;
        $this->check_cash();
        if ($quantity > $this->cash)
            die( json_encode(['error' => 'Insufficient cash', 'cash' => $this->cash]) );

        $new_cash = $this->cash - $quantity;
        $affected = $_conn->execute('UPDATE user SET cash=? WHERE id=?', [$new_cash, $this->id]);
        if ($affected != 1)
            return false;

        $this->cash = $new_cash;
        return true;
    }

}