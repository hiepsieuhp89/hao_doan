<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use Zend\Session\Container;
use Product\Model\Utility;

class ManageController extends AbstractActionController {

    protected $User;

    public function getUserTable() {
        if (!$this->User) {
            $pst = $this->getServiceLocator();
            $this->User = $pst->get('User\Model\UserTable');
        }
        return $this->User;
    }
    protected $Order;

    public function getOrderTable() {
        if (!$this->Order) {
            $sm = $this->getServiceLocator();
            $this->Order = $sm->get('Invoice\Model\OderTable');
        }
        return $this->Order;
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
        $checklog = new User();
        $checklog->checkloginone();
        $this->layout('layout/user.phtml');
        $data = $this->getUserTable()->listuser();
		
		 //Log File
				  $witelog = new Utility();
                $text = 'Xem danh sách tài khoản quản trị';
                $witelog->witelog($text);
                //--------------------------
        return array('data' => $data);
    }

    public function homeAction() {
        $this->layout('layout/user.phtml');
         $data_order = $this->getOrderTable()->listorder();
         $data_product = $this->getProductTable()->listproduct();
        return array(
            'data_order' => $data_order,
            'data_product'=>$data_product,
            );
    }

    public function adduserAction() {
        $checklog = new User();
        $checklog->checkloginone();
        $this->layout('layout/user.phtml');
        if ($this->request->isPost()) {
            $username = addslashes(trim($this->params()->fromPost('username')));
            $fullname = addslashes(trim($this->params()->fromPost('fullname')));
            $email = addslashes(trim($this->params()->fromPost('email')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $repasss = addslashes(trim($this->params()->fromPost('repass')));
            $permission = addslashes(trim($this->params()->fromPost('permission')));
            if ($password != $repasss) {
                $thongbao = 'Password không khớp nhau';
                return array('thongbao' => $thongbao);
            }
            $chekUs = $this->getUserTable()->checkuser($username);
            if ($chekUs) {
                $endpass = substr(base64_encode(md5($password)), 0, -1);
                $data = array(
                    'username' => $username, 
                    'fullname' => $fullname, 
                    'email' => $email, 
                    'password' => $endpass, 
                    'permission' => $permission);
                // print_r($data);die();
                $objuser = new User;
                $objuser->exchangeArray($data);
                $this->getUserTable()->adduser($objuser);
                $thongbao = 'Thêm tài khoản thành công';
				
				//Log File
				  $witelog = new Utility();
                $text = 'Thêm tài khoản quản trị - '.$username;
                $witelog->witelog($text);
                //--------------------------
                return array('thongbao' => $thongbao);
            } else {
                $thongbao = 'Username này đã có trong hệ thống';
                return array('thongbao' => $thongbao);
            }
        }
    }

    public function updateuserAction() {
        $checklog = new User();
        $checklog->checkloginone();
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $dataUs = $this->getUserTable()->detailuser($id);
        if ($this->request->isPost()) {
            $username = addslashes(trim($this->params()->fromPost('username')));
            $fullname = addslashes(trim($this->params()->fromPost('fullname')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $repasss = addslashes(trim($this->params()->fromPost('repass')));
            $email = addslashes(trim($this->params()->fromPost('email')));
            if ($password != $repasss) {
                $thongbao = 'Password không khớp nhau';
                return array('thongbao' => $thongbao, 'data' => $dataUs);
            }
            // $chekUs =  $this->getUserTable()->checkuser($username);
            //if($chekUs){
            $endpass = substr(base64_encode(md5($password)), 0, -1);
            $data = array(
                'username' => $username,
                'fullname' => $fullname, 
                'pass' => $endpass,
                'email' => $email,
                'permission' => 1,
                );

            $objuser = new User;
            $objuser->exchangeArray($data);
            $this->getUserTable()->updateuser($id, $objuser);
			
			//Log File
				  $witelog = new Utility();
                $text = 'Sủa tài khoản quản trị ID='.$id;
                $witelog->witelog($text);
                //--------------------------
//            }else {
//               $thongbao='Username này đã có trong hệ thống';
//               return array('thongbao'=>$thongbao, 'data'=>$dataUs);
//            }
        }
        return array('data' => $dataUs);
    }

    public function changpassAction() {
        $checklog = new User();
        $checklog->checkloginone();
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        if ($this->request->isPost()) {
            $passold = addslashes(trim($this->params()->fromPost('passold')));
            $passnew = addslashes(trim($this->params()->fromPost('passwordnew')));
            $repasss = addslashes(trim($this->params()->fromPost('repass')));
            $endpassold = substr(base64_encode(md5($passold)), 0, -1);
            if ($passnew != $repasss) {
                $thongbao = "Password không khớp";
                return array('thongbao' => $thongbao);
            }
            $checkpass = $this->getUserTable()->checkpass($endpassold);
            if ($checkpass) {
                $endpassnew = substr(base64_encode(md5($passnew)), 0, -1);
                $data = array('password' => $endpassnew);
                $objuser = new User;
                $objuser->exchangeArray($data);
                $this->getUserTable()->changerpass($id, $objuser);
				
				//Log File
				  $witelog = new Utility();
                $text = 'Đổi mật khẩu tài khoản quản trị ID='.$id;
                $witelog->witelog($text);
                //--------------------------
            } else {
                $thongbao = "Password cũ không đúng";
                return array('thongbao' => $thongbao);
            }
        }
    }

    public function resetpassAction() {
        $idus = $this->params()->fromPost('id');
        $pass = $this->params()->fromPost('password');
        $endpass = substr(base64_encode(md5($pass)), 0, -1);
        $data = array('password' => $endpass);
        //print_r($data);die();
        $objuser = new User;
        $objuser->exchangeArray($data);
        $this->getUserTable()->changerpass($idus, $objuser);
		
		//Log File
				  $witelog = new Utility();
                $text = 'Khôi phục mật khẩu tài khoản quản trị ID='.$idus;
                $witelog->witelog($text);
                //--------------------------
        echo 'Sucsses';
        die;
    }

    public function deleteusAction() {
        $checklog = new User();
        $checklog->checkloginone();
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $this->getUserTable()->deleteuser($id);
		
		//Log File
				  $witelog = new Utility();
                $text = 'Xóa tài khoản quản trị ID='.$id;
                $witelog->witelog($text);
                //--------------------------
        $this->redirect()->toUrl(WEB_PATH . '/system/manageuser.html');
    }

}
