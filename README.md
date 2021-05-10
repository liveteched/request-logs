# Log API requests inside your Laravel app

The `shambou/request-logs` package provides way to store API call request/response data with headers and custom attributes
The Package stores all requests in the `request_logs` table with ability to define polymorphic relationships in `request_log_relations` table. 

The Package doesn't provide **views** to see logs, you can do that yourself if you need it.

There are two optional fields in `request_logs` table
- `channel` - used to set unique channel name for API. Ex: `client1-api` 
- `action`  - used to set API action. Ex: `login`, `retrieve`, `event_modified` etc

Both are used for easier searching/handling DB records

## Installation:
```
$ composer require shambou/request-logs
$ php artisan requestlogs:install
$ php artisan migrate
```

## Configuration:
```
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
```
## Example usage 1: REST

```
$startTime = microtime(true);
$response = response()->json([
    'success' => true
]);

$relation = User::find(1);

RequestLogFactory::createForRest($request)
    ->setJsonResponse($response)
    ->setAction('login')
    ->setChannel('client-api')
    ->setExectionTime(microtime(true) - $startTime)
    ->setCustomData([
        'transaction_id' => 'ecfe78cc-10ce-49d2-bb31-29b01da03fc6'
    ])
    ->storeLog($relation);
```

## Example usage 2: SOAP

```
$wsdlUrl = 'https://api.example.com/soap/V10023.ASMX?WSDL'
$startTime = microtime(true);
$relation = User::find(1);

RequestLogFactory::createForSoap($wdsUrl, $soapClient)
    ->setAction('login')
    ->setChannel('client-soap-api')
    ->setExectionTime(microtime(true) - $startTime)
    ->setCustomData([
        'transaction_id' => 'ecfe78cc-10ce-49d2-bb31-29b01da03fc6'
    ])
    ->storeLog($relation);
```
