<?php

namespace Invoice\Model;

class Oderdetail {
    public $id;
    public $id_order;
    public $id_product;
    public $quantity;
    public $price;     
 
    function exchangeArray($data) 
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->id_order = (isset($data['id_order'])) ? $data['id_order'] : null;
         $this->id_product = (isset($data['id_product'])) ? $data['id_product'] : null;
        $this->quantity = (isset($data['quantity'])) ? $data['quantity'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
        
       
    }
     public function getdata() {
        $data = array();        
        $data['id_order'] = $this->id_order;       
        $data['id_product'] = $this->id_product;
        $data['quantity'] = $this->quantity;            
        $data['price'] = $this->price;       
        return $data;
    }
}