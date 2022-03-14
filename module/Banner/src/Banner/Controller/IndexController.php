<?php

namespace Banner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Banner\Model\Banner;

class IndexController extends AbstractActionController {

    protected $Banner;

    public function getBannerTable() {
        if (!$this->Banner) {
            $pst = $this->getServiceLocator();
            $this->Banner = $pst->get('Banner\Model\BannerTable');
        }
        return $this->Banner;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getBannerTable()->listbanner();
        return array('data' => $data);
    }

    public function addAction() {
        $this->layout('layout/user.phtml');
        if ($this->request->isPost()) {
            $nameimage = addslashes(trim($this->params()->fromPost('title')));
            $url = addslashes(trim($this->params()->fromPost('url')));
            $location = addslashes(trim($this->params()->fromPost('location')));

            $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgBanner");

            $tmpimg = $_FILES["img"]["tmp_name"];
            $filename = $_FILES["img"]["name"];
            if ($filename == null) {
                $alert = '<p class="bg-warning">Không có hình ảnh nào được chọn</p>';
                return array('alert' => $alert);
            }
            $ext = substr(strrchr($filename, '.'), 1);
            $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;
            move_uploaded_file($tmpimg, "$dirpath/$fileupload");
            //$objImg = new Uty();
            //copy($tmpimg, $dirpath .'/'. $fileupload);
            // $objImg->load($tmpimg);
            // $objImg->resize(836,524);
            // $objImg->save($dirpath .'/'. $fileupload);
            // @$objImg->croppThis($dirpath .'/'. $fileupload); --- Crop ảnh

            $data = array(
                'title' => $nameimage,
                'url' => $url,
                'img' => 'imgBanner/' . $fileupload,
                'location' => $location);

            $Objls = new Banner();
            $Objls->exchangeArray($data);
            $this->getBannerTable()->addbanner($Objls);
            $alert = '<p class="bg-success">Thêm ảnh Banner thành công</p>';
            return array('alert' => $alert);
        }
    }

    public function editAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getBannerTable()->bannerdetail($id);

        if ($this->request->isPost()) {
            $nameimage = addslashes(trim($this->params()->fromPost('title')));
            $url = addslashes(trim($this->params()->fromPost('url')));
            $location = addslashes(trim($this->params()->fromPost('location')));

            $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgBanner");
            $tmpimg = $_FILES["img"]["tmp_name"];
            $filename = $_FILES["img"]["name"];
            if ($filename == '') {
                $fileuploadnull = $data['img'];
                $datanew = array(
                    'title' => $nameimage,
                    'url' => $url,
                    'img' => $fileuploadnull,
                    'location' => $location);
                $Objls = new Banner();
                $Objls->exchangeArray($datanew);
                $this->getBannerTable()->editbanner($id, $Objls);
                $alert = '<p class="bg-success">Sửa ảnh Banner thành công</p>';
                return array(
                    'alert' => $alert,
                    'data' => $data
                );
            } else {
                $img_old = $data['img'];
                $url_img = WEB_MEDIA . '/media/' . $img_old;
                unlink($url_img); // Xóa ảnh cũ nếu chọn ảnh mới;
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode(time($filename)), 0, -1) . time() . '.' . $ext;
                move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                // $objImg = new Uty();
                // copy($tmpimg, $dirpath .'/'. $fileupload);
                //  $objImg->load($tmpimg);
                //  $objImg->resize(836,524);
                //  $objImg->save($dirpath .'/'. $fileupload);
                // @$objImg->croppThis($dirpath .'/'. $fileupload); --- Crop ảnh

                $datanew = array(
                    'title' => $nameimage,
                    'url' => $url,
                    'img' => 'imgBanner/' . $fileupload,
                    'location' => $location);
                $Objls = new Banner();
                $Objls->exchangeArray($datanew);
                $this->getBannerTable()->editbanner($id, $Objls);
                $alert = '<p class="bg-success">Sửa ảnh Banner thành công</p>';
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
        $data = $this->getBannerTable()->bannerdetail($id);
        $url_img = WEB_MEDIA . '/media/' . $data['img'];
        unlink($url_img); //
        $this->getBannerTable()->deletebanner($id);
        $this->redirect()->toRoute('Banner');
    }

    public function statusAction() {
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $status = addslashes(trim($this->params()->fromRoute('status', 0)));
        /*$data_detail = $this->getBannerTable()->bannerdetail($id);
        $location = $data_detail['location'];
        $data_reset = array('status' => 0);
        $obj = new Banner();
        $obj->exchangeArray($data_reset);
        $this->getBannerTable()->resetStatus($location, $obj);
        */
        if($status==0){
        $data_active = array('status' => 1);
        }else{
         $data_active = array('status' => 0);   
        }
        $obj_active = new Banner();
        $obj_active->exchangeArray($data_active);
        $this->getBannerTable()->changestatus($id, $obj_active);
        $this->redirect()->toRoute('Banner');
    }

}

?>