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

### 9.16.2020
Continue with custom.css to fix product listing cards.

### 9.22.2020
Fixed product listing cards using the following 
```CSS
/* Fixes div width to inherit the parent div width */
.innerProdContainer{
    width: inherit;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
}
```
Specifically, display: flex, and flex-direction: column. Those were life saving for getting images and text in the appropriate locations.

Updated DB because I messed up the product details information on SQL insert.
```SQL
UPDATE cit410f20.product SET cat_ID = '101' WHERE (pro_ID = '100');
UPDATE cit410f20.product SET cat_ID = '101' WHERE (pro_ID = '101');
UPDATE cit410f20.product SET cat_ID = '101' WHERE (pro_ID = '102');
UPDATE cit410f20.product SET cat_ID = '102' WHERE (pro_ID = '103');
UPDATE cit410f20.product SET cat_ID = '102' WHERE (pro_ID = '104');
UPDATE cit410f20.product SET cat_ID = '102' WHERE (pro_ID = '105');
```
### 9.23.2020
Kept the products_class.php structure I started working with. Thought about changing things and using AJAX calls to process JSON to output product results. Started using query strings to select appropriate member functions of the Product_class. A lot of this was rinse and repeat. Not exactly in line with best OOP methods, but I'm still learning. Besides, I'll have better opportunities to employ best practices as this project grows. 

Way to select all products from a primary category was to use a LEFT JOIN clause in SQL statement.
```SQL
SELECT
    pro_ID,
    pro_Name,
    pro_Descript,
    pro_Price,
    pro_Manufacturer
FROM
    product
LEFT JOIN category
ON product.cat_ID = category.cat_ID
WHERE category.cat_SubCat = $category;
```
Happy with the results. 

### 9.24.2020
Whoa, pagination was pretty tough. Debugging was the hardest part, but as of 5:55pm it works. 

#### Paginate_class.php
<hr>
This guy started out kind of like Product_class.php.... NOT GOOD. Too 'one-trick-poney'. At first I extended this class to derive it from Product. Worked through the logic and output for a while and realized how interdependent the functions would be. I was basically just creating a class mess of spaghetti code. 

Wound up making the class a little more versatile and less dependent on others. Set appropriate PRIVATE member variables with good setters and getters. It's amazing what you can accomplish when you bring things back to basics. After streamlining the class I was able to instantiate a Paginate class object within the shop.php page on page load. With the class members I had placed, I was able to provide some simple error handling related to the query strings that these functions are so dependant on. 

Overall, pretty happy with how Pagination_class.php turned out. I should go back and refactor Product_class.php, but who has the time???
<hr>

### 9.25.2020
Started working on product detail page:
    Some things to consider:
    <ul>
        <li>Things to populate</li>
            <ul>
            <li>Product Details</li>
                <ul>
                    <li>Images</li>
                    <li>Description</li>
                    <li>Price</li>
                    <li>Name</li>
                    <li>Model</li>
                </ul>
            <li>Product Options</li>
                <ul>
                    <li>Size</li>
                    <li>Color</li>
                </ul>
            </ul>
    </ul>

Not sure how I'm going to go about the product options portion yet. 

For Product details, I'm writing new functions to include in the Product_class.php file.

