<?php

namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Contact\Model\Contact;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class IndexController extends AbstractActionController {

    protected $Contact;
    public function getContactTable() {
        if (!$this->Contact) {
            $pst = $this->getServiceLocator();
            $this->Contact = $pst->get('Contact\Model\ContactTable');
        }
        return $this->Contact;
    }
    protected $Setting;
    public function getSettingTable() {
        if (!$this->Setting) {
            $pst = $this->getServiceLocator();
            $this->Setting = $pst->get('Setting\Model\SettingTable');
        }
        return $this->Setting;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getContactTable()->listcontact();
        return array('data' => $data);
    }

    public function viewAction() {
        $data_setting = $this->getSettingTable()->datasetting();       
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getContactTable()->contactdetail($id);

        //Trả lời khách hàng
        if ($this->request->isPost()) {
            $title = addslashes(trim($this->params()->fromPost('title')));
            $content = addslashes(trim($this->params()->fromPost('content')));
            $message = new Message();
            $message->addTo($data['email'])
                    ->addFrom($data_setting['email_admin'])
                    ->setSubject($title);

// Setup SMTP transport using LOGIN authentication
            $transport = new SmtpTransport();
            $options = new SmtpOptions(array(
                //'host' => '103.18.6.137',
                'host' => 'smtp.gmail.com',
                'connection_class' => 'login',
                'connection_config' => array(
                    'ssl' => 'tls',
                    // 'username' => 'support@linkpayplus.eu',
                     //'password' => 'zgkjgsgospcqbrqg' ,  
                    'username' => $data_setting['email_system'],
                    'password' => $data_setting['pass_system'],
                ),
                'port' => 587,
            ));
            
            $html = new MimePart($content);
            $html->type = "text/html";

            $body = new MimeMessage();
            $body->addPart($html);
            $message->setBody($body);
            $transport->setOptions($options);
            $transport->send($message);
            
            //Cập nhật trạng thái
            $data_obj=array('status'=>1);
            $obj=new Contact();
            $obj->exchangeArray($data_obj);
            $this->getContactTable()->updatestatus($id,$obj);
            $alert='Thông tin đã được gửi đến khách hàng';
            return array('alert'=>$alert,'data' => $data);
        }

        return array('data' => $data);
    }
	
	public function EmailMaketingAction() {
        $data_setting = $this->getSettingTable()->datasetting();       
        $this->layout('layout/user.phtml');
        

        //Trả lời khách hàng
        if ($this->request->isPost()) {
            $title = addslashes(trim($this->params()->fromPost('title')));
			$email_nhan = addslashes(trim($this->params()->fromPost('email_from')));
            $content = addslashes(trim($this->params()->fromPost('content')));
           
            
           $this->sendmail($email_nhan, $title, $content);
            $alert='Thông tin đã được gửi đến khách hàng';
            return array('alert'=>$alert,);
        }

        //return array('data' => $data);
    }
	
	public function sendmail($mail_to, $title_mail, $content_mail) {
		$data_setting = $this->getSettingTable()->datasetting();      
//Gửi được cả html và text
        $message = new Message();
        $message->addTo($mail_to)//Email nhận
                ->addFrom($data_setting['email_system'])//Email gửi
                ->setSubject($title_mail); //Tiêu đề mail
// Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options = new SmtpOptions(array(
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'ssl' => 'tls',
                //'username' => 'xuandac990@gmail.com',
                //'password' => 'mmysoqiziyhvsbea'
				'username' => $data_setting['email_system'],
                'password' => $data_setting['pass_system'],
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

	
    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $this->getContactTable()->deletecontact($id);
        $this->redirect()->toRoute('Contact');
    }

}

?>