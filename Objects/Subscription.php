<?php


/**
 * Class NewRelicPartnerAPISubscription
 */
class NewRelicPartnerAPISubscription
{
  /** @var NewRelicPartnerAPI $api */
  private $api;

  public function __construct(NewRelicPartnerAPI $api)
  {
    $this->api = $api;
  }
} 