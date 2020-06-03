
-- Following INSERT statements used to populate Parent Cat of Bicycles
-- and subCats for Road Bikes and Mountain Bikes
INSERT INTO `cit485s20`.`category` (`cat_ID`, `cat_Name`) VALUES ('58', 'Bicycles');
INSERT INTO `cit485s20`.`category` (`cat_ID`, `cat_Name`, `cat_SubCat`) VALUES ('59', 'Road', '58');
INSERT INTO `cit485s20`.`category` (`cat_ID`, `cat_Name`, `cat_SubCat`) VALUES ('60', 'Mountain', '58');

-- Following INSERT statements used to populate products for each subCat --
