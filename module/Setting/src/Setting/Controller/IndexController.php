<?php

namespace Setting\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Setting\Model\Setting;
use Setting\Model\Acticre;
use Product\Model\Utility;
class IndexController extends AbstractActionController {

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
        $data = $this->getSetingTable()->listslide();
        return array('data' => $data);
    }

    public function settingAction() {
        $this->layout('layout/user.phtml');
        $data_setting=  $this->getSettingTable()->datasetting();
       $id=$data_setting['id'];
        if ($this->request->isPost()) {
            $url = addslashes(trim($this->params()->fromPost('url')));
            $email = addslashes(trim($this->params()->fromPost('email')));            
            $address = addslashes(trim($this->params()->fromPost('address')));
            $hotline = addslashes(trim($this->params()->fromPost('hotline')));
            $phone1 = addslashes(trim($this->params()->fromPost('phone1')));            
            $phone2 = addslashes(trim($this->params()->fromPost('phone2')));
            $footer = addslashes(trim($this->params()->fromPost('footer')));
            //$map = addslashes(trim($this->params()->fromPost('map')));
           // $about = addslashes(trim($this->params()->fromPost('about')));
           
            // LOGO
                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/images");
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];                
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = 'logo-' .time(). '.' . $ext;
                if($filename == null){
                    $logo=$data_setting['logo'];
                }  else {                   
                    $url_logo=WEB_MEDIA.'/media/'.$data_setting['logo'];
                    unlink($url_logo);
                    $logo='images/'.$fileupload;
                     move_uploaded_file($tmpimg, "$dirpath/$fileupload");   
                }
               
                // FAVICON
                $tmp_favico = $_FILES["favicon"]["tmp_name"];
                $filename_favico = $_FILES["favicon"]["name"];                
                //$ext = substr(strrchr($filename, '.'), 1);
                //$fileupload = substr(base64_encode(time($filename)), 0, -1) .time(). '.' . $ext;
                if($filename_favico == null){
                    $favico=$data_setting['favicon'];
                }  else {                   
                    $url_favicon=WEB_MEDIA.'/media/'.$data_setting['favicon'];
                    unlink($url_favicon);
                    $favico='images/'.$filename_favico;
                     move_uploaded_file($tmp_favico, "$dirpath/$filename_favico");   
                }
                
                $data = array(                    
                    'website_url'=>$url,
                    'email' => $email,
                    'address' => $address,
                    'hotline'=>$hotline,
                    'phone1'=>$phone1,
                    'phone2'=>$phone2,
                    'footer'=>$footer,
                    //'map'=>$map,
                   // 'about'=>$about,
                   'favicon'=>$favico,
                    'logo'=>$logo,
                    );
                   // print_r($data);die;
                $Obj = new Setting();
                $Obj->exchangeArray($data);
                $this->getSettingTable()->editsetting($id,$Obj);
                $alert = '<p class="bg-success">Sửa thông tin thành công</p>';
                return array('alert' => $alert, 'data_setting' => $data_setting);
           
        }
         return array('data_setting' => $data_setting);
    }
    public function seoAction(){
        $this->layout('layout/user.phtml');
        $data_setting=  $this->getSettingTable()->datasetting();
       $id=$data_setting['id'];
        if ($this->request->isPost()) {
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));            
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $data=array(
                'seo_title'=>$seo_title,
                'seo_keyword'=>$seo_keyword,
                'seo_description'=>$seo_description,
            );
            $obj =new Setting();
            $obj->exchangeArray($data);
            $this->getSettingTable()->editseo($id, $obj);
            $alert = '<p class="bg-success">Sửa thông tin thành công</p>';
           return array('alert' => $alert, 'data_seo' => $data_setting);
        }
       return array('data_seo' => $data_setting);
    }

    public function aboutAction(){
        $this->layout('layout/user.phtml');
        $data_setting=  $this->getSettingTable()->datasetting();
       $id=$data_setting['id'];
       if ($this->request->isPost()) {
            $about = addslashes(trim($this->params()->fromPost('about')));
            $map = addslashes(trim($this->params()->fromPost('map')));            
           
            $data=array(
                'about'=>stripslashes($about),
                'map'=>stripslashes($map),               
            );
            //print_r($data);die;
            $obj =new Setting();
            $obj->exchangeArray($data);
            $this->getSettingTable()->editabout($id, $obj);
            $alert = '<p class="bg-success">Sửa thông tin thành công</p>';
           return array('alert' => $alert, 'data_setting' => $data_setting);
        }
        return array('data_setting' => $data_setting);
    }
    public function sociuAction(){
         $this->layout('layout/user.phtml');
        $data_setting=  $this->getSettingTable()->datasetting();
       $id=$data_setting['id'];
        if ($this->request->isPost()) {
            $facebook = addslashes(trim($this->params()->fromPost('facebook')));
            $twiter = addslashes(trim($this->params()->fromPost('twiter'))); 
            $google = addslashes(trim($this->params()->fromPost('google')));
            $printer = addslashes(trim($this->params()->fromPost('printer')));  
            $fanpage = addslashes(trim($this->params()->fromPost('fanpage')));  
           
            $data=array(
                'facebook'=>$facebook,
                'twiter'=>$twiter,  
                'google'=>$google,
                'printer'=>$printer,
                'fanpage'=>  stripslashes($fanpage),
            );
            //print_r($data);die;
            $obj =new Setting();
            $obj->exchangeArray($data);
            $this->getSettingTable()->editsociu($id, $obj);
            $alert = '<p class="bg-success">Sửa thông tin thành công</p>';
           return array('alert' => $alert, 'data_setting' => $data_setting);
        }
        return array('data_setting' => $data_setting);
    }
     public function configemailAction(){
         $this->layout('layout/user.phtml');
        $data_setting=  $this->getSettingTable()->datasetting();
       $id=$data_setting['id'];
        if ($this->request->isPost()) {
            $email_admin = addslashes(trim($this->params()->fromPost('email_admin')));
            $email_customer = addslashes(trim($this->params()->fromPost('email_customer'))); 
            $email_system = addslashes(trim($this->params()->fromPost('email_system')));
            $pass_system = addslashes(trim($this->params()->fromPost('pass_system')));  
            
           
            $data=array(
                'email_admin'=>$email_admin,
                'email_customer'=>$email_customer,  
                'email_system'=>$email_system,
                'pass_system'=>$pass_system,
                
            );
            //print_r($data);die;
            $obj =new Setting();
            $obj->exchangeArray($data);
            $this->getSettingTable()->editemail($id, $obj);
            $alert = '<p class="bg-success">Sửa thông tin thành công</p>';
           return array('alert' => $alert, 'data_setting' => $data_setting);
        }
        return array('data_setting' => $data_setting);
    }
    public function analyticsAction(){
        $this->layout('layout/user.phtml');
         if ($this->request->isPost()) {
              $google = addslashes(trim($this->params()->fromPost('google')));
               $file_open = fopen(WEB_MEDIA . '/public/file/google.txt', 'w')or exit("khong tim thay file can mo");
                fwrite($file_open, stripslashes($google));
                 fclose($file_open);
           $alert = '<p class="bg-success">Cập nhật thành công</p>';
           return array('alert' => $alert,);
         }
    }
     public function suportAction(){
        $this->layout('layout/user.phtml');
         if ($this->request->isPost()) {
              $suport = addslashes(trim($this->params()->fromPost('suport')));
               $file_open = fopen(WEB_MEDIA . '/public/file/suport.txt', 'w')or exit("khong tim thay file can mo");
                fwrite($file_open, stripslashes($suport));
                 fclose($file_open);
           $alert = '<p class="bg-success">Cập nhật thành công</p>';
           return array('alert' => $alert,);
         }
    }
	
	public function nhatkyAction(){
        $this->layout('layout/user.phtml');
          if ($this->request->isPost()) {
              $date_time = addslashes(trim($this->params()->fromPost('date-time')));
              $array_date = explode("-", $date_time);
              $parth_forder = WEB_MEDIA . '/public/log/Thang-' . $array_date['1'] . '-' . $array_date['2'];
              if (!is_dir($parth_forder)) {
                  return array(
                     'error'=>'Ngày bạn chọn không có nhật ký hoạt động nào.' 
                      );
              }else{
                  if (file_exists($parth_forder . '/' . $date_time.'.txt')) {
                       $read_file = file($parth_forder . '/' . $date_time.'.txt');
                       return array('data'=>$read_file);
                  }else{
                    return array(
                     'error'=>'Ngày bạn chọn không có nhật ký hoạt động nào.' 
                      );  
                  }
              }
             
          }
    }

}

?>