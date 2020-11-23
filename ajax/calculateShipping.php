<?
    //Include config file for classes I've written
    require_once(__DIR__ . "/../config/config.php");

    //Include info required for grabielbull's ups-api wrapper
    require_once(__DIR__ . "/../classes/vendor/autoload.php");


    //Posted information from ajax call on checkout page


    $rate = new Ups\Rate(
        $accessKey = '4D8BF763AAA53435',
        $userID = 'cayton10',
        $password = 'hf5H_cVymTyiMAw'
    );

    try {
        $shipment = new \Ups\Entity\Shipment();

        $shipperAddress = $shipment->getShipper()->getAddress();
        $shipperAddress->setPostalCode('45619');

        $address = new \Ups\Entity\Address();
        $address->setPostalCode('45619');
        $shipFrom = new \Ups\Entity\ShipFrom();
        $shipFrom->setAddress($address);

        $shipment->setShipFrom($shipFrom);

        $shipTo = $shipment->getShipTo();
        $shipTo->setCompanyName('Test Ship To');
        $shipToAddress = $shipTo->getAddress();
        $shipToAddress->setPostalCode('99205');

        $package = new \Ups\Entity\Package();
        $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
        $package->getPackageWeight()->setWeight(37.38);
        
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

        echo $calcRate->ShipmentResults->ShipmentCharges->TransportationCharges->MonetaryValue;
        print "<pre>";
        print_r($calcRate->RatedShipment[0]->TotalCharges->MonetaryValue);
        print "<pre>";

        //var_dump($rate->getRate($shipment));
        } catch (Exception $e) {
            var_dump($e);
        }
?>