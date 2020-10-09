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

Everything populates for product details as far as text is concerned. Wrote simple getter functions to grab all details after we make the query for the item on page load. Use those getters to return our information and store in variables on the page. Output those variables as text within the html elements.

#### Image Carousel
WHAT A NIGHTMARE
Took a minute to figure out why my carousel wasn't showing on the page. Can't hard code an 'active' image for the class attribute since the images are loaded dynamically. Worked out some PHP logic to work around that.

Wow. I hate dynamic image carousels. I think I've just been working on this thing non stop for the last couple of days, too, so I'm starting to make stupid mistakes.


### 10.05.2020

#### Featured Products

Have decided to create a 'Featured' class which will extend the Product class.
The rationale behind extending the class is to limit variable declaration reuse. I would like to be able to reuse some of the functions I had already written, but the queries are specific to each function within the Product class. Therefore, a new function to populate 'Featured Products' needs written.

Instead of combining multiple operations into one function like I did before, I am simply returning all the product information from the database if the boolean 'pro_Feat' is true or == 'Y'. This information is returned as an associative array and processed in a foreach loop. For each product that is processed, an appropriate image needs to be populated. I created an image class to take care of this

#### Image Class

The image class contains a member function to process a product ID, search for an accompanying image in the 'products' directory, and returns the file path, and image name back to index.php for output. 


Styling the products card was pretty tough since the div containing the cards dynamically populates products using an owl-carousel. It took some hammering, but I got it done. 

### 10.6.2020

#### Smart Search Bar

The smart search feature is located in the header.php, which is included on every page of My Variety Store. I created a class 'Search_class.php' to process characters from the search box via ajax and return results back to the ajax call for processing. 

I had a helpful tutorial video I heavily referenced from my CIT313 class. It was a huge help!


### 10.8.2020

#### Reviews and Ratings

Wow... just wow. 

##### Rating Plugins
I went through 3 differen star rating plugins before I found one that I could make work, and it still doesn't do everything I want it to. The one I wound up going with is 
###### Starrr
<link>https://github.com/dobtco/starrr</link>
I've managed to make it do what I need. Was really running out of time with this deadline so I just ran with it. 

#### Review class
The review class essentially does all of my querying and formatting of information with regard to rating scores and printing reviews from the DB. I actually kind of painted myself in a corner with one function, and have had to implement a workaround with the Featured class to try and get ratings populated for featured products. I'm pretty nervous to see how tough it's going to be on all of my other 'getProduct' function in the Products class. Screwed myself pretty good on that one, but I think I can recover. 

#### Leave Review
Need to get this finished ASAP because I still need to work on filtering... wonder if I can get this done in time D: