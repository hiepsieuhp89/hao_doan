<?php

namespace Slideshow\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Slideshow\Model\Slide;
use Product\Model\Resize;

class IndexController extends AbstractActionController {

    protected $Slide;

    public function getSlideTable() {
        if (!$this->Slide) {
            $pst = $this->getServiceLocator();
            $this->Slide = $pst->get('Slideshow\Model\SlideTable');
        }
        return $this->Slide;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getSlideTable()->listslide();
        return array('data' => $data);
    }

    public function addAction() {
        $this->layout('layout/user.phtml');
        if ($this->request->isPost()) {
            $nameimage = addslashes(trim($this->params()->fromPost('title')));
            $url = addslashes(trim($this->params()->fromPost('url')));
            $status = addslashes(trim($this->params()->fromPost('status')));

                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgSlide");

                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];
                if($filename ==null){
                $alert = '<p class="bg-warning">Không có hình ảnh nào được chọn</p>';
                return array('alert' => $alert);
                }
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode(time($filename)), 0, -1) .time(). '.' . $ext;
                //move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                
                 move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(900, 350, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);
                // @$objImg->croppThis($dirpath .'/'. $fileupload); --- Crop ảnh

                $data = array(
                    'title' => $nameimage,
                    'url'=>$url,
                    'img' => 'imgSlide/'.$fileupload,
                    'status' => $status);
                $Objls = new Slide();
                $Objls->exchangeArray($data);
                $this->getSlideTable()->addslide($Objls);
                $alert = '<p class="bg-success">Thêm ảnh silide thành công</p>';
                return array('alert' => $alert);
           
        }
    }

    public function editAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getSlideTable()->slidedetail($id);

        if ($this->request->isPost()) {
             $nameimage = addslashes(trim($this->params()->fromPost('title')));
            $url = addslashes(trim($this->params()->fromPost('url')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            
             $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgSlide");
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];
            if ($filename == '') {
                $fileuploadnull = $data['img'];
                $datanew = array(
                    'title' => $nameimage,
                    'url'=>$url,
                    'img' => $fileuploadnull,
                    'status' => $status);
                $Objls = new Slide();
                $Objls->exchangeArray($datanew);
                $this->getSlideTable()->editslide($id, $Objls);
                $alert = '<p class="bg-success">Sửa ảnh silide thành công</p>';
                return array(
                    'alert' => $alert,
                    'data' => $data
                    );
            } else {
                $img_old=$data['img'];
                $url_img=WEB_MEDIA.'/media/'.$img_old;
                unlink($url_img);// Xóa ảnh cũ nếu chọn ảnh mới;
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode(time($filename)), 0, -1) .time(). '.' . $ext;
                //move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                  move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                    $resizeObj = new Resize($dirpath . '/' . $fileupload);
                    $resizeObj->resizeImage(900, 350, 'crop');
                    $resizeObj->saveImage($dirpath . '/' . $fileupload, 100);
                // @$objImg->croppThis($dirpath .'/'. $fileupload); --- Crop ảnh

                $datanew = array(
                    'title' => $nameimage,
                    'url'=>$url,
                    'img' => 'imgSlide/'.$fileupload,
                    'status' => $status);
                $Objls = new Slide();
                $Objls->exchangeArray($datanew);
                $this->getSlideTable()->editslide($id, $Objls);
                $alert = '<p class="bg-success">Sửa ảnh silide thành công</p>';
                return array(
                    'alert' => $alert,
                    'data' => $data
                    );
            }
        }
        return array('data' => $data);
    }

    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getSlideTable()->slidedetail($id);
        $url_img=WEB_MEDIA.'/media/'.$data['img'];
        unlink($url_img);//
        $this->getSlideTable()->deleteslide($id);
        $this->redirect()->toRoute('Slideshow');
    }

    public function statusAction(){
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $status=  addslashes(trim($this->params()->fromRoute('status',0)));
        if($status==0){
            $data=array('status'=>1);
        }  else {
            $data=array('status'=>0);
        }  
        $obj = new Slide();
        $obj->exchangeArray($data);
        $this->getSlideTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('Slideshow');
    }

}

?>