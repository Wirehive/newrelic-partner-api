<?php


/**
 * Class NewRelicPartnerAPIAccount
 *
 * @author Robin Corps <robin@wirehive.net>
 *
 * @link   https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-account-object#account-calls
 */
class NewRelicPartnerAPIAccount
{
  /** @var NewRelicPartnerAPI $api */
  private $api;


  /**
   * Construct a new Account object
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
    return $this->api->call('accounts/' . $account_id, $params, HTTP_METH_PUT);
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
    return $this->api->call('accounts', $params, HTTP_METH_POST);
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
    return $this->api->call('accounts/' . $account_id, null, HTTP_METH_DELETE);
  }
} 