<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '1789766641297087',
            'client_secret' => '07b1ad2c36fedd7650dfdfd42dd5337f',
            'scope'         => array(),
        ),	

        'Google' => array(
		    'client_id'     => '562343786028-vojuv06cfmf5amlv3a3i7ls2vnvcfpg2.apps.googleusercontent.com',
		    'client_secret' => 'SxGoRVaRCTVdeT_Ew2f9RP3d',
		    'scope'         => array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'),
		),  

		'Twitter' => array(
		    'client_id'     => 'Your Twitter client ID',
		    'client_secret' => 'Your Twitter Client Secret',
		    // No scope - oauth1 doesn't need scope
		),  	

	)



);