<?php


/**
 * Class NewRelicPartnerAPIAccount
 *
 * @author Robin Corps <robin@wirehive.net>
 * @link   https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#account-calls
 *
 * The following is an example of a JSON call using the API.
 * <code>
 * {
 *   "account": [
 *     {
 *       "name": "Data Nerds",
 *       "phone_number": "503-555-0123",
 *       "allow_api_access": true,
 *       "testing": false,
 *       "users": [
 *         {
 *           "email": "someone@company.com",
 *           "password": "testing123",
 *           "first_name": "John",
 *           "last_name": "Smith",
 *           "owner": true,
 *           "role": "admin"
 *         },
 *         {
 *           "email": "someonelse@company.com",
 *           "password": "testing345",
 *           "first_name": "Fred",
 *           "last_name": "Bloggs",
 *           "owner": false,
 *           "role": "user"
 *         }
 *       ],
 *       "subscriptions": [
 *         {
 *           "product_id": 4,
 *           "quantity": 2,
 *          "promo_code": null
 *         }
 *       ]
 *     }
 *   ]
 * }
 * </code>
 */
class NewRelicPartnerAPIAccount
{
  /** @var NewRelicPartnerAPI $api */
  private $api;


  /**
   * Construct a new Account API interface
   *
   * @param NewRelicPartnerAPI $api
   */
  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }


  /**
   * List (index) all accounts of a partner.
   *
   * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#example-list
   *
   * @return array
   */
  public function getList()
  {
    return $this->api->call('accounts');
  }


  /**
   * Show the attributes of an account.
   *
   * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#example-show
   *
   * @param int $account_id
   *
   * @return array
   */
  public function show($account_id)
  {
    return $this->api->call('accounts/' . $account_id);
  }


  /**
   * Update the attributes of an account.
   *
   * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#example-update
   *
   * @param int   $account_id
   * @param array $params
   *
   * @return array
   */
  public function update($account_id, $params)
  {
    return $this->api->call('accounts/' . $account_id, $params, NewRelicPartnerAPI::PUT);
  }


  /**
   * Create an account with the given parameters.
   *
   * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#example-create
   *
   * @param array $params
   *
   * @return array
   */
  public function create($params)
  {
    return $this->api->call('accounts', $params, NewRelicPartnerAPI::POST);
  }


  /**
   * Cancel (delete) an account.
   *
   * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#example-delete
   *
   * @param int $account_id
   *
   * @return array
   */
  public function cancel($account_id)
  {
    return $this->api->call('accounts/' . $account_id, null, NewRelicPartnerAPI::DELETE);
  }
} 