# Part I - Add A Building

We're going to add the first action to our buildings application. In a CQRS system, such as
Event Engine, operations and processes are triggered by messages. Those messages can have three different types and
define the API of the application. In the first part of the tutorial we learn the first message type: `command`.

## API

The Event Engine skeleton includes an API folder (src/Api) that contains a predefined set of `EventEngineDescription` classes.
We will look at these descriptions step by step and start with `src/Api/Command.php`:

```php
<?php

declare(strict_types=1);

namespace App\Api;

use Prooph\EventEngine\EventEngine;
use Prooph\EventEngine\EventEngineDescription;

class Command implements EventEngineDescription
{
    /**
     * Define command names using constants
     *
     * @example
     *
     * const REGISTER_USER = 'RegisterUser';
     */


    /**
     * @param EventEngine $eventEngine
     */
    public static function describe(EventEngine $eventEngine): void
    {
        //Describe commands of the service and corresponding payload schema (used for input validation)
    }
}

```

The `Command` description is used to group all commands of our application into one file and add semantic meaning to our
code. Replace the comment with a real constant `const ADD_BUILDING = 'AddBuilding';` and register the command in the
`describe` method.

```php
<?php

declare(strict_types=1);

namespace App\Api;

use Prooph\EventEngine\EventEngine;
use Prooph\EventEngine\EventEngineDescription;
use Prooph\EventEngine\JsonSchema\JsonSchema;

class Command implements EventEngineDescription
{
    const ADD_BUILDING = 'AddBuilding';

    /**
     * @param EventEngine $eventEngine
     */
    public static function describe(EventEngine $eventEngine): void
    {
        $eventEngine->registerCommand(
            Command::ADD_BUILDING,
            JsonSchema::object(
                [
                    'buildingId' => JsonSchema::uuid(),
                    'name' => JsonSchema::string(['minLength' => 2])
                ]
            )
        );
    }
}

```
Event Engine uses [JSON Schema](http://json-schema.org/) to describe messages.
The advantage of JSON schema is that we can configure validation rules for our messages. Whenever Event Engine receives a message
(command, event or query) it uses the defined JSON Schema for that message to validate the input. We configure it once
and Event Engine takes care of the rest.

*Note: The skeleton defines the namespace `App` in composer.json and maps it to the `src` directory. You can change that for your own project, but
you need to change the namespaces of the classes/interfaces shipped with the skeleton which are located in the `src` directory (just a few helpers)*

## Descriptions

Event Engine Descriptions are very important. They are called at "**compile time**" and used to configure Event Engine.
Later in the tutorial we learn more about using Event Engine in production. In production mode the descriptions are only
called once and cached to speed up bootstrapping.

## Swagger Integration

Switch to the Swagger UI and reload the schema (press explore button).
Swagger UI should show a new **command** called `AddBuilding` in the commands section.

Click on the "Try it out" button and **execute** the `AddBuilding` command with the this request body:

```json
{
  "payload": {
    "buildingId": "9ee8d8a8-3bd3-4425-acee-f6f08b8633bb",
    "name": "Acme Headquarters"
  }
}
```

```json
{
  "error": {
    "message": "CommandBus was not able to identify a CommandHandler for command AddBuilding",
    "details": "..."
  }
}
```

Our command cannot be handled because a command handler is missing. In Event Engine
commands can be routed directly to `Aggregates`.
In **part II** of the the tutorial you'll learn more about pure aggregates.

*Sum up: Event Engine Descriptions allow you to easily describe the API of your application using messages. The messages get
a unique name and their payload is described with JSON Schema which allow us to add validation rules. The messages and their
schema are translated to an OpenAPI v3 Schema and we can use Swagger UI to interact with the backend
service.*










