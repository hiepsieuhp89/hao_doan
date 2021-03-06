<?php

namespace Fronts\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Fronts\Model\View;
use Fronts\Model\Acount;
use Invoice\Model\Oder;
use Invoice\Model\Oderdetail;
use Fronts\Model\Setting;
use Product\Model\Utility;
use Zend\Session\Container;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ShoppingcartController extends AbstractActionController {

    protected $Acount;

    public function getAcountTable() {
        if (!$this->Acount) {
            $pst = $this->getServiceLocator();
            $this->Acount = $pst->get('Customer\Model\CustomerTable');
        }
        return $this->Acount;
    }

    protected $Product;

    public function getProductTable() {
        if (!$this->Product) {
            $pst = $this->getServiceLocator();
            $this->Product = $pst->get('Product\Model\ProductTable');
        }
        return $this->Product;
    }

    protected $Image;

    public function getImageTable() {
        if (!$this->Image) {
            $pst = $this->getServiceLocator();
            $this->Image = $pst->get('Product\Model\ImageTable');
        }
        return $this->Image;
    }

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

    public function addcartAction() {
        $container = new Container('shopcart');
        $quanlity = addslashes(trim($this->params()->fromPost("qty")));
        $productId = addslashes(trim($this->params()->fromPost("product")));
        $this->checkcart($productId, $quanlity);
// $this->redirect()->toUrl(WEB_PATH . '/shoppingcart/view-cart.html');
//        $mes = $this->checkcart($productId, $quanlity);
//Check Mobile;
        $ismobile = 0;
        $container_mb = $_SERVER['HTTP_USER_AGENT'];
        $useragents = array(
            'Android',
            'IEMobile',
            'iPhone',
            'iPad',
        );
        foreach ($useragents as $useragents) {
            if (strstr($container_mb, $useragents)) {
                $ismobile = 1;
            }
        }
        if ($ismobile == 1) {
//N???u L?? Mobile ch???y ch??? n??y
            echo '1';
            die;
        } else {
// N???u l?? Desktop s??? ch???y do???n n??y        

            $arraycart = $container->cart;
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }

                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">B???n c?? <span id="count-cart">' . count($arraycart) . '</span> s???n ph???m trong gi??? h??ng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">H??nh ???nh</th>
                        <th> T??n s???n ph???m</th>									
                        <th> Gi?? s???n ph???m</th>
                        <th>S??? l?????ng</th>
                        <th> Th??nh ti???n</th>
                        <th  class="action"><i class="fa fa-trash-o"></i></th>
                    </tr>
                </thead>
                <tbody>';

                $total_money = '';
                foreach ($listproduct as $key => $value) {
                    $qty = $arraycart[$value['id']];

                    echo '<tr>
                            <td class="cart_product">
                                <a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html"><img src="' . WEB_IMG .'images/'. $value['thumbnail'] . '" alt="' . $value['product_name'] . '" width="60" height="60"></a>
                            </td>
                            <td class="cart_description">
                                <p class="product-name"><a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html">' . $value['product_name'] . ' </a></p>

                            </td>
                            <td class="price">';

                    if ($value['sales'] == 1) {
                        //$price_sales = $value['price'] - ($value['price'] * $value['price_sales'] / 100);
                          $price_sales = $value['price_sales'];
                        echo '<span class="price product-price">' . number_format($price_sales, 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                             <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . '??</span><br/>                           

                            </td>
                            <td class="action">
                               <a href="javascript:void(0);" onclick="deletecart(' . $value['id'] . ')">Delete item</a>
                            </td>
                        </tr>';

                    $total_money +=$qty * $price_sales;
                } // en foreach
                echo '</tbody>
                <tfoot>

                    <tr>
                        <td colspan="4"><strong>T???ng ti???n</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . '??</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh To??n <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';


                die;
            } else {
                echo '<center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button><center>';
            }
        }//En?? Add Cart Desktop
    }

    private function checkcart($productId, $quanlity) {
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        $mes = "";
        if (isset($arraycart[$productId])) {
            $quanlityoffset = $arraycart[$productId];
            $quanlityupdate = $quanlityoffset + $quanlity;

            $arraycart[$productId] = $quanlityupdate;
            $mes = "C???p nh???t s???n ph???m gi??? h??ng th??nh c??ng.";
        } else {
            $arraycart[$productId] = $quanlity;
            $mes = "Th??m s???n ph???m v??o gi??? h??ng th??nh c??ng.";
        }
        $container->cart = $arraycart;
        return $mes;
    }

    public function viewcartAction() {
//Check Mobile;
        $ismobile = 0;
        $container_mb = $_SERVER['HTTP_USER_AGENT'];
        $useragents = array(
            'Android',
            'IEMobile',
            'iPhone',
            'iPad',
        );
        foreach ($useragents as $useragents) {
            if (strstr($container_mb, $useragents)) {
                $ismobile = 1;
            }
        }
        if ($ismobile == 1) {
//N???u L?? Mobile ch???y ch??? n??y
            echo '1';
            die;
        } else {
// N???u l?? Desktop s??? ch???y do???n n??y    
            $container = new Container('shopcart');

            $arraycart = $container->cart;
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }
                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">B???n c?? ' . count($arraycart) . ' s???n ph???m trong gi??? h??ng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">H??nh ???nh</th>
                        <th> T??n s???n ph???m</th>									
                        <th> Gi?? s???n ph???m</th>
                        <th>S??? l?????ng</th>
                        <th> Th??nh ti???n</th>
                        <th  class="action"><i class="fa fa-trash-o"></i></th>
                    </tr>
                </thead>
                <tbody>';

                $total_money = '';
                foreach ($listproduct as $key => $value) {
                    $qty = $arraycart[$value['id']];

                    echo '<tr>
                            <td class="cart_product">
                                <a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html"><img src="' . WEB_IMG .'images/'. $value['thumbnail'] . '" alt="' . $value['product_name'] . '" width="60" height="60"></a>
                            </td>
                            <td class="cart_description">
                                <p class="product-name"><a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html">' . $value['product_name'] . ' </a></p>

                            </td>
                            <td class="price">';

                    if ($value['sales'] == 1) {
                        //$price_sales = $value['price'] - ($value['price'] * $value['price_sales'] / 100);
                       $price_sales = $value['price_sales'];
echo '<span class="price product-price">' . number_format($price_sales , 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                            <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . '??</span><br/>                           

                            </td>
                            <td class="action">
                                <a href="javascript:void(0);" onclick="deletecart(' . $value['id'] . ')">Delete item</a>
                            </td>
                        </tr>';

                    $total_money +=$qty * $price_sales;
                } // en foreach
                echo '</tbody>
                <tfoot>

                    <tr>
                        <td colspan="4"><strong>T???ng ti???n</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . '??</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh To??n <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';

                die;
            } else {
                echo '<p class="title-cart">B???n c?? ' . count($arraycart) . ' s???n ph???m trong gi??? h??ng.</p>';
                echo '<div class="modal-footer">
                    <center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button><center>
                    </div>';
                die;
            }
        }
    }

    public function viewcartmobileAction() {
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/shoppingcart/view-cart.html"><span>Gi??? h??ng</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        if ($arraycart != null) {
            foreach ($arraycart as $key => $value) {
                $arrayproduct[] = $key;
            }

            $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
            return array('listproduct' => $listproduct);
        }
		
    }

    public function checkoutAction() {
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/shoppingcart/checkout.html"><span>Thanh to??n</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        if ($arraycart == null) {
            $this->redirect()->toUrl(WEB_PATH . '/shoppingcart/view-cart.html');
        }
		
    }

    public function postcheckoutAction() {

//load email h??? th???ng
		 $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
		//----------	
        $email_admin = $setting->email_admin;
        $email_customer = $setting->email_customer;
		$mail_system = $setting->email_system;	
       
        $session_user = new Container('userlogin');
        $id_user = $session_user->idus;
        if ($id_user == null) {
            $id_customer = '0';
        } else {
            $id_customer = $id_user;
        }

        $session_customer_guest = new Container('customer_guest'); //th??ng tin kh??ch h??ng ch??a d??ng k??
        $name_customer_guest = $session_customer_guest->name_customer;

        $container = new Container('shopcart');
        $arraycart = $container->cart;
        $name = addslashes(trim($this->params()->fromPost('name')));
        $email = addslashes(trim($this->params()->fromPost('email')));
        $phone = addslashes(trim($this->params()->fromPost('phone')));
        $address = addslashes(trim($this->params()->fromPost('address')));
        $content = addslashes(trim($this->params()->fromPost('content')));
        $get_codeoder = $this->getOrderTable()->get_code_oder();
        $code_oder = $get_codeoder['code_oder'] +=1;

// L??u th??ng tin kh??ch h??ng khi ch??a ????ng k??
        if ($id_user == null && $name_customer_guest == null) {
            $session_customer_guest = new Container('customer_guest');
            $session_customer_guest->name_customer = $name;
            $session_customer_guest->mail_customer = $email;
            $session_customer_guest->phone_customer = $phone;
            $session_customer_guest->address_customer = $address;
        }

        foreach ($arraycart as $key => $value) { // M???ng s???n ph???m trong gi??? h??ng
            $arrayproduct[] = $key;
        }
        $total_money = '';
        $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
        foreach ($listproduct as $key => $value) {
            $qty = $arraycart[$value['id']];
//check xem c?? ph???i s???n ph???m khuy???n m??i hay kh??ng.
            if ($value['sales'] == 1) {
                //$price_sales = $value['price'] - ($value['price'] * $value['price_sales'] / 100);
                $price_sales = $value['price_sales'];
            } else {
                $price_sales = $value['price'];
            }
            $money = $qty * $price_sales;
            $total_money +=$money;
        }
        $data = array(
            'code_oder' => $code_oder,
            'id_customer' => $id_customer,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'total_money' => $total_money,
            'content' => $content,
        );
        $obj = new Oder();
        $obj->exchangeArray($data);
        $this->getOrderTable()->addoder($obj);

//Order detail
        $getdernew = $this->getOrderTable()->getoder_new(); //L???y Id oder m??i th??m v??o
        $id_oder = $getdernew['id'];
        $list_product_mail = '';
        $list_product_mail_admin = '';
        foreach ($listproduct as $key1 => $value1) {
            $qty1 = $arraycart[$value1['id']];
//check xem c?? ph???i s???n ph???m khuy???n m??i hay kh??ng.
            if ($value1['sales'] == 1) {
                //$price_sales1 = $value1['price'] - ($value1['price'] * $value1['price_sales'] / 100);
                 $price_sales1 =  $value1['price_sales'];   
            } else {
                $price_sales1 = $value1['price'];
            }
            $data_oder_detail = array(
                'id_order' => $id_oder,
                'id_product' => $value1['id'],
                'quantity' => $qty1,
                'price' => $price_sales1
            );
            $list_product_mail .= "
                                 <tbody>
                                <tr style='background-color: #ebecee;text-align: center;'>
                                    <td style='padding: 0.6em 0.4em 0.6em 1em;text-align: left;'>" . $value1['product_name'] . "</td>                                    
                                    <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price_sales1, 0) . " VN??</td>   
                                    <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty1 . " " . $value1['product_dv'] . " </td>
                                    <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($qty1 * $price_sales1, 0) . " VN??</td>
                                </tr>
                                </tbody>
                            ";
            $obj_dt = new Oderdetail();
            $obj_dt->exchangeArray($data_oder_detail);
            $this->getOrderDetailTable()->addoder_detail($obj_dt);
        }


