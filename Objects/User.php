<?php


/**
 * Class NewRelicPartnerAPIUser
 *
 * @author Robin Corps <robin@wirehive.net>
 * @link https://docs.newrelic.com/docs/accounts-partnerships/partnerships/partner-api/partner-api-user-object
 *
 * The following is an example of a defined user object.
 * <code>
 * {
 *   "users": [
 *     {
 *       "email": "jsmith@gmail.com",
 *       "password": "testing123",
 *       "first_name": "John",
 *       "last_name": "Smith",
 *       "owner": true,
 *       "role": "admin"
 *     }
 *   ]
 * }
 * </code>
 */
class NewRelicPartnerAPIUser
{
  /** @var NewRelicPartnerAPI $api */
  private $api;

  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }


  /**
   * List (index) all users of an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-user-object#example-list
   *
   * @param int $account_id
   *
   * @return array
   */
  public function getList($account_id)
  {
    return $this->api->call('accounts/' . $account_id . '/users');
  }


  /**
   * Update the role of a user or the owner of an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-user-object#example-update
   *
   * @param int   $account_id
   * @param int   $user_id
   * @param array $params
   *
   * @return array
   */
  public function update($account_id, $user_id, $params)
  {
    return $this->api->call('accounts/' . $account_id . '/users/' . $user_id, $params, HTTP_METH_PUT);
  }


  /**
   * Add (create) a user to an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-user-object#example-create
   *
   * @param array $params
   *
   * @return array
   */
  public function create($account_id, $params)
  {
    return $this->api->call('accounts/' . $account_id . '/users', $params, HTTP_METH_POST);
  }


  /**
   * Delete a user from an account.
   *
   * @link https://docs.newrelic.com/docs/users-partnerships/partnerships/partner-api/partner-api-user-object#example-delete
   *
   * @param int $user_id
   *
   * @return array
   */
  public function delete($account_id, $user_id)
  {
    return $this->api->call('accounts/' . $account_id . '/users/' . $user_id, null, HTTP_METH_DELETE);
  }
} 