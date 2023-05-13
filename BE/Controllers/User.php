<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login() {  
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => md5($this->request->getVar('password'))
        ];

        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM logintable LT where 
            LT.password = '{$data['password']}'
        and LT.username = '{$data['username']}'"); 
    
        $row = $query->getRowArray();
        if (isset($row)) {
            if ($data['username'] == $row['username'] && 
            $data['password'] == $row['password']){
                $token = bin2hex(random_bytes(16));
                $currentTime = microtime(true);
                $expirationTime = strtotime("+3 minute");
                $responseData = ['token' => $token,
                'expirationTime' => $expirationTime];
                return $this->response->setJSON($responseData);
            }
        }else{
            $responseData = ['token' => -1];
            return $this->response->setJSON($responseData);
        }
    }

    public function signup(){
        $password = $this->request->getVar('password');
        $user = $this->request->getVar('username');
        $obj = [
            'id' => NULL,
            'username' => $user,
            'password' => md5($password)
        ];

        $table = 'logintable';
        $db = \Config\Database::connect();

        $db->table($table)->insert($obj);
    }

    public function getdata(){
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM datatable"); 
        $rows = $query->getRowArray();
        return $this->response->setJSON($query->resultArray);
    }

    public function editdata(){
        $data = [
            'id'=> $this->request->getVar('id'),
            'name' => $this->request->getVar('name'),
            'price' => $this->request->getVar('price'),
            'description' => $this->request->getVar('description'),
        ];
        $db = \Config\Database::connect();
        $builder = $db->table('datatable');
        $builder->where('id', $data['id']);

        if (!empty($data['name'])) {
            $builder->set('name', $data['name']);
        } 
        if (!empty($data['price'])) {
            $builder->set('price', $data['price']);
        } 
        if (!empty($data['description'])) {
            $builder->set('description', $data['description']);
        } 

        $builder->update();

    }

    public function deletedata(){

        $request = service('request');

        $data = $request->getJSON(true);
        $id = $data['id'];
        $db = \Config\Database::connect();

        $db->table('datatable')
            ->where('id', $id)
            ->delete();

    }

    public function adddata(){
        $data = [
            'id'=> NULL,
            'name' => $this->request->getVar('name'),
            'price' => $this->request->getVar('price'),
            'description' => $this->request->getVar('description'),
        ];
        $db = \Config\Database::connect();
        $builder = $db->table('datatable');
        $builder->insert($data);
    }
}