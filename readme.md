<p align="right">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About OPI

This is a project are aimed to create a online system with API for thrid party to push order request with a list specific products and their quantities. System also provide a front interface to managing inventory and order. The specification on the feature will be listed in the **System Specification** section below.

- [System Specification](#system_specification)
- [Installation](#installation)
- [Database Migration](#database_migration)
- [Testing](#testing)
- [Howto](#howto)
- [Assumptions](#assumptions)
- [License](#license)

<div id="software_requirements"></div>
## Software Requirements

My Dev environment are using the follow settings:

- XAMPP Control Panel v3.2.2 for running the Apache Server
- Laravel 5.4.12
- PHP 7.1.1
- MySQL 5.6
- jQuery 3.1.1
- Bootstrap 3.3.7
- PHPUnit 5.7.13
- Composer

<div id="installation"></div>
## Installation
Make sure you have setup or have a working Apache and MySQL server running in your local. (Window or Linux etc)
Make sure you have PHP7 running on your local machine and the Apache server. Sometime if could be different please double confirm your version first. If you in doubt please contact your system admin or hosting provider.

To Start with, first, you need to clone the latest verison of the source to you local directory, where you server's project/public folder is.
e.g. In windows.
```
C:\xampp\htdocs\opi
```
Where **opi** is the project folder you created.

Next, you need to have composer install on your machine and use it to install the Laravel application. So go into your project folder if you already have composer installed and type this.
```
composer install
```
If everything goes well, the installation will be completed without any issue. In case you do encounter any issue during the installation, do feel free to let me know and I could see what I can help.

You also need the encryption key to be generated for Laravel library. Just use the following command to generate a new key for your system.
```
php artisan key:generate
```

Assuming everything is done and successful, you should be able to see the home page of the system by using the follow url below:
```
http://[your-domain-or-localhost]/[your-project-folder]/public

e.g. 
http://localhost/opi/public
```
I have not change the default home page of Laravel. So it is a good example to test and see you can open this page in Laravel application. Upon succcessful installaion of this system, you should be able to see it without any issue.

Now we need access the system page. Normally I will just use the order home page to access the system.
```
e.g.
http://localhost/opi/public/order
```
The system should be pretty empty now. So let inject some dummy data. Please see the next section for more detail.

<div id="database_migration"></div>
## Database Migration
Once you have installed the system and have the database configuration setup with your local server. You can use the following command to import the tables we used for this system.
** Removeber you need to create the database first and put it into the .env file. You can rename the .env.example file and up the database configuration there.**
```
// Run it from your root folder of the laravel framework.

php artisan migrate
```
If you want to refresh and redo the migration again you can use this command below.
```
php artisan migrate:refresh
```
The item physical status lookup table is a must have in the database before you can use the system.
Please run the following command to import the rows from the seeder file.
```
php artisan db:seed --class=ItemPhysicalStatusLookupTableSeeder

```
Do not run it without the **--class=ItemPhysicalStatusLookupTableSeeder**, because it will import all seeders into the database including the dummy record. If you don't wish to have these dummy data in the table please just run the ItemPhysicalStatusLookupTableSeeder class only.

To help you getting start with the system and testing, here is a set of dummy data from the migration scripts. You can run the command below to import tham all.
```
php artisan db:seed
```

<div id="testing"></div>
## Testing

As for unit testing I am using **PHPUnit 5.7.13**.

I have make some basic unit testing, you can run the following command quickly check is the system ok.
```
vendor\bin\phpunit tests/unit
```
This is not the full system test. As I have some issue with the PHPUnit json api routing on the test, so I would recommand to go through the frontend and test some of the component, like listing and viewing.

To create an order you will need to send a JSON request to the server. I didn't add the cross origin logic in there. By default you should be able to send request to your localhost.

To do that you will need to install the plugin **Postman** from your browser (preferable Chrome or Firefox).

Set the URL:
```
http://localhost/opi/public/api/order/new
```
And the JSON request data as following structure. (use raw JSON format in the Postman)
```
{
  "order": {
    "customer_name": "Gabriel Jaramillo",
    "address": "test address",
    "total": 230.00,
    "items": [
      {
        "sku": "TESTSKU4",
        "quantity": 2
      },
      {
        "sku": "TESTSKU3",
        "quantity": 1
      }
    ]
  }
}
```
You should be able to see the following output/response:

```
{
    "success": true,
    "message": "A new order [20] has been submitted."
}
```
There is a script/command to be run at the backend (cronjob) to clear up the order status. This is one of the requirement from the requester. Since we can't mannually update the order status, the script will go through the business logic to update status or skip making any change on the order.

```
php artisan order:processor -vvv
```

<div id="howto"></div>
## How To?
### 1. How do I subject an order request?
##### You can use browser plugin like Postman. Please see the Testing section for instruction.
### 2. How do I view an Order?
##### In the order list, at the end of each row. You click on the view button to see a specific order information.
### 3. How do I remove an item from Order?
##### You can remove an item from the order information page. Just click on the remove button next to the item you want to remove it.
### 4. How do I update the status of an Item?
##### You need to view an item first and in the specific item page, you should be able to use the status drop down to change the Item status. Keep it in mind that you won't be set any item to be delivered if it hasn't assigned to any order.
### 5. How to create a new item?
##### Item must be coming from a product, therefore you need go to a specific product page first and just increase the number add the bottom of the left and click Add. No other additional information are required.
### 6. How do I assign an item to an order?
##### You can't do it from the interface. You must submit with a order request through the API. Then based on your request item SKU, the system will allocation the next available item to your order.
### 7. How do I change the order status to Cancelled, when there is no more item attached to it?
##### You can manually change an item status. By removing the last item in the order WON'T update status either. The only way the order's status will change to "Cancelled", is when you run the order:processor script. It's logic will pickup the order and change it to "Cancelled". Please see Testing for more information.
### 8. How do the admin get the email for a new product created into the system?
##### If you try to create an product through the interface, you won't have received any notification email. The request must come from an Order request. So if an order is required a item with an new product (which is not in the system yet). The system will create a new product for ordered items. At that time an event will be fired and the handler will determine should the system sending out a notification email.
### 9. Why I can't change the item status?
##### This is likely that the order has been completed and not in "In progress" status. Because it doesn't make sense that you still can change the status of an item that it's order has been completed.

<div id="assumptions"></div>
## Assumptions
1. There is not delete feature for all orders, products and items. Item will only be removed/ unassigned from the order but not deleting from the item list in the system.
2. All items/ Product requested to the API are valid and “should” be existing in the system. Because if it is not, then it will be automatically created and couldn’t be deleted by current requirement of the application. (see Assumption 1)
3. Products and items should be locked down once the relative items have been assigned to an order.
4. Removing the Item from an order will not change the total cost on the order. The reason the order can be done by a client with alteration on the price of the product. Event we have price value on the product record, it might not be consistent with the order value. So will just assuming we won’t change the cost when item has been removed from the order. (Ideally maybe we should allow total in order to be editable, putting it to wish list)
5. When removing item from the order, assuming we are only allow item to be removed if:
  1. Order is not completed
  2. That item has not been delivered (even it is on road with driver…)
  3. We are not going to address any more business logic here e.g. And order with half items has been delivered and damaged good etc. We could build but it is not in this scope of the project.
6. All product will be created if it is missing from an incoming order. This doesn’t take into consideration on product which has been discontinued.
7. We can’t change the bond between a product and a item. E.g. changing the SKU in an item. Because the item might have been sold/ordered on a customer concern.
8. The current data structure is assuming one SKU per variant set/ combination. But I have allow product property can select two different type “feature” and “option”. While it is not much of meaning to have option in this setup but I have included it so we can upgrade the logic later.

<div id="license"></div>
## License

As building from the Laravel framework, I will follow the it's open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
