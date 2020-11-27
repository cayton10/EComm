<?

    //Include config file
    require_once(__DIR__ . "/../config/config.php");

    //Include info required for grabielbull's ups-api wrapper
    require_once(__DIR__ . "/../classes/vendor/autoload.php");


    
    //Posted information from ajax call on checkout page
    $weight = $_POST['wt'];
    $zipCode = $_POST['zip'];

    //Store service type in variable for setting down the road.
    $serviceType = '03';//Code 03 is for UPS Ground Shipping

    $rate = new Ups\Rate(
        $accessKey = UPSAPIKey,//Populate required UPS info from constants in config file
        $userID = UPSUserName,
        $password = UPSUserPW
    );

    try {
        $shipment = new \Ups\Entity\Shipment();

        $shipperAddress = $shipment->getShipper()->getAddress();
        $shipperAddress->setPostalCode('45619');//Hard coded for project reqs

        $address = new \Ups\Entity\Address();
        $address->setPostalCode('45619');//Hard coded for project reqs
        $shipFrom = new \Ups\Entity\ShipFrom();
        $shipFrom->setAddress($address);


        //Set service type (3 Day Select vs Ground, etc.)
        $service = new \Ups\Entity\Service();
        $service->setCode($serviceType);

        $shipment->setService($service);
 

        $shipment->setShipFrom($shipFrom);


        $shipTo = $shipment->getShipTo();
        $shipTo->setCompanyName('Test Ship To');
        $shipToAddress = $shipTo->getAddress();
        $shipToAddress->setPostalCode($zipCode);

        $package = new \Ups\Entity\Package();
        $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_UNKNOWN);
        $package->getPackageWeight()->setWeight($weight);
        
        // if you need this (depends of the shipper country)
        /*$weightUnit = new \Ups\Entity\UnitOfMeasurement;
        $weightUnit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_KGS);
        $package->getPackageWeight()->setUnitOfMeasurement($weightUnit);*/

        $dimensions = new \Ups\Entity\Dimensions();
        $dimensions->setHeight(10);
        $dimensions->setWidth(10);
        $dimensions->setLength(10);

        $unit = new \Ups\Entity\UnitOfMeasurement;
        $unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

        $dimensions->setUnitOfMeasurement($unit);
        $package->setDimensions($dimensions);

        $shipment->addPackage($package);

        
        $calcRate = $rate->getRate($shipment);

        
        print "<pre>";
        print_r($calcRate);
        print "<pre>";
        //print_r($calcRate->RatedShipment[0]->TotalCharges->MonetaryValue);


        //var_dump($rate->getRate($shipment));
        } catch (Exception $e) {
            var_dump($e);
        }
?>