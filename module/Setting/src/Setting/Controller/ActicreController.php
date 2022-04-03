<?php

namespace Setting\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Setting\Model\Acticre;
use Product\Model\Utility;

class ActicreController extends AbstractActionController {

    protected $Acticre;

    public function getActicreTable() {
        if (!$this->Acticre) {
            $pst = $this->getServiceLocator();
            $this->Acticre = $pst->get('Setting\Model\ActicreTable');
        }
        return $this->Acticre;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getActicreTable()->listacticre_admin();
        return array('data' => $data);
    }

    public function addAction() {
        $this->layout('layout/user.phtml');
        $Uty = new Utility;

        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('title')));
            $location = addslashes(trim($this->params()->fromPost('location')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $checkname = $this->getActicreTable()->checkname($name);
            if ($checkname) {

                $data_news = array(
                    'title' => $name,
                    'alias' => $alias,
                    'content' => stripslashes($content),
                    'status' => $status,
                    'location' => $location
                );
                //print_r($data_news);die;
                $obj = new Acticre();
                $obj->exchangeArray($data_news);
                $this->getActicreTable()->addacticre($obj);

                $alert = '<p class="bg-success">Thêm bài viết thành công</p>';
                return array('alert' => $alert);
            } else {
                $alert = '<p class="bg-warning">Bài viết đã tồn tại không thể thêm được</p>';
                return array('alert' => $alert);
            }
        }
    }

    public function editAction() {
        $Uty = new Utility;
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data_detail = $this->getActicreTable()->acticredetail($id);

        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('title')));
            $location = addslashes(trim($this->params()->fromPost('location')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $alias = strtolower($Uty->chuyenDoi($name));
            $data_news = array(
                'title' => $name,
                'alias' => $alias,
                'content' => stripslashes($content),
                'status' => $status,
                'location' => $location
            );
            
            $checkname = $this->getActicreTable()->checkname($name);
            if ($name == $data_detail['title']) {
                $obj = new Acticre();
                $obj->exchangeArray($data_news);
                $this->getActicreTable()->updatacticre($id, $obj);                
                $alert = '<p class="bg-success">Sửa bài viết thành công</p>';
                return array(
                    'alert' => $alert,
                    'data_detail' => $data_detail,);
            } else {

                if ($checkname) {
                    $obj = new Acticre();
                    $obj->exchangeArray($data_news);
                    $this->getActicreTable()->updatacticre($id, $obj);
                    $alert = '<p class="bg-success">Sửa bài viết thành công</p>';
                    return array(
                        'alert' => $alert,
                        'data_detail' => $data_detail,);
                } else {
                    $alert = '<p class="bg-warning">Bài viết đã tồn tại không thể sửa được</p>';
                    return array(
                        'alert' => $alert,
                        'data_detail' => $data_detail,
                    );
                }
            }
        }

        return array(
            'data_detail' => $data_detail,
        );
    }
    public function changelocationAction(){       
        $id = addslashes(trim($this->params()->fromPost('id')));
        $location = addslashes(trim($this->params()->fromPost('location')));
       
        $data=array(
            'location'=>$location,
        );        
         $obj = new Acticre();
        $obj->exchangeArray($data);
        $this->getActicreTable()->updatelocation($id, $obj);
        echo 'Xuan Dac';
        die;
    }

    public function statusAction() {
        $this->layout('layout/user.phtml');
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $status = addslashes(trim($this->params()->fromRoute('status', 0)));

        if ($status == 0) {
            $data = array('status' => 1);
        } else {
            $data = array('status' => 0);
        }

        $obj = new Acticre();
        $obj->exchangeArray($data);
        $this->getActicreTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('ActicreAd');
    }

    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = addslashes(trim($this->params()->fromRoute('id', 0)));
        $this->getActicreTable()->deleteacticre($id);
        $this->redirect()->toRoute('ActicreAd');
    }

}

?>