// G???i thong tin h??a ????n ????n Mail kh??ch h??ng
        $date = date('Y-m-d H:i:s');
        $content_mail = "
          <p id='display_oder'>C??m ??n qu?? kh??ch h??ng ???? quan t??m v?? ?????t h??ng c???a ch??ng t??i.
          Sau khi x??c nh???n ????n h??ng ch??ng t??i s??? giao h??ng cho qu?? kh??ch trong th???i gian s???m nh???t.</p>
          
          <p style='line-height:30px'><i class='glyphicon glyphicon-briefcase' style='line-height:30px'></i> Kh??ch h??ng: $name</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-time' style='line-height:30px'></i> Ng??y gi??? ?????t h??ng: $date</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-phone' style='line-height:30px'></i> S??? ??i???n tho???i: $phone</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-home' style='line-height:30px'></i> ?????a ch??? nh???n h??ng: $address</p>
           <table border='0' style='width:100%;color:#000;' class='table table-bordered table-responsive cart_summary'>
         <thead>
        <tr style='background-color:#02885B;text-align:center;line-height: 22px;color:#f6f7f8' >
            <th>T??n s???n ph???m</th>            
            <th>Gi??</th>
            <th>S??? l?????ng</th>
            <th>Th??nh ti???n</th>
        </tr>
        </thead>
       $list_product_mail
        <tfoot>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #FFFFFF;padding:0.6em 0.4 em;'><strong>T???ng ti???n:</strong></td>
            <td style='background-color: #FFFFFF;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VN??</strong></td>
        </tr>
      </tfoot>
    </table>";

       //View th??ng tin h??a ????n
         $odersucess = new Container('oder');
        $odersucess->getoder=$content_mail;
