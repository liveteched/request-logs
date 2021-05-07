# Log API requests inside your Laravel app

The `shambou/request-logs` package provides way to store API call request/response data with headers and custom attributes
The Package stores all requests in the `request_logs` table with ability to define polymorphic relationships in `request_log_relations` table. 

The Package doesn't provide **views** to see logs, you can do that yourself if you need it.

There are two optional fields in `request_logs` table
- `channel` - used to set unique channel that is tied to API user. Ex: `client1-api` 
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
     * If set to false, request logging from middleware will go to the default queue
     */
    'queue' => false,

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

## Example usage 1: Middleware

If middleware is used as is request logs will always save:
- request_logs.action = default 
- request_logs.channel = default

To get this right, where ever you define middleware you should pass

```
request()->attributes->add([
  'action'   => 'some-action-name',
  'channel'  => 'channel-name',
  'relation' => Model instance ex: User::find(1)
  'custom_data' => []
]);
```
To use middleware integration call in controller constructor:
```
public function __construct()
{
    $this->middleware('requestlogs');

    request()->attributes->add([
        'action' => 'login',
        'channel' => 'client1-api',
    ]);
}
```

#### Note: *Currently, only Rest API logging is supported by this middleware*

## Example usage 2: REST

```
$startTime = microtime(true);
$response = response()->json([
    'success' => true
]);

RequestLogFactory::buildFromCurrentRequest($request)
    ->setJsonResponse($response)
    ->storeLog([
        'action'           => 'login',
        'channel'          => 'client-api',
        'method'           => $request->getMethod(), // GET, POST, PATCH etc 
        'execution_time'   => microtime(true) - $startTime,
        'custom_data'      => [
            'transaction_id' => '32132131'
        ]
], User::find(1));
```

## Example usage 2: SOAP

```
$wsdlUrl = 'https://api.example.com/soap/V10023.ASMX?WSDL'
$startTime = microtime(true);

RequestLogFactory::buildFromSoapClient($wdsUrl, $soapClient)
    ->storeLog([
        'action'           => 'Login',
        'channel'          => 'client-soap-api',
        'method'           => $request->getMethod(), // GET, POST, PATCH etc 
        'execution_time'   => microtime(true) - $startTime,
        'custom_data'      => [
            'transaction_id' => '32132131'
        ]
], User::find(1));
```
