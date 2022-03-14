<?php
namespace Product\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Productmanufacture;
use Product\Model\Utility;
use Zend\Session\Container;

class ManufactureController extends AbstractActionController {

    protected $Manufacture;
    public function getManufactureTable() {
        if (!$this->Manufacture) {
            $pst = $this->getServiceLocator();
            $this->Manufacture = $pst->get('Product\Model\ProductTablemanufacture');
        }
        return $this->Manufacture;
    }

    public function indexAction() {
         $this->layout('layout/user.phtml');
         $data=  $this->getManufactureTable()->listmanu();				//Log File				$witelog = new Utility();                $text = 'Xem danh sách hãng sản xuất' ;                $witelog->witelog($text);                //--------------------------
         return array('data'=>$data);
    }
    public function addAction(){
        $Uty=new Utility;
        $this->layout('layout/user.phtml');
        
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('manu_name')));            
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
            $alias=strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
            $checkname = $this->getManufactureTable()->checkname($name);
            if ($checkname) {
                
                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgManufature");
               
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];              
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode($filename), 0, -1) .time(). '.' . $ext;
                move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                
                
                $data_mn = array(
                    'manu_name' => $name,    
                    'alias'=>$alias,
                    'description' => $description,
                    'status' => $status,   
                    'img'=>'imgManufature/'.$fileupload,
                    'date' => $date,                   
                    'seo_title' => $seo_title,
                    'seo_keyword' => $seo_keyword,
                    'seo_description' => $seo_description,
                );
                
                 $obj_mn = new Productmanufacture();
                $obj_mn->exchangeArray($data_mn);
                $this->getManufactureTable()->addmanu($obj_mn);
                $alert = '<p class="bg-success">Thêm hãng sản xuất thành công</p>';								//Log File				$witelog = new Utility();                $text = 'Thêm mới hãng sản xuất - '.$name ;                $witelog->witelog($text);                //--------------------------
                return array( 'alert' => $alert);
            } else {
                $alert = '<p class="bg-warning">Tên hãng sản xuất này đã tồn tại không thể thêm được</p>';
                return array('alert' => $alert);
            }
        }
    }
    public function editAction(){
        $Uty=new Utility;
        $this->layout('layout/user.phtml');
          $id=  addslashes(trim($this->params()->fromRoute('id',0)));
          $data_detail=  $this->getManufactureTable()->mannu_detail($id);
        
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('manu_name')));            
            $status = addslashes(trim($this->params()->fromPost('status')));
            $description = addslashes(trim($this->params()->fromPost('description')));
            $seo_title = addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword = addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description = addslashes(trim($this->params()->fromPost('seo_description')));
             $alias=strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
            
                $dirpath = str_replace("\\", "/", WEB_MEDIA . "/media/imgManufature");               
                $tmpimg = $_FILES["img"]["tmp_name"];
                $filename = $_FILES["img"]["name"];              
                $ext = substr(strrchr($filename, '.'), 1);
                $fileupload = substr(base64_encode($filename), 0, -1) . '.' . $ext;
               
                if($filename == null){
                    $img=$data_detail['img'];
                }  else {
                    $img='imgManufature/'.$fileupload;
                     move_uploaded_file($tmpimg, "$dirpath/$fileupload");
                }
                $data_mn = array(
                    'manu_name' => $name,   
                    'alias'=>$alias,
                    'description' => $description,
                    'status' => $status,   
                    'img'=>$img,
                    'date' => $date,                   
                    'seo_title' => $seo_title,
                    'seo_keyword' => $seo_keyword,
                    'seo_description' => $seo_description,
                );
            $checkname = $this->getManufactureTable()->checkname($name);
            if($name == $data_detail['manu_name']){
                $data_detail=  $this->getManufactureTable()->mannu_detail($id);
                $obj_mn = new Productmanufacture();
                $obj_mn->exchangeArray($data_mn);
                $this->getManufactureTable()->update_manu($id, $obj_mn);								//Log File				$witelog = new Utility();                $text = 'Sửa hãng sản xuất - ID = '.$id ;                $witelog->witelog($text);                //--------------------------
                $alert = '<p class="bg-success">Sửa thành công</p>';
                return array( 'data_detail'=>$data_detail,'alert' => $alert);
            }  else {                
                if ($checkname) {
                $data_detail=  $this->getManufactureTable()->mannu_detail($id);    
                $obj_mn = new Productmanufacture();
                $obj_mn->exchangeArray($data_mn);
                $this->getManufactureTable()->update_manu($id, $obj_mn);
                $alert = '<p class="bg-success">Sửa thành công</p>';								//Log File				$witelog = new Utility();                $text = 'Sửa hãng sản xuất - ID = '.$id ;                $witelog->witelog($text);                //--------------------------				
                return array('data_detail'=>$data_detail , 'alert' => $alert);
            } else {
                 $data_detail=  $this->getManufactureTable()->mannu_detail($id);
                $alert = '<p class="bg-warning">Tên hãng sản xuất này đã tồn tại không thể sửa được</p>';
                return array('data_detail'=>$data_detail,'alert' => $alert);
            }
            }
        }
        return array('data_detail'=>$data_detail);
    }
     public function statusAction(){
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $status=  addslashes(trim($this->params()->fromRoute('status',0)));
        if($status==0){
            $data=array('status'=>1);
        }  else {
            $data=array('status'=>0);
        }  
        $obj = new Productmanufacture();
        $obj->exchangeArray($data);
        $this->getManufactureTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('Manufacture');
    }
    
    public function deleteAction(){
        $this->layout('layout/user.phtml');
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $data_detail=  $this->getManufactureTable()->mannu_detail($id);
        $img=$data_detail['img'];
        $url_img=WEB_MEDIA.'/media/'.$img;
        unlink($url_img);
        $this->getManufactureTable()->delete_manu($id);				//Log File				$witelog = new Utility();                $text = 'Xóa hãng sản xuất - ID = '.$id ;                $witelog->witelog($text);                //--------------------------
         $this->redirect()->toRoute('Manufacture');
        
    }
}

?>