//G???i th??ng tin h??a ????n ?????n Email kh??ch h??ng n???u kh??ch h??ng ??i???n mail
        if ($email != null) {
            $this->sendmail($email, $email_admin, 'H??a ????n ?????t h??ng t???i Giadung88.com. M?? h??a ????n: ' . $code_oder, $content_mail);
        }
// g???i y??u c???u ?????t h??ng ?????n qu???n tr???
        $content_mail1 = " 
            <p>Giadung88.com c?? 1 y???u c???u ?????t h??ng m???i t??? $name</p>
            <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>T??n s???n ph???m</th>            
            <th>Gi??</th>
            <th>S??? l?????ng</th>
            <th>Th??nh ti???n</th>
        </tr>
       $list_product_mail
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>T???ng ti???n:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VN??</strong></td>
        </tr>
    </table> 
            <h4>?????a ch??? nh???n h??ng:</h4>
            <p>$name</p>
            <p>$address</p>
            <p>??i???n tho???i: $phone </p>
                ";
        $this->sendmail($email_customer, $mail_system, 'Y???u c???u ?????t h??ng t???i Giadung88.com. M?? h??a ????n: ' . $code_oder, $content_mail1);

//x??a gi??? h??ng khi thanh to??n xong
        unset($arraycart);
        $container->cart = $arraycart;
        echo '1';
        die;
    }

    public function checkoutsucessAction() {
        $this->getlayout();
        $title_page = '<li><a href="#"><span>?????t h??ng</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
		
    }

    public function deletecartAction() {
//$this->getlayout();
        $productId = $this->params()->fromPost('id');
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        unset($arraycart[$productId]);
        $container->cart = $arraycart;
        //Check Mobile;
        $ismobile = 0;
        $container_mb = $_SERVER['HTTP_USER_AGENT'];
        $useragents = array(
            'Android',
            'IEMobile',
            'iPhone',
            'iPad',
        );
        foreach ($useragents as $useragents) {
            if (strstr($container_mb, $useragents)) {
                $ismobile = 1;
            }
        }
        if ($ismobile == 1) {
//N???u L?? Mobile ch???y ch??? n??y
            echo '1';
            die;
        } else {
// N???u l?? Desktop s??? ch???y do???n n??y      
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }
                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">B???n c?? <span id="count-cart">' . count($arraycart) . '</span> s???n ph???m trong gi??? h??ng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">H??nh ???nh</th>
                        <th> T??n s???n ph???m</th>									
                        <th> Gi?? s???n ph???m</th>
                        <th>S??? l?????ng</th>
                        <th> Th??nh ti???n</th>
                        <th  class="action"><i class="fa fa-trash-o"></i></th>
                    </tr>
                </thead>
                <tbody>';

                $total_money = '';
                foreach ($listproduct as $key => $value) {
                    $qty = $arraycart[$value['id']];

                    echo '<tr>
                            <td class="cart_product">
                                <a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html"><img src="' . WEB_IMG .'images/'. $value['thumbnail'] . '" alt="' . $value['product_name'] . '" width="60" height="60"></a>
                            </td>
                            <td class="cart_description">
                                <p class="product-name"><a href="' . WEB_PATH . '/san-pham/' . $value['alias'] . '.html">' . $value['product_name'] . ' </a></p>

                            </td>
                            <td class="price">';

                    if ($value['sales'] == 1) {
                       // $price_sales = $value['price'] - ($value['price'] * $value['price_sales'] / 100);
                           $price_sales = $value['price_sales'];
                        echo '<span class="price product-price">' . number_format($price_sales, 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . '?? / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                            <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . '??</span><br/>                           

                            </td>
                            <td class="action">
                                <a href="javascript:void(0);" onclick="deletecart(' . $value['id'] . ')">Delete item</a>
                            </td>
                        </tr>';

                    $total_money +=$qty * $price_sales;
                } // en foreach
                echo '</tbody>
                <tfoot>

                    <tr>
                        <td colspan="4"><strong>T???ng ti???n</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . '??</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh To??n <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';

                die;
            } else {
                echo '<p class="title-cart">B???n c?? ' . count($arraycart) . ' s???n ph???m trong gi??? h??ng.</p>';
                echo '<div class="modal-footer">
                    <center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Ti???p T???c Mua H??ng</button><center>
                    </div>';
                die;
            }
        }
    }

    public function clearcartAction() {
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        unset($arraycart);
        $container->cart = $arraycart;
        $this->redirect()->toUrl(WEB_PATH . '/shoppingcart/view-cart.html');
    }

    public function bynowAction() {
//load email h??? th???ng
		 $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
		//----------	
		$session_email = new Container('emailsystem');

        $session_email->email_admin =$setting->email_admin;

        $session_email->email_customer =$setting->email_customer;

        $session_email->email_system =$setting->email_system;	
			
		//-------------------	
		
       
        $email_admin = $session_email->email_admin;
        $email_customer = $session_email->email_customer;
        $mail_system = $session_email->email_system;

        $session_user = new Container('userlogin');
        $id_user = $session_user->idus;
        if ($id_user == null) {
            $id_customer = '0';
        } else {
            $id_customer = $id_user;
        }
        $session_customer_guest = new Container('customer_guest'); //th??ng tin kh??ch h??ng ch??a d??ng k??
        $name_customer_guest = $session_customer_guest->name_customer;

        $id_product = addslashes(trim($this->params()->fromPost('id_product')));
        $product_name = addslashes(trim($this->params()->fromPost('product_name')));
        $qty = addslashes(trim($this->params()->fromPost('number')));
        $price = addslashes(trim($this->params()->fromPost('price')));
        $name = addslashes(trim($this->params()->fromPost('name')));
        $email = addslashes(trim($this->params()->fromPost('email')));
        $phone = addslashes(trim($this->params()->fromPost('phone')));
        $address = addslashes(trim($this->params()->fromPost('address')));
        $content = addslashes(trim($this->params()->fromPost('content')));
        $total_money = $qty * $price;
        $get_codeoder = $this->getOrderTable()->get_code_oder();
        $code_oder = $get_codeoder['code_oder'] +=1;

// L??u th??ng tin kh??ch h??ng khi ch??a ????ng k??
        if ($id_user == null && $name_customer_guest == null) {
            $session_customer_guest = new Container('customer_guest');
            $session_customer_guest->name_customer = $name;
            $session_customer_guest->mail_customer = $email;
            $session_customer_guest->phone_customer = $phone;
            $session_customer_guest->address_customer = $address;
        }


        if ($qty == 0) {
            echo '0';
            die;
        }
        $data = array(
            'code_oder' => $code_oder,
            'id_customer' => $id_customer,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'total_money' => $total_money,
            'content' => $content,
        );

        $obj = new Oder();
        $obj->exchangeArray($data);
        $this->getOrderTable()->addoder($obj);

        $getdernew = $this->getOrderTable()->getoder_new(); //L???y Id oder m??i th??m v??o
        $id_oder = $getdernew['id'];
        $data_oder_detail = array(
            'id_order' => $id_oder,
            'id_product' => $id_product,
            'quantity' => $qty,
            'price' => $price
        );
        $obj_dt = new Oderdetail();
        $obj_dt->exchangeArray($data_oder_detail);
        $this->getOrderDetailTable()->addoder_detail($obj_dt);

// G???i thong tin h??a ????n ????n Mail kh??ch h??ng
        $date = date('Y-m-d H:i:s');
        $content_mail = "
          <p>C??m ??n qu?? kh??ch h??ng ???? quan t??m v?? ?????t h??ng c???a ch??ng t??i.
          Sau khi x??c nh???n ????n h??ng ch??ng t??i s??? giao h??ng cho qu?? kh??ch trong th???i gian s???m nh???t.</p>
          
          <p style='line-height:25px'>Kh??ch h??ng: $name</p>
          <p style='line-height:20px'>M??  ????n h??ng: $code_oder</p>
          <p style='line-height:20px'>Ng??y gi??? ?????t h??ng: $date</p>
          <p style='line-height:20px'>S??? ??i???n tho???i: $phone</p>
          <p style='line-height:20px'>?????a ch??? nh???n h??ng: $address</p>
           <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>T??n s???n ph???m</th>            
            <th>Gi??</th>
            <th>S??? l?????ng</th>
            <th>Th??nh ti???n</th>
        </tr>
        <tr style='background-color: #ebecee;text-align: center;'>
            <td style='padding: 0.6em 0.4em 0.6em 1em;text-align: left;'>" . $product_name . "</td>                                     
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price, 0) . " VN??</td>   
            <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty . " </td>
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($total_money, 0) . " VN??</td>
        </tr>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>T???ng ti???n:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VN??</strong></td>
        </tr>
    </table>";

