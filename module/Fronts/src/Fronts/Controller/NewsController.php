<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class NewsController extends AbstractActionController {

    protected $News;

    public function getNewsTable() {
        if (!$this->News) {
            $pst = $this->getServiceLocator();
            $this->News = $pst->get('News\Model\NewsTable');
        }
        return $this->News;
    }

    public function indexAction() {
        $this->getlayout();
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/tin-tuc.html">Tin tức</a></span>';
        $this->layout()->setVariable('title_page', $title_page);

        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));

        //seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes('Tin tức - ' . $setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));

        $select = new Select();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $data_news = $this->getNewsTable()->load_news();

        $itemsPerPage = 7;
        $data_news->current();
        $paginator = new Paginator(new paginatorIterator($data_news));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);
        //print_r($paginator);die;

        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
        ));
    }

    public function viewAction() {

        $this->getlayout();
        $alias = $this->params()->fromRoute('alias');
        $detail_news = $this->getNewsTable()->views_news($alias);
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/tin-tuc.html">Tin tức</a></span>
              <span class="navigation-pipe">&nbsp;</span> <span class="navigation_page"><a href="' . WEB_PATH . '/tin-tuc/' . $detail_news['news_alias'] . 'html">' . $detail_news['news_title'] . '</a></span>';
        $this->layout()->setVariable('title_page', $title_page);

        //seo
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        if ($detail_news['seo_title'] != null) {
            $seo_title = $detail_news['seo_title'];
        } else {
            $seo_title = $detail_news['news_title'] . ' - ' . $setting->seo_title;
        }
        if ($detail_news['seo_keyword'] != null) {
            $seo_keyword = $detail_news['seo_keyword'];
        } else {
            $seo_keyword = $setting->seo_keyword;
        }
        if ($detail_news['seo_description'] != null) {
            $seo_description = $detail_news['seo_description'];
        } else {
            $seo_description = $setting->seo_description;
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($seo_description));
        $renderer->headMeta()->setProperty('og:title', $detail_news['news_title']);
        $renderer->headMeta()->setProperty('og:site_name', $setting->seo_title);
        $renderer->headMeta()->setProperty('og:url', WEB_PATH . "/tin-tuc/" . $detail_news["news_alias"] . ".html");
        $renderer->headMeta()->setProperty('og:image', WEB_IMG . $detail_news["news_img"]);

        $data_random = $this->getNewsTable()->news_random();
        $data_new = $this->getNewsTable()->load_news_index();

        return array(
            'data_detail' => $detail_news,
            'data_random' => $data_random,
            'data_new' => $data_new,
        );
    }

    public function getlayout() {
        $this->layout('layoutitem');
        $show_banner=1;
        $filter='false';
        $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        $product_left = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'productleft',));
        $img_product = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'loadimg',));
        $data_banner = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'banner',));
        $acticre = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'acticre',));
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        //bo loc san pham
         $brand = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'brand',));
        $xuatxu = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'xuatxu',));
        $chatlieu = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'chatlieu',));
        
        $this->layout()->setVariable('filter', $filter);
        $this->layout()->setVariable('show_banner', $show_banner);
        $this->layout()->setVariable('data_cat', $data_cat);
        $this->layout()->setVariable('data_parent', $data_parent);
        $this->layout()->setVariable('product_left', $product_left);
        $this->layout()->setVariable('img_product', $img_product);
        $this->layout()->setVariable('data_banner', $data_banner);
        $this->layout()->setVariable('acticre', $acticre);
        $this->layout()->setVariable('setting', $setting);
        $this->layout()->setVariable('setting', $setting);
        $this->layout()->setVariable('brand', $brand);
        $this->layout()->setVariable('xuatxu', $xuatxu);
        $this->layout()->setVariable('chatlieu', $chatlieu);
    }

}

?>