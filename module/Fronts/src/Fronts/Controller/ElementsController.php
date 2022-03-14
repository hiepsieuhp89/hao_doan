<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Fronts\Model\Download;
use Fronts\Model\View;
use Fronts\Model\Setting;
use Zend\Session\Container;

class ElementsController extends AbstractActionController {

    protected $Category;

    public function getProductcategoryTable() {
        if (!$this->Category) {
            $pst = $this->getServiceLocator();
            $this->Category = $pst->get('Product\Model\ProductTablecategory');
        }
        return $this->Category;
    }
     protected $Product;

    public function getProductTable() {
        if (!$this->Product) {
            $pst = $this->getServiceLocator();
            $this->Product = $pst->get('Product\Model\ProductTable');
        }
        return $this->Product;
    }
    protected $Image;

    public function getImageTable() {
        if (!$this->Image) {
            $pst = $this->getServiceLocator();
            $this->Image = $pst->get('Product\Model\ImageTable');
        }
        return $this->Image;
    }
     protected $Banner;

    public function getBannerTable() {
        if (!$this->Banner) {
            $pst = $this->getServiceLocator();
            $this->Banner = $pst->get('Banner\Model\BannerTable');
        }
        return $this->Banner;
    }
     protected $Setting;
    public function getSettingTable() {
        if (!$this->Setting) {
            $pst = $this->getServiceLocator();
            $this->Setting = $pst->get('Setting\Model\SettingTable');
        }
        return $this->Setting;
    }
     protected $Acticre;
    public function getActicreTable() {
        if (!$this->Acticre) {
            $pst = $this->getServiceLocator();
            $this->Acticre = $pst->get('Setting\Model\ActicreTable');
        }
        return $this->Acticre;
    }
     protected $Manufacture;
    public function getManufactureTable() {
        if (!$this->Manufacture) {
            $pst = $this->getServiceLocator();
            $this->Manufacture = $pst->get('Product\Model\ProductTablemanufacture');
        }
        return $this->Manufacture;
    }
     protected $Xuatxu;
    public function getXuatxuTable() {
        if (!$this->Xuatxu) {
            $pst = $this->getServiceLocator();
            $this->Xuatxu = $pst->get('Product\Model\XuatxuTable');
        }
        return $this->Xuatxu;
    }
    protected $Chatlieu;
    public function getChatlieuTable() {
        if (!$this->Chatlieu) {
            $pst = $this->getServiceLocator();
            $this->Chatlieu = $pst->get('Product\Model\ChatlieuTable');
        }
        return $this->Chatlieu;
    }
    public function catAction() {
        $data_cat =  $this->getProductcategoryTable()->load_category();
       return $data_cat;        
    }
    public function catparentAction(){
        $data_cat =  $this->getProductcategoryTable()->load_category();
        foreach ($data_cat as $key=>$value){
            $id_cat=$value['id'];
            $data_parent[$id_cat]=  $this->getProductcategoryTable()->load_parent($id_cat);
        }
        return @$data_parent;
    }
    public function productleftAction(){
        $data_product=  $this->getProductTable()->product_left();
        return $data_product;
    }
    public function loadimgAction(){
        $data_product=  $this->getProductTable()->product_left();
        foreach ($data_product as $key=>$value){
            $id_product=$value['id'];
            $img_product[$id_product]=  $this->getImageTable()->loadimg_product($id_product);
        }
        return $img_product;
    }
    public function bannerAction(){
        $data=  $this->getBannerTable()->load_banner();
        return $data;
    }
    public function settingAction(){
        $data=  $this->getSettingTable()->datasetting();
        return $data;
    }
     public function acticreAction(){
        $data=  $this->getActicreTable()->listacticre();
        return $data;
    }
    //-------------------------------BỘ LỌC -------------------------
   
   public function brandAction(){        $data=  $this->getManufactureTable()->show_index();        return $data;    }    public function xuatxuAction(){        $data=  $this->getXuatxuTable()->listxuatxu_index();        return $data;    }     public function chatlieuAction(){        $data=  $this->getChatlieuTable()->listchatlieu_index();        return $data;    }
  
    
            
}
?>