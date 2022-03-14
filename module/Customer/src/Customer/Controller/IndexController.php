<?php

namespace Customer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Customer\Model\Customer;

class IndexController extends AbstractActionController {

    protected $Customer;
    public function getCustomerTable() {
        if (!$this->Customer) {
            $pst = $this->getServiceLocator();
            $this->Customer = $pst->get('Customer\Model\CustomerTable');
        }
        return $this->Customer;
    }
    
    protected $Order;
    public function getOrderTable() {
        if (!$this->Order) {
            $sm = $this->getServiceLocator();
            $this->Order = $sm->get('Invoice\Model\OderTable');
        }
        return $this->Order;
    }


    public function indexAction() {
        $this->layout('layout/user.phtml');
        $data = $this->getCustomerTable()->listacount();
        foreach ($data as $key=>$value){
            $id_customer =$value['id'];
            $count_oder[$id_customer] = $this->getOrderTable()->getoder_user($id_customer);
           
        }
        return array('data' => $data, 'count_oder'=>$count_oder);
    }
   
    public function viewAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');
        $data = $this->getCustomerTable()->acountdetail($id);       
        return array('data' => $data);
    }

    public function deleteAction() {
        $this->layout('layout/user.phtml');
        $id = $this->params()->fromRoute('id');       
        $this->getCustomerTable()->deletecustomer($id);
        $this->redirect()->toRoute('Customer');
    }

    public function statusAction(){
        $id=  addslashes(trim($this->params()->fromRoute('id',0)));
        $status=  addslashes(trim($this->params()->fromRoute('status',0)));
        if($status==0){
            $data=array('status'=>1);
        }  else {
            $data=array('status'=>0);
        }  
        $obj = new Customer();
        $obj->exchangeArray($data);
        $this->getCustomerTable()->changestatus($id, $obj);
        $this->redirect()->toRoute('Customer');
    }

}

?>