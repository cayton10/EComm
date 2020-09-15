# EComm CIT410

The following information is the assignment overview for CIT410: E-Commerce.

## General Site Requirements

• Site template/theme (Responsive Theme – borrow one from the web, but it has to be Bootstrap
based)
• Use the provided database PHP Class for all database accesses
• Browse products by category/subcategory
• Filter products on brand, price range, rating (AJAX-based)
• Product listing pagination
• Product Search
• Product Reviews and Ratings
• Product Photo Galleries
• Featured Products
• Quick View and Full View of Products
• Products will support multiple options/colors/sizes, etc.
• Development of a full shopping cart with ability to edit contents
• Development of a mini shopping cart for the site template
• Integration of Authorize.NET payments (will discuss as a class)
• UPS API Integration for calculating and tracking shipments
• AJAX-Based Checkout system (modeled after your favorite E-Commerce site, but I would also
recommend you check out sites such as ToolUp.com, Crutchfield.com, and
TemplateMonster.com for suggestions/examples on what to include including labeling
techniques for form inputs)
• Customer Dashboard to update addresses, view past orders, etc.

## Project 1 

### 6.4.2020
Items needed for each page (php includes):
* Cart
* Customer Dashboard Link (Account Icon)
* Search Bar
* Custom Site Logo
Added all required link placeholders to navigation for header.php and footer.php


### 6.5.2020
Added products to CIT410 database.
Category -> Subcats -> Products and all required fields -> product images.

Created template pages for cart, checkout, single item inspection (shop-single), and browse inventory(shop).
When I say "created" I mean header/footer includes are done and some rudimentary placeholders are added.

### 8.28.2020
Repopulated products to NEW 410 database.

### 8.31.2020
Added all required tuples to prodopt Table in DB


### 9.15.2020
Finally finished category listings from main 'shop.php' page. That was pretty tough to wrap my head around. The formatting of html within each Category class function was tough to get right. I kept breaking things and forgetting element tags, but I FINALLY GOT IT WORKING. I know this isn't the best solution to this problem, but given my knowledge of OOP w/ PHP this is the best I've got for now.


#### Starting on Product_class.php
Need to develop a class that will load products to the shop.php page. Initially, this class just needs to load all products from the database.

 *Class has been created, and it works pretty well. I need to do better for just returning JSON with ajax calls, but this will work for the project deadline coming on Friday, 9.18.2020

Made some minor adjustments to custom.css to display products with more fluidity. It's still not 100%, but it's better than it was.
