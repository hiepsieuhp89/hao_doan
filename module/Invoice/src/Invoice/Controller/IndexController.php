<?php

namespace Invoice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Invoice\Model\Oder;
use Invoice\Model\Oderdetail;
use Product\Model\Utility;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
class IndexController extends AbstractActionController {

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
    
    protected $Customer;
    public function getCustomerTable() {
        if (!$this->Customer) {
            $pst = $this->getServiceLocator();
            $this->Customer = $pst->get('Customer\Model\CustomerTable');
        }
        return $this->Customer;
    }
   public function indexAction() {
        $this->layout('layout/user.phtml');
        $data =  $this->getOrderTable()->listorder();
		
		 //Log File
				  $witelog = new Utility();
                $text = 'Xem Danh sách hóa đơn';
                $witelog->witelog($text);
                //--------------------------
				
        return array('data' => $data);
    }
    public function odercustomerAction(){
        $this->layout('layout/user.phtml');
        $id_customer = $this->params()->fromQuery('cus');
        $data = $this->getOrderTable()->getoder_user($id_customer);
        $data_customer = $this->getCustomerTable()->acountdetail($id_customer);
		
		 //Log File
				  $witelog = new Utility();
                $text = 'Xem Danh sách hóa đơn của khách hàng - '.$data_customer['name'];
                $witelog->witelog($text);
                //--------------------------
        return array('data' => $data, 'data_customer'=>$data_customer);
    }

    public function viewAction(){
        $this->layout('layout/user.phtml');
        $id_order=  $this->params()->fromRoute('id');
       $data_oder=  $this->getOrderTable()->view_order($id_order);
       $id_oder_detail=$data_oder['id'];
       $data_detail[$id_oder_detail]=  $this->getOrderDetailTable()->get_array_order($id_oder_detail);
       foreach ($data_detail[$id_oder_detail] as $key=>$value){
           $id_product=$value['id_product'];
           $data_product[$id_product]=  $this->getProductTable()->product_shoppingcart($id_product);
       }
	   
	    //Log File
				$witelog = new Utility();
                $text = 'Xem  hóa đơn ID ='.$id_order;
                $witelog->witelog($text);
                //--------------------------
       return array(
           'data'=>$data_oder,
           'data_detail'=>$data_detail,
           'data_product'=>$data_product,
           );
    }
     public function statusAction(){        
        $id=  $this->params()->fromPost('id_oder');
        $status=  $this->params()->fromPost('status');
       $data=array(
         'status_order'=>$status,
       );
       $obj=new Oder();
       $obj->exchangeArray($data);
       $this->getOrderTable()->update_status($id, $obj);
       echo 'Cập nhật thành công';
	   
	   //Log File
				$witelog = new Utility();
                $text = 'Cập nhật trạng thái hóa đơn ID = '.$id;
                $witelog->witelog($text);
                //--------------------------
        die;
    }
    public function deleteAction(){
        $this->layout('layout/user.phtml');
        $id_order=  $this->params()->fromRoute('id');
        $this->getOrderTable()->delete_oder($id_order);
        $this->getOrderDetailTable()->delete_oder_detail($id_order);
		
		 //Log File
				$witelog = new Utility();
                $text = 'Xóa hóa đơn hóa đơn ID = '.$id_order;
                $witelog->witelog($text);
                //--------------------------
        $this->redirect()->toRoute('Invoice');
    }
}
