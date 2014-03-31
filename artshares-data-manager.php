<?php

/*
Plugin Name:    ArtShares Data Manager
Plugin URI:     http://aidstaskforce.org/technology
Description:    Wordpress plugin that interfaces with the ATGC Formstack account and the forms specific to the ArtCares event process.
Version:        0.0.5
Author:         Miquel Brazil [thingwone]
Author URI:     http://thingworld.com/aboutme
License:        MIT
*/

define('CLIENT_ID', '11880');
define('CLIENT_SECRET', '98e576a1c6');
define('REDIRECT_URL', get_permalink());

define('SERVICE_URL', 'https://www.formstack.com/api/v2');
define('AUTHORIZE_EP', '/oauth2/authorize');
define('AUTHORIZE_URL', SERVICE_URL.AUTHORIZE_EP);
define('TOKEN_EP', '/oauth2/token');
define('TOKEN_URL', SERVICE_URL.TOKEN_EP);

?>