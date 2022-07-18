<?php 

return [
    'momo' => [
        'endpoint_url' => env('MOMO_ENDPOINT_URL', 'https://test-payment.momo.vn'),
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
        'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
        'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
        'method_payment' => [
            '1' => 'payWithATM',
            '2' => 'captureWallet',
            '3' => 'payWithMethod'
        ]
    ]

];
