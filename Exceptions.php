<?php


/**
 * Class NewRelicApiException
 *
 * General New Relic API exception class
 *
 * @author Robin Corps <robin@wirehive.net>
 */
class NewRelicApiException extends Exception
{
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