//G???i th??ng tin h??a ????n ?????n Email kh??ch h??ng n???u kh??ch h??ng nh???p mail
        if ($email != null) {
            $this->sendmail($email, $email_admin, 'H??a ????n ?????t h??ng t???i Giadung88.com. M?? h??a ????n: ' . $code_oder, $content_mail);
        }
// g???i y??u c???u ?????t h??ng ?????n qu???n tr???
        $content_mail1 = " 
            <p>Giadung88.com c?? 1 y???u c???u ?????t h??ng m???i t??? $name</p>
            <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>T??n s???n ph???m</th>            
            <th>Gi??</th>
            <th>S??? l?????ng</th>
            <th>Th??nh ti???n</th>
        </tr>
        <tr style='background-color: #ebecee;text-align: center;'>
            <td style='padding: 0.6em 0.4em 0.6em 1em;text-align: left;'>" . $product_name . "</td>                                     
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price, 0) . " VN??</td>   
            <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty . " </td>
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($total_money, 0) . " VN??</td>
        </tr>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>T???ng ti???n:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VN??</strong></td>
        </tr>
    </table>
            <h4>?????a ch??? nh???n h??ng:</h4>
            <p>$name</p>
            <p>$address</p>
            <p>??i???n tho???i: $phone </p>
                ";
        $this->sendmail($email_customer, $mail_system, 'Y???u c???u ?????t h??ng t???i Giadung88.com. M?? h??a ????n: ' . $code_oder, $content_mail1);

        echo '1';
        die;
    }
	
	public function EmailMaketingAction() { 
	//die('00000000');
        $this->getlayout();     

        //Tr??? l???i kh??ch h??ng
        if ($this->request->isPost()) {
            $title = addslashes(trim($this->params()->fromPost('name')));
			$email_nhan = addslashes(trim($this->params()->fromPost('email')));
            $content = $this->params()->fromPost('content');
           $mail_from ='xuandac990@gmail.com';
            
           $this->sendmail($email_nhan, $mail_from, $title, $content);
            $alert='Th??ng tin ???? ???????c g???i ?????n kh??ch h??ng';
            return array('alert'=>$alert,);
        }

        //return array('data' => $data);
    }

    public function getlayout() {
        $this->layout('layoutshopping');
        $data_cat = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'cat',));
        $data_parent = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'catparent',));
        $product_left = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'productleft',));
        $img_product = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'loadimg',));
        $data_banner = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'banner',));
        $acticre = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'acticre',));
        $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));
		//----------	
		$session_email = new Container('emailsystem');

        $session_email->email_admin =$setting->email_admin;

        $session_email->email_customer =$setting->email_customer;

        $session_email->email_system =$setting->email_system;	
			
		//-------------------	
        $this->layout()->setVariable('data_cat', $data_cat);
        $this->layout()->setVariable('data_parent', $data_parent);
        $this->layout()->setVariable('product_left', $product_left);
        $this->layout()->setVariable('img_product', $img_product);
        $this->layout()->setVariable('data_banner', $data_banner);
        $this->layout()->setVariable('acticre', $acticre);
        $this->layout()->setVariable('setting', $setting);

//seo
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set(stripcslashes($setting->seo_title));
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headMeta()->setName('keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('news_keywords', stripcslashes($setting->seo_keyword));
        $renderer->headMeta()->setName('description', stripcslashes($setting->seo_description));
//end seo
    }
	
	

    public function sendmail($mail_to, $mail_from, $title_mail, $content_mail) {
//load email h??? th???ng
		 $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));     
        $mail_system = $setting->email_system;
        $pass_system = $setting->pass_system;
//G???i ???????c c??? html v?? text
        $message = new Message();
        $message->addTo($mail_to)//Email nh???n
                ->addFrom($mail_from)//Email g???i
                ->setSubject($title_mail); //Ti??u ????? mail
// Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        $options = new SmtpOptions(array(
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'ssl' => 'tls',
                'username' => $mail_system,
                'password' => $pass_system
            ),
            'port' => 587,
        ));
        $content = $content_mail; // N???i dung Email
        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);

        $message->setBody($body);

        $transport->setOptions($options);
        $transport->send($message);
    }

}
