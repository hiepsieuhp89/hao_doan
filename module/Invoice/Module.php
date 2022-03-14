<?php

namespace Invoice;
use Invoice\Model\OderTable;
use Invoice\Model\OderdetailTable;
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
                'Invoice\Model\OderTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new OderTable($dbAdapter);
            return $table;
        },
            'Invoice\Model\OderdetailTable' => function($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $table = new OderdetailTable($dbAdapter);
            return $table;
        },       
               
		//End
            ),
        );
    }

}
