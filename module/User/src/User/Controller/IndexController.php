<?php
	
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController {

    protected $User;

    public function getUserTable() {
        if (!$this->User) {
            $pst = $this->getServiceLocator();
            $this->User = $pst->get('User\Model\UserTable');
        }
        return $this->User;
    }

    public function indexAction() {
        $session_user = new Container('user');
        if ($session_user->username != null) {
            $this->redirect()->toUrl(WEB_PATH . '/system/home');
        }
        $this->layout('layout/layoutlogin.phtml');
        if ($this->request->isPost()) {
            $username = addslashes(trim($this->params()->fromPost('username')));
            $password = addslashes(trim($this->params()->fromPost('password')));
            $endpass = substr(base64_encode(md5($password)), 0, -1);
            //echo $endpass;die;
            if ($username == '') {
                $thongbao = "Chưa nhập tên đăng nhập";
                return array('thongbao' => $thongbao);
            } elseif ($password == '') {
                $thongbao = "Chưa nhập mật khẩu";
                return array('thongbao' => $thongbao);
            } else {
                $check = $this->getUserTable()->checklogin($username, $endpass);
                if ($check) {
					 $this->witelog($username);
                    $this->redirect()->toUrl(WEB_PATH . "/system/home");
                    // die;
                } else {
                    $thongbao = "Tên đăng nhập hoặc mật khẩu không đúng";
                    return array('thongbao' => $thongbao);
                }
            }
            //echo $username;
            //die;
        }

        //return array('thongbao'=>$thongbao);
    }
	public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function witelog($user) {
        $ip_client = $this->get_client_ip();
		 //Lưu Ip vào secssion
        $session_ip = new Container('S_IP');
        $session_ip->ip_client=$ip_client;
        // Kiểm tra thư mục Log của từng tháng xem có chư nếu chưa có thì tạo thư mục sau đó tạ file ngày hôm đó.
        $array_date = explode('-', date('Y-m-d'));
        $parth_forder = WEB_MEDIA . '/public/log/Thang-' . $array_date['1'] . '-' . $array_date['0'];
        $name_file = $array_date['2'] . '-' . $array_date['1'] . '-' . $array_date['0'] . '.txt';
        if (!is_dir($parth_forder)) {
            mkdir($parth_forder);
            //File-----------
            $fp = fopen($parth_forder . '/' . $name_file, 'w') or exit('Error');
            $string = $user . ' ----- '.$ip_client.' ----- ' . date('Y-m-d H:i:s') . ' ----- Đăng nhập Hệ thống';
            fwrite($fp, $string."\r\n");
            fclose($fp);
        } else {
            // Neu có thư mục rồi thì kiểm tra file co chưa nếu chưa có thì tạo file;
            if (file_exists($parth_forder . '/' . $name_file)) { //có file rồi
                $fp = fopen($parth_forder . '/' . $name_file, 'a') or exit('Error');
                $string = $user . ' ----- '.$ip_client.' ----- ' . date('Y-m-d H:i:s') . ' ----- Đăng nhập Hệ thống';
                fwrite($fp, $string . "\r\n");
                fclose($fp);
            } else {
                //Chưa có file tạo file lần đầu
                $fp = fopen($parth_forder . '/' . $name_file, 'w') or exit('Error');
                $string = $user . ' ----- '.$ip_client.' ----- ' . date('Y-m-d H:i:s') . ' ----- Đăng nhập Hệ thống';
                fwrite($fp, $string . "\r\n");
                fclose($fp);
            }
        }
    }
    public function logoutAction() {
        $this->layout('layout/layoutlogin.phtml');
        $session_user = new Container('user');
        $session_user->offsetUnset('username');
        $this->redirect()->toUrl(WEB_PATH . '/system');
    }

}

?>