# EComm CIT410

The following information is the assignment overview for CIT410: E-Commerce.

## General Site Requirements

* [Site template](#project-1)/theme (Responsive Theme – borrow one from the web, but it has to be Bootstrap
based)
* Use the provided database PHP Class for all database accesses
* Browse products by category/subcategory
* [Filter](#filtering) products on brand, price range, rating (AJAX-based)
* Product listing pagination
* Product [Search](#smart-search-bar)
* [Product Reviews and Ratings](#dynamic-rating-system)
* Product Photo Galleries
* [Featured Products](#featured-products)
* Quick View and Full View of Products
* Products will support multiple options/colors/sizes, etc.
* Development of a [full shopping cart](#cart.php) with ability to edit contents
* Development of a [mini shopping cart](#mini-cart) for the site template
* Integration of Authorize.NET payments (will discuss as a class)
* UPS API Integration for calculating and tracking shipments
* AJAX-Based Checkout system (modeled after your favorite E-Commerce site, but I would also
recommend you check out sites such as ToolUp.com, Crutchfield.com, and
TemplateMonster.com for suggestions/examples on what to include including labeling
techniques for form inputs)
* Customer Dashboard to update addresses, view past orders, etc.


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

The class implementation for this feature can be found [here](https://github.com/cayton10/EComm/blob/master/classes/Search_class.php)


### 10.7.2020

#### Dynamic Rating System

So, I'm aware that I could just make a simple form and '< select >' element that would let me do what I need as far as updating reviews and displaying them as calculations on product cards, but I'm really stuck on the star system. In my quest to complete this function I've already blown through three plugins and they have all failed to meet the needs of this function.

##### rateYo
A lightweight jQuery rating plugin that almost worked for my needs, but when styling the elements on the product cards, the foreground SVG would go haywire within the parent div while leaving the background (unfilled stars) where they were intended to be placed. I tried for about 3 hours to find workarounds and they all failed.

##### jQuery Barratings
Had this implemented and working then realized aggregate calculation of all ratings could not be accomplished. The plugin turns a select element into a star rating system based on round numbers. I never did get to the point where i could return calculated ratings to see if fontawesome had images for partially filled stars, but being that the fontawesome.css file was outdated and the stars did not render correctly, I decided to scrap this plugin and try something else... too afraid of wasting more time.

#### Last Ditch Plugin Effort
##### jQuery Starrr
I'll let you know how it goes...

### 10.7.2020

#### Reviews and Ratings

Wow... just wow. 

##### Rating Plugins
I went through 3 differen star rating plugins before I found one that I could make work, and it still doesn't do everything I want it to. The one I wound up going with is:


###### Starrr
<link>https://github.com/dobtco/starrr</link>
I've managed to make it do what I need. Was really running out of time with this deadline so I just ran with it. 

#### Review class
The review class essentially does all of my querying and formatting of information with regard to rating scores and printing reviews from the DB. I actually kind of painted myself in a corner with one function, and have had to implement a workaround with the Featured class to try and get ratings populated for featured products. I'm pretty nervous to see how tough it's going to be on all of my other 'getProduct' function in the Products class. Screwed myself pretty good on that one, but I think I can recover. 

#### Leave Review
Need to get this finished ASAP because I still need to work on filtering... wonder if I can get this done in time D:

### 10.12.2020
Refactored Product_class.php to prep for finishing filtering. Wasn't too hard. Though a lot about how to approach the filtering / pagination problem. 

### 10.13.2020
After having bounced the idea off Brian this morning I've decided to implement an ajax based filtering system that wil query the DB when called. Returned results will dictate how to refresh pagination on the fly


### 10.16.2020


#### Filtering
Finished both filters and developed so they can work in tandem. Took a long time, and I didn't really take good notes, update this readme with everything that I learned, but there are A LOT of comments in [custom.js](custom.js) for implemenation of those functions. 

#### Updating Pagination
I essentially had to trash my pagination class and start from scratch since I technically botched it the first time through. Refactoring the pagination code so everything populates on page load, then I have to account for pagination being drawn dynamically after making the filter ajax calls. Will see how this goes. 


### 10.26.2020
Starting work on shopping cart info

<ul>Items Completed
<li>Pulling cart info on page load for miniCart text bubble</li>
<li>Adding items to database cart using ajax call</li>
<li>Adding items accounts for qty input on shop-single page</li>
</ul>

#### Mini Cart
Figured I'd start with details of the minicart

Returning correct information from the Cart class query to count the number of items in a person's cart. Displays properly on minicart text bubble.
The mini cart logic can be found [here](https://github.com/cayton10/EComm/blob/master/js/custom.js).Be warned. This JS file is not for the weak. Just ctrl/CMD + F miniCart and you'll find what you're looking for.

##### Setting Sessions and Cookies
```php
//Check if cookie exists
  if(!empty($_COOKIE['cartID']))
  {
    //Set session to ID contained within the cookie
    session_id($_COOKIE['cartID']);
  }
  else
  {
    //Set a cookie for us to use for a cart ID
    setcookie('cartID', session_id(), time()+60*60*24*14, "/");//Set cookie to expire in two weeks.
                                                              //Plus include "/" for all directories (made that mistake in 313)
  }

  //Start session
  session_start();

  //Create instance of cart class
  $cart = new Cart();

  //Check number items to include in minicart
  $miniCart = $cart->getCartInfo(session_id());

```

Pretty easy, really. I was overthinking this a lot. Thinking, "I need to set a session, and then set a cookie if one doesn't exist and blah blah blah"...

### 10.27.2020
Adding items to cart successfully updates miniCart qty via ajax. Also employed a new Product class method to descrease the ordered product quantity by the amount added to the cart.

#### Cart Modal
Created a modal to signal to user whether items are added to cart successfully or not. Presents option to continue shopping or go to cart.


### 10.30.2020
Fixed bugs related to miniCart data persistence. More issues w/ starting sessions at particular times gave trouble. Fixed by starting session in header.php only.

#### cart.php
Starting work on setting up the cart inferface.
The cart class which is responsible for implementing all of the database interaction can be found [here](https://github.com/cayton10/EComm/blob/master/classes/Cart_class.php)

### 11.1.2020
Fixing up the cart interface wasn't too bad. Just setting lots of data-(n) fields on the <td> tags, assigning lots of id fields to use a name and concatenate with a product id from the DB or qty, etc. Once all of those things were put in place, the line items, mini cart, cart total, etc all communicate with each other pretty fluidly. The hardest thing to figure out was implementing the <strong><button>Update Cart</button></strong> button. 

The challenge with this was creating a JSON object filled with all of the product ids currently in cart and the qty of each item in the cart. The only reason this was hard was because I don't think I'd ever done it before. Maybe in 313 w/ CIT Pics, but I feel like it was my first time. Getting that information put together in an ajax call took a minute, but it wasn't necessarily hard. The hard part was figuring out how to access the array values in the accompanying PHP script. Took me forever to realize that within the json array, there are objects, and to access object in php you gotta use:
```php
$object->key
```
Oh well. It was a good learning experience. 

I took care of the server side of things by simply taking the cartID that's supplied, completely deleting the cart, and just repopulating it with what was specified upon clicking the update cart button. I'm sure there's another way. I thought about passing the json object array, making a DB call to return everything in cart and compare the results. Maybe a bit like parallel arrays??? It would have taken me a bit longer to figure out, and I'm just trying to get this stuff wrapped up for now. I may go back and try to optimize that function when I've got more free time / after researching it a bit.

