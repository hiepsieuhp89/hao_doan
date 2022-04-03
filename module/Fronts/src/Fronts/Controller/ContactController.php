<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Contact\Model\Contact;
use Product\Model\Utility;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class ContactController extends AbstractActionController {

    private $Contact;

    private function getContactTable() {
        if (!$this->Contact) {
            $pst = $this->getServiceLocator();
            $this->Contact = $pst->get("Contact\Model\ContactTable");
        }
        return $this->Contact;
    }

    public function indexAction() {
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/lien-he.html"><span> Liên hệ</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));
            $email = addslashes(trim($this->params()->fromPost('email')));
            $phone = addslashes(trim($this->params()->fromPost('phone')));
            $address = addslashes(trim($this->params()->fromPost('address')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $data = array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'content' => $content);
            $objct = new Contact();
            $objct->exchangeArray($data);
            $this->getContactTable()->addcontact($objct);
            $alert = '<p class="bg-success" style="line-height:50px; font-size:12px;">Thông tin của bạn đã được gửi đi thành công. Chúng tôi sẽ xử lí thông tin của bạn một cách nhanh nhât.</p>';

            return array(
                'alert' => $alert,
                'setting' => $setting,
            );
        }
        return array(
            'setting' => $setting,
        );
    }

    public function getlayout() {
        $this->layout('layoutitem');
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
        $this->layout()->setVariable('filter', $filter);
    }

}

?>