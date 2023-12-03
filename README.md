# smart-uuid

Package provides any functions to generate and analyze UUID.

There used a specific (non-standard) algorithm to generate UUID that enables to include into UUID next information:

 - creating timestamp
 - user defined application code
 - user defined entity code 

Generated UUID has a next structure:

	SSSSSSSS-UUUU-UAAA-EEEE-RRRRRRRRRRRR

- `S` - 8 hex digits is seconds value of UUID generating timestamp
- `U` - 5 hex digits is microseconds value of UUID generating timestamp
- `A` - 3 hex digits is custom application code
- `E` - 4 hex digits is custom entity code
- `R` - 12 hex digits is random value

The application code and the object code are integer values that are packed into the generated UUID.
These codes allow you to identify (programmatically or even visually) the entity and application for which the UUID was generated.
Also, a UUID generation timestamp is packed into the UUID with an accuracy of microseconds.

## Installing

To install a package run command:

	composer require codiliateur/smart-uuid

## Using trait HasUuidPrimaryKey

Use trait `/Codiliateur/SmartUuid/Models/HasUuidPrimaryKey` in your model if you need to use UUID primary key.

Declare public properties `$appCode` and `$entityCode` in your model to tune UUID generating.

This trait overwrites `getIncrementing()` and `getKeyType()` methods. You must not to fill properties `$incrementing` and `$keyType` in the model.

The trait registers model event handler for **creating**-event to generating UUID and filling model key.

Example:

```php
use /Codiliateur/SmartUuid/Models/HasUuidPrimaryKey;

class MyModel extends Model
{
	use HasUuidPrimaryKey
	
	public $appCode = 0x002;

	public $entityCode = 0x000F;

}

```

## Helper functions

### generate_uuid()

Use generate_uuid() to generate uuid.

Syntax:

	generate_uuid( [[int $entityCode] , int $appCode] ) : string

Arguments:

* `$entityCode` - integer entity code (default - 0) in range from 0 to 65535
* `$appCode` - integer application code (default - 0) in range from 0 to 4095

Returning: Generated UUID as string.

```php
$uuid = generate_uuid(0xF,0x2);
echo $uuid; // 622235ea-8e54-f002-000f-742c8deebf77
```

### extract_uuid_part()

Extracts timestamp, application or entity code from UUID.

Syntax:

	extract_uuid_part( string $uuid, $part [, string $format] ) : mixed

Arguments:

* `$uuid` - analyzed UUID;
* `$part` - extracting part:
    * 'timestamp' - to retrieve creating uuid timestamp
    * 'app_code' - to retrieve application code
    * 'entity_code' - to retrieve entity code
* `$format` - returning value format:
    * 1 - (default) \Carbon\Carbon object;
    * 2 - \DateTime object;
    * or format string to return formatted data string. Example: `'Y-m-d H:i:s.u'`

Returning: \Carbon\Carbon | \DateTime | string.

Extracting timestamp examples:
```php
$uuid = gen_uuid(0xF,0x2);
echo extract_uuid_part($uuid, 'timestamp', 1);
// Carbon\Carbon @1701626403 {#6267
//    date: 2023-12-03 18:00:03.502460 UTC (+00:00),
// }

echo extract_uuid_part($uuid, 'timestamp', 'Y-m-d H:i:s.u');
// DateTime @1701626403 {#6317
//    date: 2023-12-03 18:00:03.502460 UTC (+00:00),
// }

echo extract_uuid_part($uuid, 'timestamp', 'Y-m-d H:i:s.u');
// 2023-12-04 19:00:39.906514
```

Extracting application code example:
```php
$uuid = gen_uuid(0xF,0x2);
echo extract_uuid_part($uuid, 'app_code');
// 2
```

Extracting entity code example:
```php
$uuid = gen_uuid(0xF,0x2);
echo extract_uuid_part($uuid, 'entity_code');
// 15
```

#### Constants

You can use next constants

    // $part constants
    Codiliateur\SmartUuid\Uuid::TIMESTAMP           = 'timestamp'
    Codiliateur\SmartUuid\Uuid::APP_CODE            = 'app_code'
    Codiliateur\SmartUuid\Uuid::ENTITY_CODE         = 'entity_code'

    // $format constants
    Codiliateur\SmartUuid\Uuid::TS_FORMAT_CARBON    = 1
    Codiliateur\SmartUuid\Uuid::TS_FORMAT_DTETIME   = 2

