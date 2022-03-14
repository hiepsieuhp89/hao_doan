<?php

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Productcategory;
use Product\Model\Product;
use Product\Model\Xuatxu;
use Product\Model\Utility;
use Zend\Session\Container;

class XuatxuController extends AbstractActionController {

  
    protected $Xuatxu;
    public function getXuatxuTable() {
        if (!$this->Xuatxu) {
            $pst = $this->getServiceLocator();
            $this->Xuatxu = $pst->get('Product\Model\XuatxuTable');
        }
        return $this->Xuatxu;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getXuatxuTable()->listxuatxu();  
		
		//Log File
				$witelog = new Utility();
                $text = 'Xem danh sách Xuất xứ sản phẩm' ;
                $witelog->witelog($text);
                //--------------------------
        return array('data' => $data,);
    }

    public function addAction() {       
        $this->layout('layout/user.phtml');
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));     
            $status = addslashes(trim($this->params()->fromPost('status')));
            $checkname = $this->getXuatxuTable()->checkname($name);
            if ($checkname) {               
                $data_cat = array(
                    'name' => $name,
                    'status'=>$status
                );
                $obj_cat = new Xuatxu();
                $obj_cat->exchangeArray($data_cat);
                $this->getXuatxuTable()->addxuatxu($obj_cat);               
                $alert = '<p class="bg-success">Thêm xuất xứ thành công</p>'; 
				
				//Log File
				$witelog = new Utility();
                $text = 'Thêm mới xuất xứ sản phẩm - '.$name ;
                $witelog->witelog($text);
                //--------------------------
				
                return array('alert'=>$alert);
            } else {
                $alert = '<p class="bg-warning">Tên xuất xứ này đã tồn tại không thể thêm được</p>';
                return array('alert'=>$alert);
            }
        }
       
    }

    public function editAction() {     
        $this->layout('layout/user.phtml');
        $id = addslashes($this->params()->fromRoute('id', 0));       
        $data_detail = $this->getXuatxuTable()->xuatxudetail($id);
        if ($this->request->isPost()) {
           $name = addslashes(trim($this->params()->fromPost('name')));
            $status = addslashes(trim($this->params()->fromPost('status')));
            $checkname = $this->getXuatxuTable()->checkname($name);
           if ($checkname || $data_detail['name']==$name) {               
                $data_cat = array(
                    'name' => $name, 
                    'status'=>$status,
                );
                $obj_cat = new Xuatxu();
                $obj_cat->exchangeArray($data_cat);
                $this->getXuatxuTable()->updatexuatxu($id,$obj_cat);               
                $alert = '<p class="bg-success">Sửa xuất xứ thành công</p>'; 
				
				//Log File
				$witelog = new Utility();
                $text = 'Sửa xuất xứ sản phẩm - ID = '.$id ;
                $witelog->witelog($text);
                //--------------------------
				
                return array('alert'=>$alert, 'data_detail' => $data_detail, );
            } else {
                $alert = '<p class="bg-warning">Tên xuất xứ này đã tồn tại không thể thêm được</p>';
                return array('alert'=>$alert, 'data_detail' => $data_cat, );
            }
        }
        return array(
            'data_detail' => $data_detail,           
        );
    }
public function statusAction(){
   
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $status=  addslashes(trim($this->params()->fromRoute('status',0)));
        if($status==0){
            $data=array('status'=>1);
        }  else {
            $data=array('status'=>0);
        }  
        $obj = new Xuatxu();
        $obj->exchangeArray($data);
        $this->getXuatxuTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('Xuatxu');
    }
    
    
    public function deleteAction(){
        $this->layout('layout/user.phtml');
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));       
        $this->getXuatxuTable()->delete_xuatxu($id);
		
		//Log File
				$witelog = new Utility();
                $text = 'Xóa xuất xứ sản phẩm - ID = '.$id ;
                $witelog->witelog($text);
                //--------------------------
				
         $this->redirect()->toRoute('Xuatxu');
        
    }
}

?>