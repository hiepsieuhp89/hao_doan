<?php
namespace News\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use News\Model\Newscategory;
use Product\Model\Utility;
use Zend\Session\Container;

class CategoryController extends AbstractActionController {

    protected $Newscategory;

    public function getNewscategoryTable() {
        if (!$this->Newscategory) {
            $pst = $this->getServiceLocator();
            $this->Newscategory = $pst->get('News\Model\NewsTablecategory');
        }
        return $this->Newscategory;
    }
    
     protected $News;

    public function getNewsTable() {
        if (!$this->News) {
            $pst = $this->getServiceLocator();
            $this->News = $pst->get('News\Model\NewsTable');
        }
        return $this->News;
    }
    public function indexAction() {
         $this->layout('layout/user.phtml');
         $data=  $this->getNewscategoryTable()->listcategory();
         foreach ($data as $key=>$value){
             $parent=$value['parent'];
             $parent_name[$parent]=  $this->getNewscategoryTable()->getparent_name($parent);
         }
         return array('data'=>$data,'parent_name'=>$parent_name);
    }
    public function addAction(){
        $this->layout('layout/user.phtml');
        $data=  $this->getNewscategoryTable()->listcatparent();
         $Uty=new Utility;
         
        if($this->request->isPost()){           
            $name=  addslashes(trim($this->params()->fromPost('name')));
            $parent=  addslashes(trim($this->params()->fromPost('parent')));
            $cat_fatured=  addslashes(trim($this->params()->fromPost('cat_fatured')));
            $cat_new=  addslashes(trim($this->params()->fromPost('cat_new')));
            $status=  addslashes(trim($this->params()->fromPost('status')));
            $description=  addslashes(trim($this->params()->fromPost('description')));
            $seo_title=  addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword=  addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description=  addslashes(trim($this->params()->fromPost('seo_description')));
            $alias=strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");     
            $checkname=  $this->getNewscategoryTable()->checkname($name);
            if($checkname){
            $data_cat=array(
                'name'=>$name,
                'parent'=>$parent,
                'alias'=>$alias,
                'description'=>$description,
                'status'=>$status,
                'cat_featured'=>$cat_fatured,
                'cat_new'=>$cat_new,
                'date'=>$date,
                'mod'=>$date,
                'seo_title'=>$seo_title,
                'seo_keyword'=>$seo_keyword,
                'seo_description'=>$seo_description,
               
            );  
           
             $obj_cat= new Newscategory();
             $obj_cat->exchangeArray($data_cat);
             $this->getNewscategoryTable()->addcategory($obj_cat);  
             
             $data=  $this->getNewscategoryTable()->listcatparent();
             $alert='<p class="bg-success">Thêm danh mục thành công</p>';             
            return array('data'=>$data,'alert'=>$alert);
           
        }  else {
            $alert='<p class="bg-warning">Tên danh mục này đã tồn tại không thể thêm được</p>';
            return array('data'=>$data, 'alert'=>$alert);
            
        }
        }
        return array('data'=>$data);
    }
    public function editAction(){
        $Uty=new Utility;
        $this->layout('layout/user.phtml');
        $id=  $this->params()->fromRoute('id');
        $data=  $this->getNewscategoryTable()->listcatparent();
         $data_detail=  $this->getNewscategoryTable()->detailcategory($id);
        if($this->request->isPost()){
            $name=  addslashes(trim($this->params()->fromPost('name')));
            $parent=  addslashes(trim($this->params()->fromPost('parent')));
            $cat_fatured=  addslashes(trim($this->params()->fromPost('cat_fatured')));
            $cat_new=  addslashes(trim($this->params()->fromPost('cat_new')));
            $status=  addslashes(trim($this->params()->fromPost('status')));
            $description=  addslashes(trim($this->params()->fromPost('description')));
            $seo_title=  addslashes(trim($this->params()->fromPost('seo_title')));
            $seo_keyword=  addslashes(trim($this->params()->fromPost('seo_keyword')));
            $seo_description=  addslashes(trim($this->params()->fromPost('seo_description')));
            $alias=strtolower($Uty->chuyenDoi($name));
            $date = date("Y-m-d ");
             $data_cat=array(
                'name'=>$name,
                'parent'=>$parent,
                'alias'=>$alias,
                'description'=>$description,
                'status'=>$status,
                'cat_featured'=>$cat_fatured,
                'cat_new'=>$cat_new,
                'date'=>$data_detail['date'],
                'mod'=>$date,
                'seo_title'=>$seo_title,
                'seo_keyword'=>$seo_keyword,
                'seo_description'=>$seo_description,
               
            );  
            $checkname=  $this->getNewscategoryTable()->checkname($name);
            if($name == $data_detail['name']){
             $obj_cat= new Newscategory();
             $obj_cat->exchangeArray($data_cat);
              $this->getNewscategoryTable()->updatecatalog($id, $obj_cat);   
             
             $data=  $this->getNewscategoryTable()->listcatparent();
             $alert='<p class="bg-success">Sửa danh mục thành công</p>';             
            return array('data'=>$data,'alert'=>$alert, 'data_detail'=>$data_detail);
            }  else {
             if($checkname){           
             $obj_cat= new Newscategory();
             $obj_cat->exchangeArray($data_cat);
             $this->getNewscategoryTable()->updatecatalog($id, $obj_cat);  
             
             $data=  $this->getNewscategoryTable()->listcatparent();
             $alert='<p class="bg-success">Sửa danh mục thành công</p>';             
            return array('data'=>$data,'alert'=>$alert,'data_detail'=>$data_detail);
           
        }  else {
            $alert='<p class="bg-warning">Tên danh mục này đã tồn tại không thể sửa được</p>';
            return array('data'=>$data, 'alert'=>$alert, 'data_detail'=>$data_detail);
            
        }
            }
        }
        return array('data'=>$data,'data_detail'=>$data_detail);
    }
    public function statusAction(){
        $this->layout('layout/user.phtml');
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $status=  addslashes(trim($this->params()->fromRoute('status',0)));
        if($status==0){
            $data=array('status'=>1);
        }  else {
            $data=array('status'=>0);
        }  
        $obj = new Newscategory();
        $obj->exchangeArray($data);
        $this->getNewscategoryTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('CategoryNews');
    }
    public function deleteAction(){
        $this->layout('layout/user.phtml');
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $this->getNewsTable()->deletenews_cat($id);//xóa các tin thuộc dnah mục này        
        $this->getNewscategoryTable()->deletecategory($id);
         $this->redirect()->toRoute('CategoryNews');
        
    }
}

?>