newrelic-partner-api
====================

A PHP library for the NewRelic v2 Partner API

See the documenation for the API at:
https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-reference

The API takes associative arrays for objects and returns associative arrays (the actual API talks in JSON).

## Examples
### Create a new API object
```php
$api = new NewRelicPartnerAPI(123, 'aaabbbcccdddeeefffggghhh');
```

###  Get a list of the accounts
```php
$accounts = $api->account->getList();
```

###  Get a list of the users for an account
```php
$account_id = 10;
$users = $api->user->getList($account_id);
```

###  Show details of a subscription of an account
```php
$account_id = 10;
$subscription_id = 1;
$details = $api->subscription->show($account_id, $subscription_id);
```

### Create a new user for an account
```php
$account_id = 10;
$user = array("users" => array(
 "email" => "jsmith@gmail.com",
 "password" => "testing123",
 "first_name" => "John",
 "last_name" => "Smith",
 "owner" => true,
 "role" => "admin"
));
$result = $api->user->create($account_id, $user);
```
