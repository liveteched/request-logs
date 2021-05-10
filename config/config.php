<?php

return [
    /*
     * Currently supports only json and soap channels
     * Keep in mind that all channels must be defined in single dimension:
     * ex: 'json' => ['client-api', 'second-client-api']
     *
     * Used to parse request/response data from DB
     * ex:
     * {
     *   "data1": "something",
     *   "data2": "something",
     *   "data3": "something"
     * }
     *
     * will become:
     *
     * data1: something\n
     * data2: something\n
     * data3: something
     *
     * This is attached automatically to \Models\RequestLog parsed_request and parsed_response attributes
     */
    'channels' => [
        'json' => [],
        'soap'  => []
    ]
];
