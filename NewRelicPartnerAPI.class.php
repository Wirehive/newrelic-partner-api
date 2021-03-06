<?php

require_once('Exceptions.php');
require_once('Objects/Account.php');
require_once('Objects/User.php');
require_once('Objects/Subscription.php');


/**
 * Class NewRelicPartnerAPI
 *
 * Interface to the NewRelic Partner API v2
 *
 * @author Robin Corps <robin.corps@wirehive.com>
 * @version 0.1
 */
class NewRelicPartnerAPI
{
  const STAGING = 1;
  const LIVE    = 2;

  const STAGING_URL = 'https://staging.newrelic.com/api/v2/partners/';
  const LIVE_URL    = 'https://rpm.newrelic.com/api/v2/partners/';

  const GET = 1;
  const POST = 2;
  const PUT = 3;
  const DELETE = 4;

  public $account;
  public $user;
  public $subscription;

  private $curl = null;
  private $curl_opts = array();
  private $ipv4_fallback = true; // if server is using IPv6 but not setup correctly fall back to IPv4
  private $mode;
  private $partner_id;
  private $api_key;


  /**
   * Construct a new NewRelic Partner API interface
   *
   * @param string $partner_id
   * @param string $api_key
   * @param int    $mode
   *
   * @throws NewRelicApiException
   */
  public function __construct($partner_id, $api_key, $mode = null)
  {
    if (!is_int($partner_id) || !is_string($api_key))
    {
      throw new NewRelicApiException('You must specify a Partner ID and API key.');
    }

    $this->setPartnerId($partner_id);
    $this->setApiKey($api_key);

    if ($mode === null)
    {
      $mode = self::LIVE;
    }

    $this->setMode($mode);

    $this->initCurl();

    $this->account = new NewRelicPartnerAPIAccount($this);
    $this->user = new NewRelicPartnerAPIUser($this);
    $this->subscription = new NewRelicPartnerAPISubscription($this);
  }


  /**
   * Initialize cURL
   */
  private function initCurl()
  {
    $this->curl = curl_init($this->getEndpoint());

    $this->setCurlOpts(array(
      CURLOPT_HTTPHEADER     => array(
        'x-api-key: ' . $this->getApiKey(),
        'Content-Type: application/json'
      ),
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => 60,
      CURLOPT_USERAGENT      => 'php-new-relic2.0'
    ));
  }


  /**
   * Get the endpoint URL based on the mode set
   *
   * @return string
   */
  private function getEndpoint()
  {
    switch ($this->getMode())
    {
      case self::STAGING:
        return self::STAGING_URL . $this->getPartnerId() . '/';

      default:
      case self::LIVE:
        return self::LIVE_URL . $this->getPartnerId() . '/';
    }
  }


  /**
   * Get the mode of the API (staging/live)
   *
   * @return int
   */
  public function getMode()
  {
    return $this->mode;
  }


  /**
   * Set the mode of the API (staging/live)
   *
   * @param int $mode
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }


  /**
   * Get the Partner ID in use
   *
   * @return string
   */
  public function getPartnerId()
  {
    return $this->partner_id;
  }


  /**
   * Set the Partner ID to use
   *
   * @param string $partner_id
   */
  public function setPartnerId($partner_id)
  {
    $this->partner_id = $partner_id;
  }


  /**
   * @return mixed
   */
  public function getApiKey()
  {
    return $this->api_key;
  }


  /**
   * @param mixed $api_key
   */
  public function setApiKey($api_key)
  {
    $this->api_key = $api_key;
  }


