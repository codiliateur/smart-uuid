# smart-uuid

This package provides functions for generate UUID with specific (not-standard) algorithm
that enable to include into UUID next information:

 - creating timestamp
 - user defined application code
 - user defined entity code

## Installation

Run command

    composer require codiliteur/smqrt-uuid

## Functions

### generate_uuid()

Cenerates uuid

    generate_uuid(int $entity_code = 0, int $app_code = 0); 

Examples

```
> $entity_code = 15; 
> $app_code = 3;
> generate_uuid($entity_code, $app_code)
= "656cc223-7aab-c003-000f-b1b2375b141d"

> generate_uuid()
= "656cc240-7ec6-6000-0000-3f7858f0bae5"
```

### extract_uuid_part()

Extracts any part from uuid 

    extract_uuid_part(string $uuid, string $part, $format = null)

Examples
```
> $uuid = '656cc223-7aab-c003-000f-b1b2375b141d'

> extract_uuid_part($uuid, 'entity_code')
= 15

> extract_uuid_part($uuid, 'app_code')
= 3

> extract_uuid_part($uuid, 'timestamp')
= Carbon\Carbon @1701626403 {#6267
    date: 2023-12-03 18:00:03.502460 UTC (+00:00),
  }
  
> extract_uuid_part($uuid, 'timestamp', 1)
= Carbon\Carbon @1701626403 {#6267
    date: 2023-12-03 18:00:03.502460 UTC (+00:00),
  }
  
> extract_uuid_part($uuid, 'timestamp', 2)
= DateTime @1701626403 {#6317
    date: 2023-12-03 18:00:03.502460 UTC (+00:00),
  }

> extract_uuid_part($uuid, 'timestamp','Y-m-d H:i:s.u')
= "2023-12-03 18:00:03.502460"

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
