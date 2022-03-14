<?php
namespace Product\Model;
class Product{
    public $id;
    public $code_id;
    public $product_name;
    public $alias;
    public $cat_id;
    public $manufa_id;
    public $quantity;
    public $price;
    public $status_pro;
    public $product_dv;
    public $sales;
    public $price_sales;
    public $status;
    public $product_featured;
    public $product_new;
    public $description;
    public $content;
    public $xuatxu;
    public $chatlieu;
    public $khoanggia;
    public $delete_pro;
    public $seo_title;
    public $seo_keyword;
    public $seo_description;
    public $date;
    public $date_mod;
    public $number_by;
    public $user_post;
    public $user_edit;
    public $show_index;
    public $thumbnail; //dùng để join sang bảng tbl_img khi phân trang
    public $name_cat;		public $parent_id;


    public function exchangeArray($data){
        $this->id=(isset($data['id']))? $data['id']:null;
        $this->code_id=(isset($data['code_id']))? $data['code_id']:null;
        $this->product_name=(isset($data['product_name']))? $data['product_name']:null;
        $this->alias=(isset($data['alias']))? $data['alias']:null;
        $this->cat_id=(isset($data['cat_id']))? $data['cat_id']:null;
        $this->manufa_id=(isset($data['manufa_id']))? $data['manufa_id']:null;
        $this->quantity=(isset($data['quantity']))? $data['quantity']:null;
        $this->price=(isset($data['price']))? $data['price']:null;
        $this->status_pro=(isset($data['status_pro']))? $data['status_pro']:null;
        $this->product_dv=(isset($data['product_dv']))? $data['product_dv']:null;
        $this->sales=(isset($data['sales']))? $data['sales']:null;
        $this->price_sales=(isset($data['price_sales']))? $data['price_sales']:null;
        $this->status=(isset($data['status']))? $data['status']:null;
        $this->product_featured=(isset($data['product_featured']))? $data['product_featured']:null;
        $this->product_new=(isset($data['product_new']))? $data['product_new']:null;
        $this->description=(isset($data['description']))? $data['description']:null;
        $this->content=(isset($data['content']))? $data['content']:null;
        $this->xuatxu=(isset($data['xuatxu']))? $data['xuatxu']:null;
        $this->chatlieu=(isset($data['chatlieu']))? $data['chatlieu']:null;
        $this->khoanggia=(isset($data['khoanggia']))? $data['khoanggia']:null;
        $this->delete_pro=(isset($data['delete_pro']))? $data['delete_pro']:null;
        $this->seo_title=(isset($data['seo_title']))? $data['seo_title']:null;
        $this->seo_keyword=(isset($data['seo_keyword']))? $data['seo_keyword']:null;
        $this->seo_description=(isset($data['seo_description']))? $data['seo_description']:null;     
        $this->date=(isset($data['date']))? $data['date']:null;
        $this->date_mod=(isset($data['date_mod']))? $data['date_mod']:null;
        $this->number_by=(isset($data['number_by']))? $data['number_by']:null;
        $this->user_post=(isset($data['user_post']))? $data['user_post']:null;     
        $this->user_edit=(isset($data['user_edit']))? $data['user_edit']:null;
        $this->show_index=(isset($data['show_index']))? $data['show_index']:null;
         $this->thumbnail=(isset($data['thumbnail']))? $data['thumbnail']:null;//dùng để join sang bảng tbl_img khi phân trang
        $this->name_cat=(isset($data['name_cat']))? $data['name_cat']:null;//dùng để join sang bảng tbl_img khi phân trang				$this->parent_id=(isset($data['parent_id']))? $data['parent_id']:null;
    }
    public function dataproduct(){
        $data=array();
        $data['code_id']=  $this->code_id;
        $data['product_name']=  $this->product_name;
        $data['alias']=  $this->alias;
        $data['cat_id']= $this->cat_id;
        $data['manufa_id']=  $this->manufa_id;
        $data['quantity']=  $this->quantity;
        $data['price']=  $this->price;
        $data['status_pro']=  $this->status_pro;
        $data['product_dv']=  $this->product_dv;
        $data['sales']= $this->sales;
        $data['price_sales']=  $this->price_sales;
        $data['status']=  $this->status;
        $data['product_featured']=  $this->product_featured;
       // $data['product_new']=  $this->product_new;
        $data['description']= $this->description;
        $data['content']=  $this->content;
        $data['xuatxu']=  $this->xuatxu;
        $data['chatlieu']= $this->chatlieu;
        $data['khoanggia']=  $this->khoanggia;
        $data['seo_title']=  $this->seo_title;
        $data['seo_keyword']=  $this->seo_keyword;
        $data['seo_description']=  $this->seo_description;
        $data['date']=  $this->date;
        $data['date_mod']= $this->date_mod;       
        $data['user_post']=  $this->user_post;
        $data['user_edit']=  $this->user_edit;
        $data['show_index']= $this->show_index;
        return $data;
    }
    public function status(){
        $data=array();        
        $data['status']= $this->status;        
        return $data;
    }
    public function status_pro(){
        $data=array();        
        $data['status_pro']= $this->status_pro;        
        return $data;
    }
    public function show_index(){
        $data=array();        
        $data['show_index']= $this->show_index;        
        return $data;
    }
    public function updatecatalog(){
        $data=array();        
        $data['cat_id']= $this->cat_id;        
        return $data;
    }
    public function trash(){
        $data=array();        
        $data['delete_pro']= $this->delete_pro;        
        return $data;
    }
}

