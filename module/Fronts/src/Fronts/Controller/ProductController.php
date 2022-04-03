<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class ProductController extends AbstractActionController {

    protected $Image;

    public function getImageTable() {
        if (!$this->Image) {
            $pst = $this->getServiceLocator();
            $this->Image = $pst->get('Product\Model\ImageTable');
        }
        return $this->Image;
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

    protected $Manufacture;

    public function getManufactureTable() {
        if (!$this->Manufacture) {
            $pst = $this->getServiceLocator();
            $this->Manufacture = $pst->get('Product\Model\ProductTablemanufacture');
        }
        return $this->Manufacture;
    }

    public function indexAction() {
        $this->getlayout();
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/san-pham.html">Sản phẩm</a></span>';
        $this->layout()->setVariable('title_page', $title_page);

        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));

        //seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));


        $select = new Select();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $data_product = $this->getProductTable()->show_product();

        $itemsPerPage = 20;
        $data_product->current();
        $paginator = new Paginator(new paginatorIterator($data_product));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);

       
        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
        ));
    }

    public function categoryproductAction() {
        $this->getlayout();

        $alias = $this->params()->fromRoute('alias', 0);
        $session_url_cat = new Container('urlcat');
        $session_url_cat->aliascat = $alias;
        $data_cat = $this->getProductcategoryTable()->categorydetail_alias($alias);
        // print_r($data_cat);die;
        $title_page = '<span class="navigation_page"><a href="' . WEB_PATH . '/san-pham.html">Sản phẩm</a></span>'
                . '<span class="navigation-pipe">&nbsp;</span> <span class="navigation_page"><a href="' . WEB_PATH . '/san-pham/danh-muc/' . $data_cat['alias'] . '.html">' . $data_cat['name'] . '</a></span>';
        $this->layout()->setVariable('title_page', $title_page);

         //seo
        
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        if ($data_cat['seo_title'] != null) {
            $seo_title = $data_cat['seo_title'];
        } else {
            $seo_title = $data_cat['name'] . ' - ' . $setting->seo_title;
        }
        if ($data_cat['seo_keyword'] != null) {
            $seo_keyword = $data_cat['seo_keyword'];
        } else {
            $seo_keyword = $setting->seo_keyword;
        }
        if ($data_cat['seo_description'] != null) {
            $seo_description = $data_cat['seo_description'];
        } else {
            $seo_description = $setting->seo_description;
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($seo_description));

        $list_cat = $this->getProductcategoryTable()->getallMenu($data_cat['id']); //danh sách các id của danh mục con
        //print_r($list_cat);
        $select = new Select();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $data_product_cat = $this->getProductTable()->show_product_cat($list_cat);
        // print_r($data_product_cat);die;
        $itemsPerPage = 20;
        $data_product_cat->current();
        $paginator = new Paginator(new paginatorIterator($data_product_cat));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);

       

        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
            'data_cat' => $data_cat
        ));
    }

    public function productmanufaAction() {
        $this->getlayout();
        $alias = $this->params()->fromRoute('alias', 0);
        $session_url_manu = new Container('urlmanu');
        $session_url_manu->aliasmanu = $alias;
        $data_manu = $this->getManufactureTable()->mannu_detail_alias($alias);
        // print_r($data_cat);die;
        $title_page = '<li><a href="' . WEB_PATH . '/san-pham.html"><span>Sản phẩm</span></a></li>'
                . '<li><a href=""><span> Hãng sản xuất</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
//seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($data_manu['seo_title']));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($data_manu['seo_keyword']));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($data_manu['seo_keyword']));
        $renderer->headMeta()->setName('description', stripcslashes($data_manu['seo_description']));


        $select = new Select();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $data_product_manufa = $this->getProductTable()->show_product_manufa($data_manu['id']);
        //print_r($data_product_manufa);die;
        $itemsPerPage = 16;
        $data_product_manufa->current();
        $paginator = new Paginator(new paginatorIterator($data_product_manufa));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);

        foreach ($data_product_manufa as $key => $value) {
            @$id_product = $value->id;
            $img_product[$id_product] = $this->getImageTable()->loadimg_product($id_product);
        }
        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
            'img_product' => @$img_product,
            'data_manu' => $data_manu
        ));
    }

    public function productdetailAction() {		
        $this->layout('layout/layoutdetail.phtml');       
        $show_banner = 1;
        $alias = $this->params()->fromRoute('alias');	
		
        $product_detail = $this->getProductTable()->show_productdetail($alias);		
        $id_cat = $product_detail['cat_id'];
        $product_featured = $this->getProductTable()->product_featured();
        $product_same = $this->getProductTable()->load_product_same($id_cat);

        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        $acticre = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'acticre',));
        $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        $data_banner = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'banner',));
        $this->layout()->setVariable('data_banner', $data_banner);
        $this->layout()->setVariable('show_banner', $show_banner);
        $this->layout()->setVariable('setting', $setting);
        $this->layout()->setVariable('acticre', $acticre);
        $this->layout()->setVariable('data_cat', $data_cat);
        $this->layout()->setVariable('data_parent', $data_parent);
        $img_product = $this->getImageTable()->listimg($product_detail['id']);


        //seo

        if ($product_detail['seo_title'] != null) {
            $seo_title = $product_detail['seo_title'];
        } else {
            $seo_title = $product_detail['product_name'] . ' - ' . $setting->seo_title;
        }
        if ($product_detail['seo_keyword'] != null) {
            $seo_keyword = $product_detail['seo_keyword'];
        } else {
            $seo_keyword = $setting->seo_keyword;
        }
        if ($product_detail['seo_description'] != null) {
            $seo_description = $product_detail['seo_description'];
        } else {
		$seo_description = $setting->seo_description;
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($seo_description));
		$renderer->headMeta()->setProperty('fb:app_id', '544101992437000');
		$renderer->headMeta()->setProperty('fb:admins', '100003200734938');
        $renderer->headMeta()->setProperty('og:title', $product_detail['product_name']);
        $renderer->headMeta()->setProperty('og:site_name', $setting->seo_title);
        $renderer->headMeta()->setProperty('og:url', WEB_PATH . "/san-pham/" . $product_detail["alias"] . ".html");
        $renderer->headMeta()->setProperty('og:image', WEB_IMG .'images/'.$product_detail["medium"]);
		$renderer->headMeta()->setProperty('og:description', stripcslashes($seo_description));
		//print_r($product_detail);
        return array(
            'product_detail' => $product_detail,
            'img_product' => $img_product,
            'product_same' => $product_same,
            'product_featured' => $product_featured,
            'data_banner' => $data_banner,
                //'all_img' => $all_img,
        );
    }

    public function searchAction() {
        $this->getlayout();
        $title_page = '<li><a href=""><span>Tìm kiếm</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);

        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));

        //seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));

        $_key = addslashes(trim($this->params()->fromQuery('key')));
        $key = @str_replace(',', '', $_key);

        //url phân trang
        $url = @str_replace(' ', '+', $_key);
        $url_key = @str_replace(',', '%2C', $url);
        $session_key = new Container('urlsearch');
        $session_key->key = '?key=' . $url_key;
        $session_key->keyfilter=$key;

        if ($key != null) {
            $select = new Select();
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            $data_search = $this->getProductTable()->search_product($key);
            //print_r($data_search);die;
            $itemsPerPage = 20;
            $data_search->current();
            $paginator = new Paginator(new paginatorIterator($data_search));
            $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($itemsPerPage)
                    ->setPageRange(5);
            return array(
                'page' => $page,
                'paginator' => $paginator,
            );
        }
    }
	 public function searchajaxAction() {
        $_key = $this->params()->fromPost('key');        
       // echo '999999';
      
        if($_key !==null){
        $data_product=  $this->getProductTable()->load_productsearch_ajax($_key);
        /*foreach ($data_product as $key=>$value){
             if ($value['sales'] == 1) {
                 $price_sales = number_format($value['price'] - ($value['price'] * $value['price_sales'] / 100), 0, ',', '.');
             }else{
                 $price_sales= number_format($value['price'], 0, ',', '.');
             }                            
             echo  '<li>
                        <a href="'.WEB_PATH.'/san-pham/'.$value['alias'].'.html">
                        <img src="'.WEB_IMG.$value['thumbnail'].'" width="50" height="50" />    
                        '.$value['product_name'].'
                        <span class="pull-right">Giá: <strong>'.$price_sales.'đ</strong></span>
                        </a>
                    </li>';
        }*/
        echo json_encode($data_product);
        die;
        }else{
            die();
        }
    }

    public function filterAction() {
        $price_range = $_REQUEST['price'];
        $brand = $_REQUEST['brand'];
        $xuatxu = $_REQUEST['xuatxu'];
        $chatlieu = $_REQUEST['chatlieu'];
        $cat = $_REQUEST['cat'];

        $WHERE = array();
        $inner = $w = '';
        if (!empty($price_range)) {
            if (strstr($price_range, ',')) {
                $data1 = explode(',', $price_range);
                $price_array = array();
                foreach ($data1 as $c) {
                    $price_array[] = "khoanggia = $c";
                }
                $WHERE[] = '(' . implode(' OR ', $price_array) . ')';
            } else {
                $WHERE[] = '(khoanggia = ' . $price_range . ')';
            }
        }

        if (!empty($brand)) {
            if (strstr($brand, ',')) {
                $data2 = explode(',', $brand);
                $brand_array = array();
                foreach ($data2 as $c) {
                    $brand_array[] = "manufa_id = $c";
                }
                $WHERE[] = '(' . implode(' OR ', $brand_array) . ')';
            } else {
                $WHERE[] = '(manufa_id = ' . $brand . ')';
            }
        }

        if (!empty($xuatxu)) {
            if (strstr($xuatxu, ',')) {
                $data3 = explode(',', $xuatxu);
                $xuatxu_array = array();
                foreach ($data3 as $c) {
                    $xuatxu_array[] = "xuatxu = $c";
                }
                $WHERE[] = '(' . implode(' OR ', $xuatxu_array) . ')';
            } else {
                $WHERE[] = '(xuatxu = ' . $xuatxu . ')';
            }
        }
        if (!empty($chatlieu)) {
            if (strstr($chatlieu, ',')) {
                $data3 = explode(',', $chatlieu);
                $chatlieu_array = array();
                foreach ($data3 as $c) {
                    $chatlieu_array[] = "chatlieu = $c";
                }
                $WHERE[] = '(' . implode(' OR ', $chatlieu_array) . ')';
            } else {
                $WHERE[] = '(chatlieu = ' . $chatlieu . ')';
            }
        }

        $w = implode(' AND ', $WHERE);
        if (!empty($w)) {
            //bộ lọc trang sản phẩm
            if ($cat == 'false') {
                $data_product = $this->getProductTable()->list_product_filter($w);
            } elseif ($cat == 'search') {// Bộ lọc sản phẩm trang tìm kiếm
                 $session_key = new Container('urlsearch');     
                 $key= $session_key->keyfilter;                
                $data_product = $this->getProductTable()->list_product_filter_search($key, $w);
            } else {// Bộ lọc sản phẩm trang danh mục
                $data_cat = $this->getProductcategoryTable()->categorydetail_alias($cat);
                $list_cat = $this->getProductcategoryTable()->getallMenu($data_cat['id']);
                $data_product = $this->getProductTable()->list_product_filter_cat($list_cat, $w);
            }
            foreach ($data_product as $key => $value) {
                if ($value['sales'] == 1) {
                     $price_sales = ($value['price'] - $value['price_sales'])*100/$value['price'];
                    $sales_off = '<div class="price-percent-reduction2">-' . round($price_sales) . '% OFF</div>';                   
                    $price = '<span class="price product-price">' . number_format($value['price_sales'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>
                      <span class="price old-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                } else {
                    $sales_off = '';
                    $price_sales = $value['price'];
                    $price = '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                }
                echo '<li class = "col-xs-6 col-sm-3">
            <div class = "product-container">
            <div class = "left-block">
            <a href = "' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html">
            <img class = "img-responsive" alt = "Xây dựng vườn đứng" src = "' . WEB_IMG .'images/'. $value['thumbnail'] . '" />
            </a>
            ' . $sales_off . '
            <!--<div class = "add-to-cart">
            <a title = "Đặt Hàng" onclick="addcart('.$value['id'].',0);" href = "javascript:void(0);">Đặt Hàng</a>
            </div>-->
            </div>
            <div class = "right-block">
            <h3 class = "product-name"><a href = "' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html">' . $value['product_name'] . '</a></h3>

            <div class = "content_price">
           ' . $price . '
            </div>

            </div>
            </div>
            </li>';
            }
            die;
        } else {
            echo '102';
            die;
        }
    }

    public function getlayout() {
        $this->layout('layoutitem');
        $show_banner = 0;
        $filter ='true';
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