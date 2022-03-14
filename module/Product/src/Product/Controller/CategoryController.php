<?php

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Productcategory;
use Product\Model\Product;
use Product\Model\Image;
use Product\Model\Utility;
use Product\Model\Resize;
use Zend\Session\Container;

class CategoryController extends AbstractActionController {

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

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getProductcategoryTable()->listcategory();
        foreach ($data as $key => $value) {
            $parent = $value['parent'];
            $parent_name[$parent] = $this->getProductcategoryTable()->getparent_name($parent);
        }
		 //Log File
				  $witelog = new Utility();
                $text = 'Xem Danh Mục sản phẩm';
                $witelog->witelog($text);
                //--------------------------
		
        return array('data' => $data, 'parent_name' => @$parent_name);
    }

    public function addAction() {
        $Uty = new Utility;
        $this->layout('layout/user.phtml');
        $data_cat = $this->getProductcategoryTable()->listcategory();
        foreach ($data_cat as $key=>$value){
            $id_cat = $value['id'];
            $data_parent[$id_cat]=  $this->getProductcategoryTable()->load_parent_admin($id_cat);
        }

        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));
            $parent = addslashes(trim($this->params()->fromPost('parent')));
            $show_index = addslashes(trim($this->params()->fromPost('show_index')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $user = addslashes(trim($this->params()->fromPost('user')));
            $phone = addslashes(trim($this->params()->fromPost('phone')));
            $background = addslashes(trim($this->params()->fromPost('background')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
            $checkname = $this->getProductcategoryTable()->checkname($name);
            if ($checkname) {
                //ẢNH MÔ TẢ
                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgCategory");
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];

                if ($filename == null) {
                    $img_db = 'images/imgdefault.jpg';
                } else {
                    $ext = substr(strrchr($filename, '.'), 1);
                    $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;

                    /*copy($tmpimg, $dirpath . '/' . $fileupload);
                    $Uty->load($tmpimg);
                    $Uty->resize(386, 572);
                    $Uty->save($dirpath . '/' . $fileupload);*/ // ảnh thumb
                   move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(400, 647, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);

                    $img_db = 'imgCategory/' . $fileupload;
                }

                //Icon danh mục
                $tmpicon = $_FILES["icon"]["tmp_name"];
                $fileicon = $_FILES["icon"]["name"];

                if ($fileicon == null) {
                    $icon_db = 'images/icondefault.png';
                } else {
                    $ext_icon = substr(strrchr($fileicon, '.'), 1);
                    $file_icon_upload = substr(base64_encode(time($fileicon)), 0, -1) . time() . '.' . $ext_icon;

                    copy($tmpicon, $dirpath . '/icon/' . $file_icon_upload);
                    $icon_db = 'imgCategory/icon/' . $file_icon_upload;
                }

                $data_cat = array(
                    'name' => $name,
                    'parent' => $parent,
                    'alias' => $alias,
                    'description' => $description,
                    'status' => $status,
                    'show_index' => $show_index,
                    'date' => $date,
                    'seo_title' => $seo_title,
                    'seo_keyword' => $seo_keyword,
                    'seo_description' => $seo_description,
                    'user' => $user,
                    'phone' => $phone,
                    'img' => $img_db,
                    'icon' => $icon_db,
                    'background' => $background,
                );
                $obj_cat = new Productcategory();
                $obj_cat->exchangeArray($data_cat);
                $this->getProductcategoryTable()->addcategory($obj_cat);

                $data = $this->getProductcategoryTable()->listcatparent();
                $alert = '<p class="bg-success">Thêm danh mục thành công</p>';
				
				//Log File
				  $witelog = new Utility();
                $text = 'Thêm mới Danh Mục sản phẩm - '.$name;
                $witelog->witelog($text);
                //--------------------------
                return array('data' => $data, 'alert' => $alert);
            } else {
                $alert = '<p class="bg-warning">Tên danh mục này đã tồn tại không thể thêm được</p>';
                return array('data' => $data, 'alert' => $alert);
            }
        }
        return array(
            'data_cat' => $data_cat,
          'data_parent'=>$data_parent,
        );
    }

    public function editAction() {
        $Uty = new Utility;
        $this->layout('layout/user.phtml');
        $id = addslashes($this->params()->fromRoute('id', 0));     
        $data_detail = $this->getProductcategoryTable()->categorydetail($id);
        
         $data_cat = $this->getProductcategoryTable()->listcategory();
        foreach ($data_cat as $key=>$value){
            $id_cat = $value['id'];
            $data_parent[$id_cat]=  $this->getProductcategoryTable()->load_parent_admin($id_cat);
        }
        
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));
            $parent = addslashes(trim($this->params()->fromPost('parent')));
            $show_index = addslashes(trim($this->params()->fromPost('show_index')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $user = addslashes(trim($this->params()->fromPost('user')));
            $phone = addslashes(trim($this->params()->fromPost('phone')));
            $background = addslashes(trim($this->params()->fromPost('background')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");

            //ẢNH MÔ TẢ
            $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgCategory");
            $tmpimg = $_FILES["img"]["tmp_name"];
            $filename = $_FILES["img"]["name"];

            //Icon
            $tmpicon = $_FILES["icon"]["tmp_name"];
            $fileicon = $_FILES["icon"]["name"];


            $checkname = $this->getProductcategoryTable()->checkname($name);
            if ($name == $data_detail['name']) { //  nếu không sửa tên vân sửa được các thành phần khác
                //ẢNH MÔ TẢ
                if ($filename == null) {
                    $img_db = $data_detail['img'];
                } else {
                    if ($data_detail['img'] != 'images/imgdefault.jpg') {
                        $img_old = $data_detail['img'];
                        $url_img = WEB_MEDIA . '/media/' . $img_old;
                        unlink($url_img); // Xóa ảnh cũ nếu chọn ảnh mới;
                    }
                    $ext = substr(strrchr($filename, '.'), 1);
                    $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;

                   move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(400, 647, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);

                    $img_db = 'imgCategory/' . $fileupload;
                }

                //Icon danh mục                                
                if ($fileicon == null) {
                    $icon_db = $data_detail['icon'];
                } else {
                    if ($data_detail['icon'] != 'images/icondefault.png') {
                        $icon_old = $data_detail['icon'];
                        $url_icon = WEB_MEDIA . '/media/' . $icon_old;
                        unlink($url_icon); // Xóa ảnh cũ nếu chọn ảnh mới;
                    }
                    $ext_icon = substr(strrchr($fileicon, '.'), 1);
                    $file_icon_upload = substr(base64_encode(time($fileicon)), 0, -1) . time() . '.' . $ext_icon;

                    copy($tmpicon, $dirpath . '/icon/' . $file_icon_upload);
                    $icon_db = 'imgCategory/icon/' . $file_icon_upload;
                }
                $data_cat = array(
                    'name' => $name,
                    'parent' => $parent,
                    'alias' => $alias,
                    'description' => $description,
                    'status' => $status,
                    'show_index' => $show_index,
                    'date' => $date,
                    'seo_title' => $seo_title,
                    'seo_keyword' => $seo_keyword,
                    'seo_description' => $seo_description,
                    'user' => $user,
                    'phone' => $phone,
                    'img' => $img_db,
                    'icon' => $icon_db,
                    'background' => $background,
                );

                $obj_cat = new Productcategory();
                $obj_cat->exchangeArray($data_cat);
                $this->getProductcategoryTable()->updatcategory($id, $obj_cat);

                $data_detail = $this->getProductcategoryTable()->categorydetail($id);
                $alert = '<p class="bg-success">Sửa danh mục thành công</p>';
				
				//Log File
				$witelog = new Utility();
                $text = 'Sửa Danh Mục sản phẩm - '.$name;
                $witelog->witelog($text);
                //--------------------------
				
                return array(
                    'data_detail' => $data_detail,
                     'data_cat' => $data_cat,
                     'data_parent'=>$data_parent,
                    'alert' => $alert
                );
            } else {
                if ($checkname) { // nếu sửa tên thì kiểm tra tên mới có bị trùng không;
                    //ẢNH MÔ TẢ               
                    if ($filename == null) {
                        $img_db = $data_detail['img'];
                    } else {
                        if ($data_detail['img'] != 'images/imgdefault.jpg') {
                            $img_old = $data_detail['img'];
                            $url_img = WEB_MEDIA . '/media/' . $img_old;
                            unlink($url_img); // Xóa ảnh cũ nếu chọn ảnh mới;
                        }
                        $ext = substr(strrchr($filename, '.'), 1);
                        $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;

                        copy($tmpimg, $dirpath . '/' . $fileupload);
                        $Uty->load($tmpimg);
                        $Uty->resize(386, 572);
                        $Uty->save($dirpath . '/' . $fileupload); // ảnh thumb

                        $img_db = 'imgCategory/' . $fileupload;
                    }

                    //Icon danh mục                               
                    if ($fileicon == null) {
                        $icon_db = $data_detail['icon'];
                    } else {
                        if ($data_detail['icon'] != 'images/icondefault.png') {
                            $icon_old = $data_detail['icon'];
                            $url_icon = WEB_MEDIA . '/media/' . $icon_old;
                            unlink($url_icon); // Xóa ảnh cũ nếu chọn ảnh mới;
                        }
                        $ext_icon = substr(strrchr($fileicon, '.'), 1);
                        $file_icon_upload = substr(base64_encode(time($fileicon)), 0, -1) . time() . '.' . $ext_icon;

                        copy($tmpicon, $dirpath . '/icon/' . $file_icon_upload);
                        $icon_db = 'imgCategory/icon/' . $file_icon_upload;
                    }
                    $data_cat = array(
                        'name' => $name,
                        'parent' => $parent,
                        'alias' => $alias,
                        'description' => $description,
                        'status' => $status,
                        'show_index' => $show_index,
                        'date' => $date,
                        'seo_title' => $seo_title,
                        'seo_keyword' => $seo_keyword,
                        'seo_description' => $seo_description,
                        'user' => $user,
                        'phone' => $phone,
                        'img' => $img_db,
                        'icon' => $icon_db,
                    );

                    $obj_cat = new Productcategory();
                    $obj_cat->exchangeArray($data_cat);
                    $this->getProductcategoryTable()->updatcategory($id, $obj_cat);

                    $data_detail = $this->getProductcategoryTable()->categorydetail($id);
                    $alert = '<p class="bg-success">Sửa danh mục thành công</p>';
					
					//Log File
				$witelog = new Utility();
                $text = 'Sửa Danh Mục sản phẩm - '.$name;
                $witelog->witelog($text);
                //--------------------------
                    return array(
                        'data_detail' => $data_detail,
                         'data_cat' => $data_cat,
                        'data_parent'=>$data_parent,
                        'alert' => $alert
                    );
                } else {
                    $alert = '<p class="bg-warning">Tên danh mục này đã tồn tại không thể sửa được</p>';
                    return array(
                        'data_detail' => $data_detail,
                         'data_cat' => $data_cat,
                           'data_parent'=>$data_parent,
                        'alert' => $alert
                    );
                }
            }
        }
        return array(
            'data_detail' => $data_detail,
            'data_cat' => $data_cat,
             'data_parent'=>$data_parent,
        );
    }
     public function showindexAction() {
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $showindex = addslashes(trim($this->params()->fromRoute('show', 0)));
        if ($showindex == 0) {
            $data = array('show_index' => 1);
        } else {
            $showindex = array('show_index' => 0);
        }
        $obj = new Productcategory();
        $obj->exchangeArray($data);
        $this->getProductcategoryTable()->showindex($id, $obj);
        $this->redirect()->toRoute('CategoryProduct');
    }
    
    public function statusAction() {
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $status = addslashes(trim($this->params()->fromRoute('status', 0)));
        if ($status == 0) {
            $data = array('status' => 1);
        } else {
            $data = array('status' => 0);
        }
        $obj = new Productcategory();
        $obj->exchangeArray($data);
        $this->getProductcategoryTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('CategoryProduct');
    }

    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = addslashes(trim($this->params()->fromPost('cat_id')));
        $type = addslashes(trim($this->params()->fromPost('type')));
        $cat_new = addslashes(trim($this->params()->fromPost('cat_new')));

        $data_detail = $this->getProductcategoryTable()->categorydetail($id);
        if ($type == 1) {
            $list_product = $this->getProductTable()->getproduct_cat($id);
            foreach ($list_product as $key_pro => $value_pro) {
                $id_product=$value_pro['id'];
                $data_img = $this->getImageTable()->listimg($id_product);                
                foreach ($data_img as $key => $value) {
                    $url_img = WEB_MEDIA . '/media/' . $value['img'];
                    $url_medium = WEB_MEDIA . '/media/' . $value['medium'];
                    $url_thumb = WEB_MEDIA . '/media/' . $value['thumbnail'];
                    unlink($url_img);
                    unlink($url_medium);
                    unlink($url_thumb);
                }
                $this->getImageTable()->delete_listimg($id_product); // xóa ảnh trong database*/
            }//end for product
            $this->getProductTable()->deleteproduct_cat($id);//xóa sản phảm trang db
        } else {
            //Chuyển danh mục con sang danh mục mới
            $data_parent = array('parent' => $cat_new);
            $obj_parent = new Productcategory();
            $obj_parent->exchangeArray($data_parent);
            $this->getProductcategoryTable()->updateparent($id, $obj_parent);
            
            // chuyển sản phẩm sang danh mục khác
            $data_cat = array('cat_id' => $cat_new);
            $obj_pro = new Product();
            $obj_pro->exchangeArray($data_cat);
            $this->getProductTable()->update_idcatalog($id, $obj_pro);
        }
       
        // xóa dữ liệu catalog
        if ($data_detail['img'] != 'images/imgdefault.jpg') {
            $img_old = $data_detail['img'];
            $url_img = WEB_MEDIA . '/media/' . $img_old;
            unlink($url_img);
        }
        if ($data_detail['icon'] != 'images/icondefault.png') {
            $icon_old = $data_detail['icon'];
            $url_icon = WEB_MEDIA . '/media/' . $icon_old;
            unlink($url_icon);
        }
        $this->getProductcategoryTable()->deletecategory($id);
		
		//Log File
				$witelog = new Utility();
                $text = 'Xóa Danh Mục sản phẩm ID = '.$id ;
                $witelog->witelog($text);
                //--------------------------
				
        $this->redirect()->toRoute('CategoryProduct');
    }
	
	 public function sortmenuAction(){
         $this->layout('layout/user.phtml');
         $data = $this->getProductcategoryTable()->load_category_index();
         $data_menu = $this->getProductcategoryTable()->load_category();
         return array(
             'data'=>$data,
             'data_menu'=>$data_menu   
                 );
    }
    
    public function updatesortAction(){       
        $data = $_REQUEST['data'];
        parse_str($data,$str);
        $menu = $str['item'];
        foreach ($menu as $key=>$value){
            $key=$key+1;
            $data_sort = array('sort' => $key);
            $obj_sort = new Productcategory();
            $obj_sort->exchangeArray($data_sort);
            $this->getProductcategoryTable()->updatesort($value, $obj_sort);
        }
		
		//Log File
				  $witelog = new Utility();
                $text = 'Sắp Xếp Danh Mục trang  chủ';
                $witelog->witelog($text);
                //--------------------------
        print_r('Xuan Dac');
        die();
    }
     public function updatesortmenuAction(){       
        $data = $_REQUEST['data'];
        parse_str($data,$str);
        $menu = $str['menu'];
        foreach ($menu as $key=>$value){
            $key=$key+1;
            $data_sort = array('sort_menu' => $key);
            $obj_sort = new Productcategory();
            $obj_sort->exchangeArray($data_sort);
            $this->getProductcategoryTable()->updatesort_menu($value, $obj_sort);
        }
		//Log File
				  $witelog = new Utility();
                $text = 'Sắp Xếp Danh Mục Menu';
                $witelog->witelog($text);
                //--------------------------
        print_r('Xuan Dac');
        die();
    }

}

?>