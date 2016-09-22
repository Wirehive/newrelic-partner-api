<?php


/**
 * Class NewRelicApiException
 *
 * General New Relic API exception class
 *
 * @author Robin Corps <robin.corps@wirehive.com>
 */
class NewRelicApiException extends Exception
{
  const USER_EXISTS = 100;

  protected $data;


  public function __construct($message, $code = 0, $data = null)
  {
    parent::__construct($message, $code);

    $this->data = $data;
  }


  public function getData()
  {
    return $this->data;
  }
}


/**
 * Class NewRelicApiTransportException
 *
 * General New Relic API exception class
 */
class NewRelicApiTransportException extends NewRelicApiException
{
  public function __construct($message, $code = 0, $data = null)
  {
    parent::__construct($message, $code, $data);
  }
}