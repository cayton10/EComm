
-- Following INSERT statements used to populate Parent Cat of Bicycles
-- and subCats for Road Bikes and Mountain Bikes
INSERT INTO cit410f20.category (cat_ID, cat_Name, cat_SubCat) VALUES ('100', 'Bicycles', '0');
INSERT INTO cit410f20.category (cat_ID, cat_Name, cat_SubCat) VALUES ('101', 'Road', '100');
INSERT INTO cit410f20.category (cat_ID, cat_Name, cat_SubCat) VALUES ('102', 'Mountain', '100');


-- Following INSERT statements used to populate products for each subCat --
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('100', 'System 6', 'Faster. Everywhere. The fastest UCI-legal bike in the world. More speed with less effort. Simple as that.', '11000.00', '5', 'Cannondale', 'Hi-MOD Red eTap AXS', '100', 'Y', '16.7551');
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('101', 'Ultimate', 'Canyon Ultimate CF SLX Disc 8.0 ETAP\nCombining advanced system integration, low weight, and class-leading components, this is a bike that will excel at any level of racing. The World Championship-winning frame is packed with innovative tech, including the new SRAM Force eTap AXS groupset and highly reflective design elements that improve visibility when you’re running out of daylight on your long ride.', '5999.00', '10', 'Canyon', 'CF SLX Disc 8.0 ETAP', '100', 'N', '16.40239');
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('102', 'AR | Advanced', 'This is the all-new AR, and it’s the fastest road bike we’ve ever created. Yes, it’s insanely aerodynamic—the result of the most taxing development process we’ve ever undertaken. But it also features our most sophisticated carbon chassis ever, as well as disc brakes and impeccable handling. So, actually, it’s not just faster. It’s better, in every way possible.', '4999.00', '15', 'Felt', 'Ultegra', '100', 'N', '18.65111');
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('103', 'Edict | Advanced', 'Sketchy singletrack, sick jump lines, brutal cross-country courses, crazy enduro stages, the top step of podiums – we’ve been there, we’ve done that, and we’re eager for more. Are these the best mountain bikes in the world? That’s not for us to say. But yes, they are.', '6299.00', '10', 'Felt', 'XT', '100', 'N', '24.4377');
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('104', 'Neuron', 'On the hunt for the bike that can do it all? With its low weight, top-end components and uncompromising performance, there’s not much the Neuron CF SLX 9.0 LTD can’t handle.', '6499.00', '20', 'Canyon', 'CF SLX 9.0 LTD', '100', 'Y', '25.838177');
INSERT INTO cit410f20.product (pro_ID, pro_Name, pro_Descript, pro_Price, pro_Qty, pro_Manufacturer, pro_Model, cat_ID, pro_Feat, pro_Weight) VALUES ('105', 'Trance', 'When it comes to riding hard and fast on challenging off-road terrain, there’s almost nothing this ambitious trail bike can’t do. From steep, technical climbs to rowdy descents, you’re ready for anything with Trance.', '2100.00', '12', 'Giant', '3', '100', 'N', '27.57293');

--Following INSERT statements used to populate prodopt table --
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('1', '1', 'Size', '47', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('2', '1', 'Size', '51', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('3', '1', 'Size', '54', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('4', '1', 'Size', '56', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('5', '1', 'Size', '58', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('6', '1', 'Size', '60', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('7', '1', 'Size', '62', '0.00', '100');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('8', '2', 'Color', 'Stealth', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('9', '2', 'Color', 'Blue Tinted', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('10', '1', 'Size', '2XS', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('11', '1', 'Size', 'XS', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('12', '1', 'Size', 'S', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('13', '1', 'Size', 'M', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('14', '1', 'Size', 'L', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('15', '1', 'Size', 'XL', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('16', '1', 'Size', '2XL', '0.00', '101');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('17', '2', 'Color', 'White', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('18', '2', 'Color', 'Aqua', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('19', '1', 'Size', '48', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('20', '1', 'Size', '51', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('21', '1', 'Size', '54', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('22', '1', 'Size', '56', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('23', '1', 'Size', '58', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('24', '1', 'Size', '61', '0.00', '102');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('25', '2', 'Color', 'Dove / Matte Carbon', '0.00', '103');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('26', '2', 'Color', 'Aquafresh / Matte Carbon', '0.00', '103');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('27', '1', 'Size', 'S', '0.00', '103');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('28', '1', 'Size', 'M', '0.00', '103');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('29', '1', 'Size', 'L', '0.00', '103');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('30', '1', 'Size', 'M', '0.00', '104');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('31', '1', 'Size', 'L', '0.00', '104');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('32', '1', 'Size', 'XL', '0.00', '104');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('33', '2', 'Color', 'Metallic Black', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('34', '2', 'Color', 'Olive Green', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('35', '1', 'Size', 'XS', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('36', '1', 'Size', 'S', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('37', '1', 'Size', 'M', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('38', '1', 'Size', 'L', '0.00', '105');
INSERT INTO cit410f20.prodopt (opt_ID, opt_Group, opt_Name, opt_Value, opt_Price, pro_ID) VALUES ('39', '1', 'Size', 'XL', '0.00', '105');
