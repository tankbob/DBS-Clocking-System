<?php

return [

  /**
   * Should we send errors to Airbrake
   */
  'enabled'             => true,

  /**
   * Airbrake API key
   */
  'api_key'             => 'd4dbd16fa10ec6567cb13d2878d0d4e1',

  /**
   * Should we send errors async
   */
  'async'               => false,

  /**
   * Which enviroments should be ingored? (ex. local)
   */
  'ignore_environments' => [],

  /**
   * Ignore these exceptions
   */
  'ignore_exceptions'   => [],

  /**
   * Connection to the airbrake server
   */
  'connection'          => [

    'host'      => 'helio-errbit.herokuapp.com',

    'port'      => '443',

    'secure'    => true,

    'verifySSL' => true
  ]

];
