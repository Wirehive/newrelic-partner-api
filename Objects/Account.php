<?php


/**
 * Class NewRelicPartnerAPIAccount
 */
class NewRelicPartnerAPIAccount
{
  /** @var NewRelicPartnerAPI $api */
  private $api;

  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }


  public function getList()
  {
    return $this->api->call('accounts');
  }
} 