  /**
   * Call the API
   *
   * @param string $url
   * @param array  $params
   * @param int    $type
   */
  public function call($url, $params = null, $type = null)
  {
    $this->setCurlOpt(CURLOPT_URL, $this->getEndpoint() . $url);

    if ($params !== null)
    {
      $this->setCurlOpt(CURLOPT_POSTFIELDS, json_encode($params, JSON_NUMERIC_CHECK));
    }

    switch ($type)
    {
      case self::POST:
        $this->setCurlOpt(CURLOPT_POST, true);
        break;

      case self::PUT:
        $this->setCurlOpt(CURLOPT_PUT, true);
        $this->setCurlOpt(CURLOPT_CUSTOMREQUEST, 'PUT');
        break;

      case self::DELETE:
        $this->setCurlOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        break;
    }

    $response = curl_exec($this->curl);

    $status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

    if (curl_errno($this->curl) == 60)
    {
      throw new NewRelicApiException('Invalid or no certificate authority found');
    }

    if ($response === false && !$this->curlOptSet(CURLOPT_IPRESOLVE))
    {
      $matches = array();
      $regex   = '/Failed to connect to ([^:].*): Network is unreachable/';

      if (preg_match($regex, curl_error($this->curl), $matches))
      {
        if (strlen(@inet_pton($matches[1])) === 16)
        {
          if (!$this->ipv4_fallback)
          {
            throw new NewRelicApiTransportException(
              'Invalid IPv6 configuration on server, please disable or get native IPv6 on your server.',
              curl_errno($this->curl),
              $this->getCurlOpts()
            );
          }

          $this->setCurlOpt(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

          $response = curl_exec($this->curl);
        }
      }
    }

    if ($response === false)
    {
      curl_close($this->curl);

      throw new NewRelicApiTransportException(
        curl_error($this->curl),
        curl_errno($this->curl),
        $this->getCurlOpts()
      );
    }

    curl_close($this->curl);

    $result = json_decode($response, true);

    if ($result === null)
    {
      if ($status == 500)
      {
        throw new NewRelicApiException('NewRelic threw a 500 error...');
      }

      throw new NewRelicApiException('Error decoding result as JSON (' . $status . '): ' . $response);
    }

    if (array_key_exists('error', $result))
    {
      $code = 0;
      $error = $result['error'];

      if (is_array($result['error']))
      {
        if (array_key_exists('accountview', $result['error']) && strpos($result['error']['accountview'][0], 'exists') !== false)
        {
          $code = NewRelicApiException::USER_EXISTS;
        }

        $error = var_export($result['error'], true);
      }

      throw new NewRelicApiException('NewRelic returned the error: ' . $error, $code);
    }

    return $result;
  }


  /**
   * Return the value of a set cURL option
   *
   * @param $opt
   *
   * @return mixed
   */
  public function getCurlOpt($opt)
  {
    if (array_key_exists($opt, $this->curl_opts))
    {
      return $this->curl_opts[$opt];
    }

    return null;
  }


  /**
   * Allow setting of a cURL option
   *
   * @param int   $opt
   * @param mixed $value
   */
  public function setCurlOpt($opt, $value)
  {
    $this->curl_opts[$opt] = $value;

    curl_setopt($this->curl, $opt, $value);
  }


  /**
   * Return whether or not a cURL option has been set
   *
   * @param int $opt
   *
   * @return bool
   */
  public function curlOptSet($opt)
  {
    return array_key_exists($opt, $this->getCurlOpts());
  }


  /**
   * Get an array of the set cURL options (can be useful for debugging)
   *
   * @return array
   */
  public function getCurlOpts()
  {
    return $this->curl_opts;
  }


  /**
   * Allow setting of an array of cURL options
   *
   * @param array $options
   */
  public function setCurlOpts($options)
  {
    $this->curl_opts = $options + $this->curl_opts;

    curl_setopt_array($this->curl, $options);
  }


  /**
   * Get the URL of the API call
   *
   * @return string
   */
  public function getUrl()
  {
    return $this->getCurlOpt(CURLOPT_URL);
  }


  /**
   * Reset the cURL settings so the same API instance can be reused
   */
  public function reset()
  {
    $this->initCurl();
  }
}
