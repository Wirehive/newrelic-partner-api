<?php


/**
 * Class NewRelicPartnerAPISubscription
 *
 * @author Robin Corps <robin.corps@wirehive.com>
 * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-subscription-object
 *
 * The following is an example of a subscription call to create a subscription.
 * <code>
 * {
 *   "subscriptions": [
 *     {
 *       "product_id": 4,
 *       "quantity": 2
 *     },
 *     {
 *       "product_id": 13,
 *       "quantity": 2
 *     }
 *   ]
 * }
 * </code>
 */
class NewRelicPartnerAPISubscription
{
  /** @var NewRelicPartnerAPI $api */
  private $api;


  /**
   * Construct a new Subscription API interface
   *
   * @param NewRelicPartnerAPI $api
   */
  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }


  /**
   * List (index) all subscriptions of an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-subscription-object#example-list
   *
   * @param int $account_id
   *
   * @return array
   */
  public function getList($account_id)
  {
    return $this->api->call('accounts/' . $account_id . '/subscriptions');
  }


  /**
   * Show a subscription of an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-subscription-object#example-show
   *
   * @param int   $account_id
   * @param int   $subscription_id
   * @param array $params
   *
   * @return array
   */
  public function show($account_id, $subscription_id)
  {
    return $this->api->call('accounts/' . $account_id . '/subscriptions/' . $subscription_id);
  }


  /**
   * Add (create) a subscription for an account with given parameters.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-subscription-object#example-create
   *
   * @param array $params
   *
   * @return array
   */
  public function create($account_id, $params)
  {
    return $this->api->call('accounts/' . $account_id . '/subscriptions', $params, NewRelicPartnerAPI::POST);
  }
} 