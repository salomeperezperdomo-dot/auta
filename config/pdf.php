<?php

return [
    'driver' => Spatie\LaravelPdf\Drivers\DomPdfDriver::class,

    'drivers' => [
        'dompdf' => [
            'driver' => Spatie\LaravelPdf\Drivers\DomPdfDriver::class,
            'options' => [
                'defaultFont' => 'sans-serif',
            ],
        ],
        'browsershot' => [
            'driver' => Spatie\LaravelPdf\Drivers\BrowsershotDriver::class,
            'options' => [
                'timeout' => 30,
            ],
        ],
    ],

    'default' => 'dompdf',
];
