<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController {

     protected $Slide;
    public function getSlideTable() {
        if (!$this->Slide) {
            $pst = $this->getServiceLocator();
            $this->Slide = $pst->get('Slideshow\Model\SlideTable');
        }
        return $this->Slide;
    }

    protected $Banner;
    public function getBannerTable() {
        if (!$this->Banner) {
            $pst = $this->getServiceLocator();
            $this->Banner = $pst->get('Banner\Model\BannerTable');
        }
        return $this->Banner;
    }

    protected $Manufacture;
    public function getManufactureTable() {
        if (!$this->Manufacture) {
            $pst = $this->getServiceLocator();
            $this->Manufacture = $pst->get('Product\Model\ProductTablemanufacture');
        }
        return $this->Manufacture;
    }

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

    public function indexAction() {		
        $this->layout('home');
        //load dư liệu vào input search
        $data_search =  $this->getProductTable()->load_productsearch();      
        $product_search = new Container('productsearch');
        $product_search->arrayproduct=$data_search;
        // end load
        
        //load email hệ thống
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        $session_email = new Container('emailsystem');
        $session_email->email_admin =$setting->email_admin;
        $session_email->email_customer =$setting->email_customer;
        $session_email->email_system =$setting->email_system;
        $session_email->pass_system =$setting->pass_system;
        
        $data_slide = $this->getSlideTable()->load_slideshow();
        $data_cattegory_index =  $this->getProductcategoryTable()->load_category_index();
        
        foreach ($data_cattegory_index as $key => $value) {
            $id_cat = $value['id'];
             $list_cat = $this->getProductcategoryTable()->getallMenu($id_cat); //danh sách các id của danh mục con
            $data_product[$id_cat] = $this->getProductTable()->load_product_index($list_cat);           
        }
     
        $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        $product_left = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'productleft',));       
        $acticre = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'acticre',));
        
        //seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));
        //end seo
        //print_r($setting);
        $this->layout()->setVariable('acticre', $acticre);
        $this->layout()->setVariable('setting', $setting);
         $this->layout()->setVariable('data_cat', $data_cat);
        $this->layout()->setVariable('data_parent', $data_parent);
        return array(
            'data_slide' => $data_slide,
            'data_cattegory_index'=>$data_cattegory_index,
            'data_product' => @$data_product,           
        );
    }

    
}

?>