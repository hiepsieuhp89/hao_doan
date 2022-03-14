<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Customer\Model\Customer;
use Product\Model\Utility;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class AcountController extends AbstractActionController {

    protected $Acount;

    public function getAcountTable() {
        if (!$this->Acount) {
            $pst = $this->getServiceLocator();
            $this->Acount = $pst->get('Customer\Model\CustomerTable');
        }
        return $this->Acount;
    }

    protected $Banner;

    public function getBannerTable() {
        if (!$this->Banner) {
            $pst = $this->getServiceLocator();
            $this->Banner = $pst->get('Banner\Model\BannerTable');
        }
        return $this->Banner;
    }

    protected $Category;

    public function getProductcategoryTable() {
        if (!$this->Category) {
            $pst = $this->getServiceLocator();
            $this->Category = $pst->get('Product\Model\ProductTablecategory');
        }
        return $this->Category;
    }

    protected $Order;

    public function getOrderTable() {
        if (!$this->Order) {
            $sm = $this->getServiceLocator();
            $this->Order = $sm->get('Invoice\Model\OderTable');
        }
        return $this->Order;
    }

    protected $Orderdetail;

    public function getOrderDetailTable() {
        if (!$this->Orderdetail) {
            $sm = $this->getServiceLocator();
            $this->Orderdetail = $sm->get('Invoice\Model\OderdetailTable');
        }
        return $this->Orderdetail;
    }

    protected $Product;

    public function getProductTable() {
        if (!$this->Product) {
            $pst = $this->getServiceLocator();
            $this->Product = $pst->get('Product\Model\ProductTable');
        }
        return $this->Product;
    }

    public function loginAction() {
        $this->getlayout();
        $title_page = '<li><a href="#"><span>Tài khoản của tôi</span></a></li>
		   <li><a href=""><span>Đăng nhập hệ thống</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);

        if ($this->request->isPost()) {
            $email = addslashes(trim($this->params()->fromPost('email')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $endpass = base64_encode(md5(base64_encode(md5($password))));
            $checklogin = $this->getAcountTable()->checklogin($email, $endpass);
            if ($checklogin) {
                $data_detail = $this->getAcountTable()->acountdetail_email($email);
                if ($data_detail['status'] == 1) {
                    $session_user = new Container('userlogin');
                    $session_user->username = $data_detail['fullname'];
                    $session_user->idus = $data_detail['id'];
                    $session_user->email = $data_detail['email'];
                    $session_user->address = $data_detail['address'];
                    $session_user->phone = $data_detail['phone'];
                    $this->redirect()->toUrl(WEB_PATH);
                } else {
                    $error = "Tài khoản của bạn đang bị khóa hãy liên hệ với quản trị website để biết thông tin chi tiết";
                    return array('error' => $error);
                }
            } else {
                $error = "Email hoặc Mật khẩu không đúng";
                return array('error' => $error);
            }
        }
    }

    public function registerAction() {
        $this->getlayout();
        //load email hệ thống
        $session_email = new Container('emailsystem');
        $email_admin = $session_email->email_admin;
        $title_page = '<li><a href="#"><span>Tài khoản của tôi</span></a></li>
		   <li><a href=""><span>Đăng ký tài khoản </span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);

        //print_r($country);die;
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('fullname')));
            $email = addslashes(trim($this->params()->fromPost('email')));
            $phone = addslashes(trim($this->params()->fromPost('phone')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $company = addslashes(trim($this->params()->fromPost('company')));
            $address = addslashes(trim($this->params()->fromPost('address')));
            $date = date('Y-m-d');
            $endpass = base64_encode(md5(base64_encode(md5($password))));
            $data = array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $endpass,
                'company' => $company,
                'address' => $address,
                'yahoo' => '',
                'skype' => '',
                'facebook' => '',
                'date' => $date,
                'cus_mod' => $date
            );

            $check = $this->getAcountTable()->checkacount($email);
            if ($check) {
                $objct = new Customer();
                $objct->exchangeArray($data);
                $this->getAcountTable()->addacount($objct);


                /// Gửi Mail khi đăng ký thành công
                $title_mail = 'Giadung88.com | Thông báo Quý Khách hàng  đã đăng ký thành công tài khoản mua hàng mới';
                $content_mail = '<p style="font-weight:bold">Xin chào bạn, ' . $name . '</p>
                               <p>Thông tin tài khoản của bạn tại <span style="font-weight:bold">Giadung88.com</span></p>
                               <p style="font-weight:bold">Email đăng nhập: ' . $email . '</p>
                               <p style="font-weight:bold">Mật khẩu: ' . $password . '</p>
                               <p>Hãy truy cập Website <a href="' . WEB_PATH . '">' . WEB_PATH . '</a> để đăng nhập</p>';
                $this->sendmail($email, $email_admin, $title_mail, $content_mail);

                $user_new = $this->getAcountTable()->get_acount_new();
                $session_user = new Container('userlogin');
                $session_user->username = $user_new['fullname'];
                $session_user->idus = $user_new['id'];
                $session_user->email = $user_new['email'];
                $session_user->address = $user_new['address'];
                $session_user->phone = $user_new['phone'];
                $this->redirect()->toUrl(WEB_PATH);
            } else {
                $error = 'Email này đã tồn tại không thể đăng ký';
                return array('error' => $error);
            }
        }
    }

    public function personalAction() {
        $this->getlayout();
        $checklogin = new Customer();
        $checklogin->checklogin();
        $title_page = '<li><a href="' . WEB_PATH . '/tai-khoan/personal.html"><span>Tài khoản của tôi</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
    }

    public function informationAction() {
        $this->getlayout();
        $checklogin = new Customer();
        $checklogin->checklogin();
        $title_page = '<li><a href="#"><span>Tài khoản của tôi</span></a></li>
                 <li><a href=""><span>Thông tin tài khoản</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $session_user = new Container('userlogin');
        $id_us = $session_user->idus;
        $data_acount = $this->getAcountTable()->acountdetail($id_us);
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));
            $email = addslashes(trim($this->params()->fromPost('email')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $phone = addslashes(trim($this->params()->fromPost('phone')));
            $company = addslashes(trim($this->params()->fromPost('company')));
            $address = addslashes(trim($this->params()->fromPost('address')));
            $city = addslashes(trim($this->params()->fromPost('city')));
            $yahoo = addslashes(trim($this->params()->fromPost('yahoo')));
            $skype = addslashes(trim($this->params()->fromPost('skype')));
            $facebook = addslashes(trim($this->params()->fromPost('facebook')));
            $date = date('Y-m-d');

            //Đổi pass
            if ($password != null) {
                $endpass = base64_encode(md5(base64_encode(md5($password))));
                $data_passnew = array(
                    'password' => $endpass
                );
                $obj = new Customer();
                $obj->exchangeArray($data_passnew);
                $this->getAcountTable()->updatepass_user($id_us, $obj);
                $error2 = " Cập nhật mật khẩu thành công";
                return array('error2' => $error2, 'data_acount' => $data_acount);
            }

            // Thay đổi thông tin
            $data = array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $data_acount['password'],
                'company' => $company,
                'address' => $address,
                'city' => $city,
                'yahoo' => $yahoo,
                'skype' => $skype,
                'facebook' => $facebook,
                'date' => $data_acount['date'],
                'cus_mod' => $date
            );
            if ($email == $data_acount['email']) {
                $obj = new Customer();
                $obj->exchangeArray($data);
                $this->getAcountTable()->update_acount($id_us, $obj);
                $error = "Cập nhật thông tin tài khoản thành công";
                return array('data_acount' => $data_acount, 'error' => $error);
            } else {
                $check = $this->getAcountTable()->checkacount($email);
                if ($check) {
                    $obj = new Customer();
                    $obj->exchangeArray($data);
                    $this->getAcountTable()->update_acount($id_us, $obj);
                    $error = "Cập nhật thông tin tài khoản thành công";
                    return array('data_acount' => $data_acount, 'error' => $error);
                } else {
                    $error1 = "Email này đã tồn tại không thể cập nhật";
                    return array('data_acount' => $data_acount, 'error1' => $error1);
                }
            }
        }
        return array('data_acount' => $data_acount);
    }

    public function resetpassAction() {
        $this->getlayout();
        //load email hệ thống
        $session_email = new Container('emailsystem');
        $email_admin = $session_email->email_admin;
        $title_page = '<li><a href="#"><span>Tài khoản của tôi</span></a></li>
                 <li><a href="#"><span> Khôi phục mật khẩu</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        if ($this->request->isPost()) {
            $email = addslashes(trim($this->params()->fromPost('email')));
            $check = $this->getAcountTable()->checkacount($email);
            if ($check) {
                $error = 'Email này chưa được đăng ký';
                return array('error' => $error);
            } else {
                $Uty = new Utility();
                $pass = $Uty->rand_string(8);
                $endpass = base64_encode(md5(base64_encode(md5($pass))));
                $data = array(
                    'password' => $endpass
                );
                $obj = new Customer();
                $obj->exchangeArray($data);
                $this->getAcountTable()->updatepass($email, $obj);

                $title_mail = 'Giadung88.com | Khôi phục mật khẩu';
                $content_mail = '<p>Bạn vừa sử dụng tính năng khôi phục mật khẩu Website <a href="' . WEB_PATH . '"><span style="font-weight:bold">Giadung88.com</span></a></p>
                    <p style="font-weight:bold"> Mật khẩu mới của bạn là: ' . $pass . '</p>
                    <p>Để bảo mật tài khoản của bạn hãy đăng nhập Website <a href="' . WEB_PATH . '"><span style="font-weight:bold">Giadung88.com</span></a> để đổi mật khẩu của bạn</p>';
                $this->sendmail($email, $email_admin, $title_mail, $content_mail);

                $error1 = 'Một mật khẩu mới đã được chúng tôi gửi đến Email của bạn. Xin vui lòng kiểm tra Email của bạn !';
                return array('error1' => $error1);
            }
        }
    }

    public function orderAction() {
        $this->getlayout();
        $checklogin = new Customer();
        $checklogin->checklogin();
        $title_page = '<li><a href="#"><span>Tài khoản của tôi</span></a></li>
                 <li><a href="#"><span> Quản lý đơn hàng</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $session_user = new Container('userlogin');
        $id_customer = $session_user->idus;

        $select = new Select();
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        $data_order = $this->getOrderTable()->getoder_user($id_customer);

        $itemsPerPage = 3;
        $data_order->current();
        $paginator = new Paginator(new paginatorIterator($data_order));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(5);

        if ($paginator != null) {
            foreach ($data_order as $key => $value) {
                $id_order = $value->id;
                //echo $id_order;
                $data_oder_detai[$id_order] = $this->getOrderDetailTable()->get_array_order($id_order);
                foreach ($data_oder_detai[$id_order] as $key1 => $value1) {
                    $id_product = $value1['id_product'];
                    $listproduct[$id_product] = $this->getProductTable()->product_shoppingcart($id_product);
                }
            }
            return array(
                'paginator' => $paginator,
                'oder_detail' => $data_oder_detai,
                'listproduct' => $listproduct,
            );
        }// nếu có hóa đơn mới chạy đoạn này
        return array(
            'paginator' => $paginator,
        );
    }

    public function logoutAction() {
        $this->getlayout();
        $session_user = new Container('userlogin');
        $session_user->offsetUnset('username');
        $session_user->offsetUnset('idus');
        $session_user->offsetUnset('email');
        $session_user->offsetUnset('address');
        $session_user->offsetUnset('phone');

        //-----------------------

        $session_customer_guest = new Container('customer_guest');
        $session_customer_guest->offsetUnset('name_customer');
        $session_customer_guest->offsetUnset('mail_customer');
        $session_customer_guest->offsetUnset('phone_customer');
        $session_customer_guest->offsetUnset('address_customer');
        
        $this->redirect()->toUrl(WEB_PATH);
    }

    public function sendmail($mail_to, $mail_from, $title_mail, $content_mail) {
        //load email hệ thống
        $session_email = new Container('emailsystem');
        $mail_system = $session_email->email_system;
        $pass_system = $session_email->pass_system;
        //Gửi được cả html và text
        $message = new Message();
        $message->addTo($mail_to)//Email nhận
                ->addFrom($mail_from)//Email gửi
                ->setSubject($title_mail); //Tiêu đề mail
        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options = new SmtpOptions(array(
            // 'name' => 'localhost',
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'ssl' => 'tls',
                'username' => $mail_system,
                'password' => $pass_system
            ),
            'port' => 587,
        ));
        $content = $content_mail; // Nội dung Email
        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);

        $message->setBody($body);

        $transport->setOptions($options);
        $transport->send($message);
    }

    public function getlayout() {
        $this->layout('layout/layoutitem.phtml');
        $show_banner = 1;
        $filter = 'false';
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
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));

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

    //--------------------------------------
}

?>