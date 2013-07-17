<?php
return array(

	'components'=>array(
			'loid' => array(
		        'class' => 'account.extensions.loid.loid',
		    ),
		    'eauth' => array(
		        'class' => 'account.extensions.eauth.EAuth',
		        'popup' => true, // Use the popup window instead of redirecting.
		        'services' => array( // You can change the providers and their classes.
		            'facebook' => array(
		                'class' => 'FacebookOAuthService',
		                'client_id' => '179800975411523',//'433965203313005',//'170916972965108',547520715276197
		                'client_secret' => '458af358c13f5658dc682e7d13b0c505',//'d531e8200d88eee00f5eb61f5ff2ec65',
						'scope' => 'email',
					),
                'google' => array(
	                'class' => 'GoogleOpenIDService',
	           ),
		        /* 	'twitter' => array(
		                'class' => 'TwitterOAuthService',
		                'key' => '...',
		                'secret' => '...',
		            ),
		            'yandex' => array(
		                'class' => 'YandexOpenIDService',
		            ),
		            'vkontakte' => array(
		                'class' => 'VKontakteOAuthService',
		                'client_id' => '...',
		                'client_secret' => '...',
		            ),
		            'mailru' => array(
		                'class' => 'MailruOAuthService',
		                'client_id' => '...',
		                'client_secret' => '...',
		            ),*/
		        ),
		    )
		)
);
