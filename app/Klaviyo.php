<?php

namespace App;

use Siro\Klaviyo\KlaviyoAPI;

abstract class Klaviyo {

    private static $private_key = 'pk_a6d6dde87c39934bc2840450fd53e23d54';

    public static function get_user(string $email) {
        $request = wp_remote_get(
            "https://a.klaviyo.com/api/v2/people/search?email=$email",
            array(
                'headers' => [
                  'api-key' => self::$private_key,
                ]
            )
        );

        $body = wp_remote_retrieve_body( $request );

        return json_decode($body, true);
    }

    public static function send_template( $template_id, $subject, $to, array $context = [] ) {

       $klaviyo = new KlaviyoAPI(self::$private_key);
       $klaviyo->template->send(
           $template_id,
           'lets@willo.com',
           'Willo 32, inc',
           $subject,
           $to,
           $context
       );
    }

}
