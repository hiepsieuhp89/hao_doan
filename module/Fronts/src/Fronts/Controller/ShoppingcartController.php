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
//Nếu Là Mobile chạy chỗ này
            echo '1';
            die;
        } else {
// Nếu là Desktop sẽ chạy doạn này        

            $arraycart = $container->cart;
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }

                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">Bạn có <span id="count-cart">' . count($arraycart) . '</span> sản phẩm trong giỏ hàng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">Hình ảnh</th>
                        <th> Tên sản phẩm</th>									
                        <th> Giá sản phẩm</th>
                        <th>Số lượng</th>
                        <th> Thành tiền</th>
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
                        echo '<span class="price product-price">' . number_format($price_sales, 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                             <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . 'đ</span><br/>                           

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
                        <td colspan="4"><strong>Tổng tiền</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . 'đ</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh Toán <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';


                die;
            } else {
                echo '<center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button><center>';
            }
        }//Enđ Add Cart Desktop
    }

    private function checkcart($productId, $quanlity) {
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        $mes = "";
        if (isset($arraycart[$productId])) {
            $quanlityoffset = $arraycart[$productId];
            $quanlityupdate = $quanlityoffset + $quanlity;

            $arraycart[$productId] = $quanlityupdate;
            $mes = "Cập nhật sản phẩm giỏ hàng thành công.";
        } else {
            $arraycart[$productId] = $quanlity;
            $mes = "Thêm sản phẩm vào giỏ hàng thành công.";
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
//Nếu Là Mobile chạy chỗ này
            echo '1';
            die;
        } else {
// Nếu là Desktop sẽ chạy doạn này    
            $container = new Container('shopcart');

            $arraycart = $container->cart;
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }
                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">Bạn có ' . count($arraycart) . ' sản phẩm trong giỏ hàng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">Hình ảnh</th>
                        <th> Tên sản phẩm</th>									
                        <th> Giá sản phẩm</th>
                        <th>Số lượng</th>
                        <th> Thành tiền</th>
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
echo '<span class="price product-price">' . number_format($price_sales , 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                            <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . 'đ</span><br/>                           

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
                        <td colspan="4"><strong>Tổng tiền</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . 'đ</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh Toán <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';

                die;
            } else {
                echo '<p class="title-cart">Bạn có ' . count($arraycart) . ' sản phẩm trong giỏ hàng.</p>';
                echo '<div class="modal-footer">
                    <center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button><center>
                    </div>';
                die;
            }
        }
    }

    public function viewcartmobileAction() {
        $this->getlayout();
        $title_page = '<li><a href="' . WEB_PATH . '/shoppingcart/view-cart.html"><span>Giỏ hàng</span></a></li>';
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
        $title_page = '<li><a href="' . WEB_PATH . '/shoppingcart/checkout.html"><span>Thanh toán</span></a></li>';
        $this->layout()->setVariable('title_page', $title_page);
        $container = new Container('shopcart');
        $arraycart = $container->cart;
        if ($arraycart == null) {
            $this->redirect()->toUrl(WEB_PATH . '/shoppingcart/view-cart.html');
        }
		
    }

    public function postcheckoutAction() {

//load email hệ thống
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

        $session_customer_guest = new Container('customer_guest'); //thông tin khách hàng chưa dăng ký
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

// Lưu thông tin khách hàng khi chưa đăng ký
        if ($id_user == null && $name_customer_guest == null) {
            $session_customer_guest = new Container('customer_guest');
            $session_customer_guest->name_customer = $name;
            $session_customer_guest->mail_customer = $email;
            $session_customer_guest->phone_customer = $phone;
            $session_customer_guest->address_customer = $address;
        }

        foreach ($arraycart as $key => $value) { // Mảng sản phẩm trong giỏ hàng
            $arrayproduct[] = $key;
        }
        $total_money = '';
        $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
        foreach ($listproduct as $key => $value) {
            $qty = $arraycart[$value['id']];
//check xem có phải sản phẩm khuyến mãi hay không.
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
        $getdernew = $this->getOrderTable()->getoder_new(); //Lấy Id oder mói thêm vào
        $id_oder = $getdernew['id'];
        $list_product_mail = '';
        $list_product_mail_admin = '';
        foreach ($listproduct as $key1 => $value1) {
            $qty1 = $arraycart[$value1['id']];
//check xem có phải sản phẩm khuyến mãi hay không.
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
                                    <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price_sales1, 0) . " VNĐ</td>   
                                    <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty1 . " " . $value1['product_dv'] . " </td>
                                    <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($qty1 * $price_sales1, 0) . " VNĐ</td>
                                </tr>
                                </tbody>
                            ";
            $obj_dt = new Oderdetail();
            $obj_dt->exchangeArray($data_oder_detail);
            $this->getOrderDetailTable()->addoder_detail($obj_dt);
        }


// Gửi thong tin hóa đơn đên Mail khách hàng
        $date = date('Y-m-d H:i:s');
        $content_mail = "
          <p id='display_oder'>Cám ơn quý khách hàng đã quan tâm và đặt hàng của chúng tôi.
          Sau khi xác nhận đơn hàng chúng tôi sẽ giao hàng cho quý khách trong thời gian sớm nhất.</p>
          
          <p style='line-height:30px'><i class='glyphicon glyphicon-briefcase' style='line-height:30px'></i> Khách hàng: $name</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-time' style='line-height:30px'></i> Ngày giờ đặt hàng: $date</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-phone' style='line-height:30px'></i> Số điện thoại: $phone</p>
          <p style='line-height:30px'><i class='glyphicon glyphicon-home' style='line-height:30px'></i> Địa chỉ nhận hàng: $address</p>
           <table border='0' style='width:100%;color:#000;' class='table table-bordered table-responsive cart_summary'>
         <thead>
        <tr style='background-color:#02885B;text-align:center;line-height: 22px;color:#f6f7f8' >
            <th>Tên sản phẩm</th>            
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
       $list_product_mail
        <tfoot>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #FFFFFF;padding:0.6em 0.4 em;'><strong>Tổng tiền:</strong></td>
            <td style='background-color: #FFFFFF;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VNĐ</strong></td>
        </tr>
      </tfoot>
    </table>";

       //View thông tin hóa đơn
         $odersucess = new Container('oder');
        $odersucess->getoder=$content_mail;
//Gửi thông tin hóa đơn đến Email khách hàng nếu khách hàng điền mail
        if ($email != null) {
            $this->sendmail($email, $email_admin, 'Hóa đơn đặt hàng tại Giadung88.com. Mã hóa đơn: ' . $code_oder, $content_mail);
        }
// gửi yêu cấu đặt hàng đến quản trị
        $content_mail1 = " 
            <p>Giadung88.com có 1 yều cầu đặt hàng mới từ $name</p>
            <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>Tên sản phẩm</th>            
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
       $list_product_mail
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>Tổng tiền:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VNĐ</strong></td>
        </tr>
    </table> 
            <h4>Địa chỉ nhận hàng:</h4>
            <p>$name</p>
            <p>$address</p>
            <p>Điện thoại: $phone </p>
                ";
        $this->sendmail($email_customer, $mail_system, 'Yều cầu đặt hàng tại Giadung88.com. Mã hóa đơn: ' . $code_oder, $content_mail1);

//xóa giỏ hàng khi thanh toán xong
        unset($arraycart);
        $container->cart = $arraycart;
        echo '1';
        die;
    }

    public function checkoutsucessAction() {
        $this->getlayout();
        $title_page = '<li><a href="#"><span>Đặt hàng</span></a></li>';
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
//Nếu Là Mobile chạy chỗ này
            echo '1';
            die;
        } else {
// Nếu là Desktop sẽ chạy doạn này      
            if ($arraycart != null) {
                foreach ($arraycart as $key => $value) {
                    $arrayproduct[] = $key;
                }
                $listproduct = $this->getProductTable()->product_shoppingcart($arrayproduct);
                echo '<p class="title-cart">Bạn có <span id="count-cart">' . count($arraycart) . '</span> sản phẩm trong giỏ hàng.</p>';
                echo '<table class="table table-bordered table-responsive cart_summary">
                <thead>
                    <tr>
                        <th class="cart_product">Hình ảnh</th>
                        <th> Tên sản phẩm</th>									
                        <th> Giá sản phẩm</th>
                        <th>Số lượng</th>
                        <th> Thành tiền</th>
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
                        echo '<span class="price product-price">' . number_format($price_sales, 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span><br/>';
                        echo '<span class="price old-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    } else {
                        $price_sales = $value['price'];
                        echo '<span class="price product-price">' . number_format($value['price'], 0, ',', '.') . 'đ / ' . $value['product_dv'] . '</span>';
                    }

                    echo '</td>
                            <td class="qty">   
                           <input type="hidden" id="qty_old' . $value['id'] . '" value="' . $qty . '" />
                           <input type="text" id="number-update' . $value['id'] . '" value="' . $qty . '"/>
                             <i onclick="updatecart(' . $value['id'] . ',1);" class="fa fa-minus fa-minus-cart"></i>
                             <i onclick="updatecart(' . $value['id'] . ',2);" class="fa fa-plus fa-plus-cart"></i>   
                                   
                            </td>
                            <td class="price">                                                              
                                <span class="price product-price">' . number_format($qty * $price_sales, 0, ',', '.') . 'đ</span><br/>                           

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
                        <td colspan="4"><strong>Tổng tiền</strong></td>
                        <td colspan="2"><strong>' . number_format($total_money, 0, ',', '.') . 'đ</strong></td>
                    </tr>
                </tfoot>    
            </table> 
            <div class="modal-footer">
        <button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button>
        <a href="' . WEB_PATH . '/shoppingcart/checkout.html">
            <button class="button pull-right">Thanh Toán <i class="fa fa-arrow-circle-right"></i></button>
            </a>
            </div>';

                die;
            } else {
                echo '<p class="title-cart">Bạn có ' . count($arraycart) . ' sản phẩm trong giỏ hàng.</p>';
                echo '<div class="modal-footer">
                    <center><button type="button" class="button btn-primary pull-left" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Tiếp Tục Mua Hàng</button><center>
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
//load email hệ thống
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
        $session_customer_guest = new Container('customer_guest'); //thông tin khách hàng chưa dăng ký
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

// Lưu thông tin khách hàng khi chưa đăng ký
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

        $getdernew = $this->getOrderTable()->getoder_new(); //Lấy Id oder mói thêm vào
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

// Gửi thong tin hóa đơn đên Mail khách hàng
        $date = date('Y-m-d H:i:s');
        $content_mail = "
          <p>Cám ơn quý khách hàng đã quan tâm và đặt hàng của chúng tôi.
          Sau khi xác nhận đơn hàng chúng tôi sẽ giao hàng cho quý khách trong thời gian sớm nhất.</p>
          
          <p style='line-height:25px'>Khách hàng: $name</p>
          <p style='line-height:20px'>Mã  đơn hàng: $code_oder</p>
          <p style='line-height:20px'>Ngày giờ đặt hàng: $date</p>
          <p style='line-height:20px'>Số điện thoại: $phone</p>
          <p style='line-height:20px'>Địa chỉ nhận hàng: $address</p>
           <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>Tên sản phẩm</th>            
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        <tr style='background-color: #ebecee;text-align: center;'>
            <td style='padding: 0.6em 0.4em 0.6em 1em;text-align: left;'>" . $product_name . "</td>                                     
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price, 0) . " VNĐ</td>   
            <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty . " </td>
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($total_money, 0) . " VNĐ</td>
        </tr>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>Tổng tiền:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VNĐ</strong></td>
        </tr>
    </table>";

//Gửi thông tin hóa đơn đến Email khách hàng nếu khách hàng nhập mail
        if ($email != null) {
            $this->sendmail($email, $email_admin, 'Hóa đơn đặt hàng tại Giadung88.com. Mã hóa đơn: ' . $code_oder, $content_mail);
        }
// gửi yêu cấu đặt hàng đến quản trị
        $content_mail1 = " 
            <p>Giadung88.com có 1 yều cầu đặt hàng mới từ $name</p>
            <table border='0' style='width:100%;color:#000;'>
        <tr style='background-color:#b9babe;text-align:center;line-height: 22px;'>
            <th>Tên sản phẩm</th>            
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        <tr style='background-color: #ebecee;text-align: center;'>
            <td style='padding: 0.6em 0.4em 0.6em 1em;text-align: left;'>" . $product_name . "</td>                                     
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($price, 0) . " VNĐ</td>   
            <td style='padding: 0.6em 0.4em;text-align: center;'>" . $qty . " </td>
            <td style='padding: 0.6em 0.4em;text-align: right;'>" . number_format($total_money, 0) . " VNĐ</td>
        </tr>
        <tr style='text-align:right; line-height: 22px;'>            
            <td colspan='3' style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>Tổng tiền:</strong></td>
            <td style='background-color: #dde2e6;padding:0.6em 0.4 em;'><strong>" . number_format($total_money, 0) . " VNĐ</strong></td>
        </tr>
    </table>
            <h4>Địa chỉ nhận hàng:</h4>
            <p>$name</p>
            <p>$address</p>
            <p>Điện thoại: $phone </p>
                ";
        $this->sendmail($email_customer, $mail_system, 'Yều cầu đặt hàng tại Giadung88.com. Mã hóa đơn: ' . $code_oder, $content_mail1);

        echo '1';
        die;
    }
	
	public function EmailMaketingAction() { 
	//die('00000000');
        $this->getlayout();     

        //Trả lời khách hàng
        if ($this->request->isPost()) {
            $title = addslashes(trim($this->params()->fromPost('name')));
			$email_nhan = addslashes(trim($this->params()->fromPost('email')));
            $content = $this->params()->fromPost('content');
           $mail_from ='xuandac990@gmail.com';
            
           $this->sendmail($email_nhan, $mail_from, $title, $content);
            $alert='Thông tin đã được gửi đến khách hàng';
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
//load email hệ thống
		 $setting = $this->forward()->dispatch('Fronts\Controller\Elements', array(
            'action' => 'setting',));     
        $mail_system = $setting->email_system;
        $pass_system = $setting->pass_system;
//Gửi được cả html và text
        $message = new Message();
        $message->addTo($mail_to)//Email nhận
                ->addFrom($mail_from)//Email gửi
                ->setSubject($title_mail); //Tiêu đề mail
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
        $content = $content_mail; // Nội dung Email
        $html = new MimePart($content);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);

        $message->setBody($body);

        $transport->setOptions($options);
        $transport->send($message);
    }

}
