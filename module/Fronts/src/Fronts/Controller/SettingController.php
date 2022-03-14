<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class SettingController extends AbstractActionController {

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

    public function aboutAction() {
        $this->getlayout();      
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/gioi-thieu.html">Giới thiệu</a></span>';
        $this->layout()->setVariable('title_page', $title_page);
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        //seo title
         $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes('Giới thiệu - '.$setting->seo_title));
       //end seo title
         return array('data_setting' => $setting);
    }

    public function mapsAction() {       
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/ban-do.html"><span> Bản đồ</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        //seo title
         $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes('Bản đồ - '.$setting->seo_title));
       //end seo title
        
        return array('data_setting' => $setting);
    }
    public function huongdanAction() {        
        $this->getlayout();        
        $data_view = $this->getActicreTable()->load_huongdan();
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/huong-dan.html">Hướng dẫn </a></span>';
        $this->layout()->setVariable('title_page', $title_page);
        
        //seo title
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
         $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes('Hướng dẫn - '.$setting->seo_title));
       //end seo title
        return array('data_view' => $data_view);
    }
     public function dieukhoanAction() {        
        $this->getlayout();        
        $data_view = $this->getActicreTable()->load_dieukhoan();
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/dieu-khoan-giao-dich.html">Điều khoản giao dịch</a></span>';
        $this->layout()->setVariable('title_page', $title_page);
        
        //seo title
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
         $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes('Hướng dẫn - '.$setting->seo_title));
       //end seo title
        return array('data_view' => $data_view);
    }
    public function suportAction() {         
        $this->getlayout();
        $alias = $this->params()->fromRoute('alias');
        $data_view = $this->getActicreTable()->view_acticre($alias);
        $title_page = '<li><a href="' . WEB_PATH . '/suport/' . $data_view['alias'] . '.html"><span>' . $data_view['title'] . '</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        
        //seo title
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
         $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes( $data_view['title'].' - '.$setting->seo_title));
       //end seo title
        return array('data_view' => $data_view);
    }
    public function sitemapAction() {
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/sitemap.html"><span>Sitemap</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
       $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        return array(           
            'data_cat' => $data_cat,
            'data_parent' => $data_parent,
            
        );
    }
    public function getlayout() {
        $this->layout('layoutitem');
        $show_banner =1;
        $filter='false';
        $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        $product_left = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'productleft',));      
        $data_banner = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'banner',));
        $acticre = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'acticre',));
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        $brand = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'brand',));
        $xuatxu = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'xuatxu',));
        $chatlieu = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'chatlieu',));
        //seo       
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));
        //end seo
         $this->layout()->setVariable('filter', $filter);
        $this->layout()->setVariable('show_banner', $show_banner);
        $this->layout()->setVariable('data_cat', $data_cat);
        $this->layout()->setVariable('data_parent', $data_parent);
        $this->layout()->setVariable('product_left', $product_left);       
        $this->layout()->setVariable('data_banner', $data_banner);
        $this->layout()->setVariable('acticre', $acticre);       
         $this->layout()->setVariable('setting', $setting);
        $this->layout()->setVariable('brand', $brand);
        $this->layout()->setVariable('xuatxu', $xuatxu);
        $this->layout()->setVariable('chatlieu', $chatlieu);
    }

}

?>