<?php

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use News\Model\News;
use Product\Model\Resize;
use Product\Model\Utility;

class IndexController extends AbstractActionController {

    protected $Newscategory;

    public function getNewscategoryTable() {
        if (!$this->Newscategory) {
            $pst = $this->getServiceLocator();
            $this->Newscategory = $pst->get('News\Model\NewsTablecategory');
        }
        return $this->Newscategory;
    }

    protected $News;

    public function getNewsTable() {
        if (!$this->News) {
            $pst = $this->getServiceLocator();
            $this->News = $pst->get('News\Model\NewsTable');
        }
        return $this->News;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getNewsTable()->listnews();
		
		 //Log File
				$witelog = new Utility();
                $text = 'Xem danh sách tin tức';
                $witelog->witelog($text);
         //--------------------------
        return array('data' => $data);
    }

    public function addAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getNewscategoryTable()->listcatparent();
        $Uty = new Utility;
         $session_user = new Container('user');
         $id_user =$session_user->idus;
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('title')));
            $cat_id = addslashes(trim($this->params()->fromPost('cat_id')));
            //$news_fatured = addslashes(trim($this->params()->fromPost('new_featured')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
            $checkname = $this->getNewsTable()->checkname($name);
            if ($checkname) {
                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgNews");
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];
                
                if ($filename == null) {
                   $img_db = 'images/imgdefault.jpg';
                }else{
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;
               
                move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(345, 244, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);
                
                 $img_db = 'imgNews/' . $fileupload;
                }
               
                
                $data_news = array(
                    'news_title' => $name,
                    'news_alias' => $alias,
                    'news_descripion' => $description,
                    'news_contents' => stripslashes($content),
                    'news_status' => $status,
                    //'news_featured' => $news_fatured,
                    'news_img' => $img_db,
                    'news_catid' => $cat_id,
                    'news_mod' => $date,
                    'news_date' => $date,
                    'seo_title' => $seo_title,
                    'seo_keyword' => $seo_keyword,
                    'seo_description' => $seo_description,
                    'id_user'=>$id_user,
                );
                //print_r($data_news);die;
                $obj_cat = new News();
                $obj_cat->exchangeArray($data_news);
                $this->getNewsTable()->addnews($obj_cat);

                $data = $this->getNewscategoryTable()->listcatparent();
                $alert = '<p class="bg-success">Thêm Tin tức thành công</p>';
				
				 //Log File
				$witelog = new Utility();
                $text = 'Thêm Mới tin tức ';
                $witelog->witelog($text);
         //--------------------------
                return array('data' => $data, 'alert' => $alert);
            } else {
                $alert = '<p class="bg-warning">Tin này đã tồn tại không thể thêm được</p>';
                return array('data' => $data, 'alert' => $alert);
            }
        }
        return array('data' => $data);
    }

    public function editAction() {
        $Uty = new Utility;
        $session_user = new Container('user');
         $id_user =$session_user->idus;
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getNewscategoryTable()->listcatparent();
        $data_detail = $this->getNewsTable()->detailnews($id);
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('title')));
            $cat_id = addslashes(trim($this->params()->fromPost('cat_id')));
            //$news_fatured = addslashes(trim($this->params()->fromPost('new_featured')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
            $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgNews");
            $tmpimg = $_FILES["img"]["tmp_name"];
            $filename = $_FILES["img"]["name"];
            $ext = substr(strrchr($filename, '.'), 1);
            $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;
            if ($filename == null) {
                $img = $data_detail['news_img'];
            } else{
                if($data_detail['news_img'] !='images/imgdefault.jpg'){
                $img_old=$data_detail['news_img'];
                $url_img=WEB_MEDIA.'/media/'.$img_old;
                unlink($url_img);// Xóa ảnh cũ nếu chọn ảnh mới;
                }
                $img = 'imgNews/' . $fileupload;          
              move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(345, 244, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);
            }

            $data_news = array(
                'news_title' => $name,
                'news_alias' => $alias,
                'news_descripion' => $description,
                'news_contents' => stripslashes($content),
                'news_status' => $status,
                //'news_featured' => $news_fatured,
                'news_img' => $img,
                'news_catid' => $cat_id,
                'news_mod' => $date,
                'news_date' => $date,
                'seo_title' => $seo_title,
                'seo_keyword' => $seo_keyword,
                'seo_description' => $seo_description,
                'id_user'=>$id_user,
            );
            $checkname = $this->getNewsTable()->checkname($name);
            if ($name == $data_detail['news_title']) {   
                $obj_cat = new News();
                $obj_cat->exchangeArray($data_news);
                $this->getNewsTable()->updatenews($id, $obj_cat);

                $data = $this->getNewscategoryTable()->listcatparent();
                $alert = '<p class="bg-success">SửaTin tức thành công</p>';
				
				//Log File
				$witelog = new Utility();
                $text = 'Sủa tin tức ID = '.$id;
                $witelog->witelog($text);
         //--------------------------
                return array(
                    'data' => $data,
                    'alert' => $alert,
                    'data_detail' => $data_news,);
            }  else {                
            
            if ($checkname) {       
                //print_r($data_news);die;
                $obj_cat = new News();
                $obj_cat->exchangeArray($data_news);
                $this->getNewsTable()->updatenews($id, $obj_cat);

                $data = $this->getNewscategoryTable()->listcatparent();
                $alert = '<p class="bg-success">Sửa Tin tức thành công</p>';
				
				//Log File
				$witelog = new Utility();
                $text = 'Sủa tin tức ID = '.$id;
                $witelog->witelog($text);
         //--------------------------
                return array(
                    'data' => $data,
                    'alert' => $alert,
                    'data_detail' => $data_news,);
            } else {
                $alert = '<p class="bg-warning">Tin này đã tồn tại không thể sửa được</p>';
                return array(
                    'data' => $data,
                    'alert' => $alert,
                    'data_detail' => $data_detail,
                );
            }
            }
        }

        return array(
            'data' => $data,
            // 'alert'=>$alert,
            'data_detail' => $data_detail,
        );
    }

    public function statusAction() {
        $this->layout('layout/user.phtml');
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $status = addslashes(trim($this->params()->fromRoute('status', 0)));

        if ($status == 0) {
            $data = array('news_status' => 1);
        } else {
            $data = array('news_status' => 0);
        }

        $obj = new News();
        $obj->exchangeArray($data);
        $this->getNewsTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('News');
    }

    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $data_detail = $this->getNewsTable()->detailnews($id);
        $img = $data_detail['news_img'];
        $url_img = WEB_MEDIA . '/media/' . $img;
        unlink($url_img);
        $this->getNewsTable()->deletenews($id);
		
		//Log File
				$witelog = new Utility();
                $text = 'Xóa tin tức ID = '.$id;
                $witelog->witelog($text);
         //--------------------------
        $this->redirect()->toRoute('News');
    }

}

?>