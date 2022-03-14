<?php

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Model\Productcategory;
use Product\Model\Product;
use Product\Model\Chatlieu;
use Product\Model\Utility;
use Zend\Session\Container;

class ChatlieuController extends AbstractActionController {

  
    protected $Chatlieu;
    public function getChatlieuTable() {
        if (!$this->Chatlieu) {
            $pst = $this->getServiceLocator();
            $this->Chatlieu = $pst->get('Product\Model\ChatlieuTable');
        }
        return $this->Chatlieu;
    }

    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getChatlieuTable()->listchatlieu();    
		
		//Log File
				$witelog = new Utility();
                $text = 'Xem danh sách chất liệu sản phẩm' ;
                $witelog->witelog($text);
                //--------------------------
        return array('data' => $data,);
    }

    public function addAction() {       
        $this->layout('layout/user.phtml');
        if ($this->request->isPost()) {
            $name = addslashes(trim($this->params()->fromPost('name')));  
             $status = addslashes(trim($this->params()->fromPost('status')));  
            $checkname = $this->getChatlieuTable()->checkname($name);
            if ($checkname) {               
                $data_cat = array(
                    'name' => $name,     
                    'status'=>$status
                );
                $obj_cat = new Chatlieu();
                $obj_cat->exchangeArray($data_cat);
                $this->getChatlieuTable()->addchatlieu($obj_cat);               
                $alert = '<p class="bg-success">Thêm chất liệu thành công</p>'; 
				
				//Log File
				$witelog = new Utility();
                $text = 'Thêm mới chất liệu sản phẩm' ;
                $witelog->witelog($text);
                //--------------------------
				
                return array('alert'=>$alert);
            } else {
                $alert = '<p class="bg-warning">Tên chất liệu này đã tồn tại không thể thêm được</p>';
                return array('alert'=>$alert);
            }
        }
       
    }

    public function editAction() {     
        $this->layout('layout/user.phtml');
        $id = addslashes($this->params()->fromRoute('id', 0));       
        $data_detail = $this->getChatlieuTable()->chatlieudetail($id);
        if ($this->request->isPost()) {
           $name = addslashes(trim($this->params()->fromPost('name'))); 
           $status = addslashes(trim($this->params()->fromPost('status')));
            $checkname = $this->getChatlieuTable()->checkname($name);
           if ($checkname || $data_detail['name']==$name) {               
                $data_cat = array(
                    'name' => $name,      
                    'status'=>$status
                );
                $obj_cat = new Chatlieu();
                $obj_cat->exchangeArray($data_cat);
                $this->getChatlieuTable()->updatechatlieu($id,$obj_cat);               
                $alert = '<p class="bg-success">Sửa chất liệu thành công</p>'; 
				
					//Log File
				$witelog = new Utility();
                $text = 'Sửa chất liệu sản phẩm ID = '.$name ;
                $witelog->witelog($text);
                //--------------------------
				
                return array('alert'=>$alert, 'data_detail' => $data_detail, );
            } else {
                $alert = '<p class="bg-warning">Tên chất liệu này đã tồn tại không thể thêm được</p>';
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
        $obj = new Chatlieu();
        $obj->exchangeArray($data);
        $this->getChatlieuTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('Chatlieu');
    }
    
    public function deleteAction(){
        $this->layout('layout/user.phtml');
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));       
        $this->getChatlieuTable()->delete_chatlieu($id);
		
			//Log File
				$witelog = new Utility();
                $text = 'Xóa chất liệu sản phẩm' ;
                $witelog->witelog($text);
                //--------------------------
         $this->redirect()->toRoute('Chatlieu');
        
    }
}

?>