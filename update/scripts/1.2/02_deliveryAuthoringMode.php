<?php 
$userService = core_kernel_users_Service::singleton();
$userService->login(SYS_USER_LOGIN, SYS_USER_PASS, new core_kernel_classes_Class('http://www.tao.lu/Ontologies/TAO.rdf#TaoManagerRole'));
error_reporting(E_ALL);

$dbWarpper = core_kernel_classes_DbWrapper::singleton();


//get all instance of deliveries:
$deliveryMainClass = new core_kernel_classes_Class(TAO_DELIVERY_CLASS);

$propAuthoringMode = new core_kernel_classes_Property('http://www.tao.lu/Ontologies/TAODelivery.rdf#AuthoringMode');
$updatedDelivery = array();
foreach($deliveryMainClass->getInstances(true) as $delivery){
	//for each of them, check if an authoring mode is set:
	$authoringMode = $delivery->getOnePropertyValue($propAuthoringMode);
	if(is_null($authoringMode)){
		//if not, set it to simple mode:
		$delivery->setPropertyValue($propAuthoringMode, 'http://www.tao.lu/Ontologies/TAODelivery.rdf#i1268049036038811802');//TAO_DELIVERY_SIMPLEMODE
		$updatedDelivery[$delivery->uriResource] = $delivery;
	}
}

echo 'done';

?>