<?php

    $spBaseUrl = 'https://vcisprod.clarion.edu/~s_smwice/ClarionResourceRoom/ResourceRoom/security/php-saml-2.19.1/'; //or http://<your_domain>

    $settingsInfo = array (
        'sp' => array (
            'entityId' => $spBaseUrl.'demo1/metadata.php',
            'assertionConsumerService' => array (
                'url' => $spBaseUrl.'demo1/index.php?acs',
            ),
            'singleLogoutService' => array (
                'url' => $spBaseUrl.'demo1/index.php?sls',
            ),
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        ),
        'idp' => array (
            'entityId' => 'https://idp.clarion.edu/idp',
            'singleSignOnService' => array (
                'url' => 'https://idp.clarion.edu/idp/profile/SAML2/Redirect/SSO',
            ),
            'singleLogoutService' => array (
                'url' => 'https://idp.clarion.edu/idp/profile/SAML2/Redirect/SLO',
            ),
            'x509cert' => '-----BEGIN CERTIFICATE-----
                            MIIDJzCCAg+gAwIBAgIUBafbshnnIQs9vn04YDHVguyflZswDQYJKoZIhvcNAQEF
                            BQAwGjEYMBYGA1UEAxMPaWRwLmNsYXJpb24uZWR1MB4XDTEyMTIxODE1MDc0N1oX
                            DTMyMTIxODE1MDc0N1owGjEYMBYGA1UEAxMPaWRwLmNsYXJpb24uZWR1MIIBIjAN
                            BgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhQ1hrfd9TG7GUt01LXLCXY3Sb7we
                            rvPCxnff63bJlvrDprQwhLU0gZcOYPxHgnj9xCglvGzfxHYN7k6b8rmtSYE+i/As
                            eAmxIadE8UtFyhzQigpmbkuc26T/mrKBH4X5fJC6Shv7bhGMkxAdIVfVKVAk8z2e
                            JV10baku4n3heyJ+FpmybPGmgkxAS1zbgC2MZJUkZAW+7C6+FoUgWCezCA0+VB87
                            6QiMeX04Y6PG4FimGp6WJWDkW2KGPalIv6fdJAZOrOg3aJakpJFPyg1fUaEUHIPd
                            1ip+P7DisITQeRwVqOiW5N9UTUktba0X6R0BqHuQlbfV5bIUDicSpP7iqwIDAQAB
                            o2UwYzBCBgNVHREEOzA5gg9pZHAuY2xhcmlvbi5lZHWGJmh0dHBzOi8vaWRwLmNs
                            YXJpb24uZWR1L2lkcC9zaGliYm9sZXRoMB0GA1UdDgQWBBQvjjlxOgaZw1afROEy
                            0g3wElY5ZjANBgkqhkiG9w0BAQUFAAOCAQEABLcQuMrWaIjTiWPEowG+sQRwnewI
                            OkmtVC/r2hAz7p/m0WvdPFzcciGY2h6j/jqfH+iy9sYH4yM9u4J9WAumQK1Q4GlI
                            0IcvbzMS/z0J4c7DZMf5o2bOW2nNnYH+snLifzQ3oFdb/41ZHc4DJPmdUQrGe5zu
                            R/+XlwCtHu5YnWULAF6CiyS7z1KXj12fT8a4brLHLV1WGDYeOP3Wt0gF0lalQ+0x
                            EnfRqvXvlWlxTuzRLDqKXzowjyAsGsxCItlvmIwANgYxn1SxDBTW0ZwMZ6lVR0F2
                            8ergXvj+UYo/oR2H/HIprHaREQadi68xBDCSKMaZVqbdk/DfFzNiUEeOyQ==
                           -----END CERTIFICATE-----
',
        ),
    );
