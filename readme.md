<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About OPI

This is a project are aimed to create a online system with API for thrid party to push order request with a list specific products and their quantities. System also provide a front interface to managing inventory and order. The specification on the feature will be listed in the **System Specification** section below.

- [System Specification](#)
- [System Requirements](#)
- [Installation](#)
- [Database Migration](#)
- [Testing](#)
- [Assumptions](#)
- [License](#)

## System Specification


## Software Requirements

My Dev environment are using the follow settings:

- XAMPP Server v3.2.2
- Laravel 5.4.12
- PHP 7.1.1
- jQuery 3.1.1
- Bootstrap 3.3.7
- PHPUnit 5.7.13

## Installation

## Database Migration

## Testing

As for unit testing I am using **PHPUnit 5.7.13**

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


## License

As building from the Laravel framework, I will follow the it's open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
