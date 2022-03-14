<?php

namespace Product;
use Product\Model\ProductTablecategory;
use Product\Model\ProductTable;
use Product\Model\ProductTablemanufacture;
use Product\Model\ImageTable;
use Product\Model\XuatxuTable;
use Product\Model\ChatlieuTable;
class Module {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
			//Khai báo model tại đât
                'Product\Model\ProductTablecategory' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new ProductTablecategory($dbAdapter);
            return $table;
        },
                 'Product\Model\ProductTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new ProductTable($dbAdapter);
            return $table;
        },
                 'Product\Model\ProductTablemanufacture' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new ProductTablemanufacture($dbAdapter);
            return $table;
        },
                 'Product\Model\ImageTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new ImageTable($dbAdapter);
            return $table;
        },
                
                 'Product\Model\XuatxuTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new XuatxuTable($dbAdapter);
            return $table;
        },
                'Product\Model\ChatlieuTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new ChatlieuTable($dbAdapter);
            return $table;
        },
                
		//End
            ),
        );
    }

}
