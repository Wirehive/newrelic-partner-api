<?php


/**
 * Class NewRelicPartnerAPIUser
 */
class NewRelicPartnerAPIUser
{
  /** @var NewRelicPartnerAPI $api */
  private $api;

  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }
} 