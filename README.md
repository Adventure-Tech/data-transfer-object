# Data transfer object

This is a strict and opinionated implementation of data transfer objects. One of the goals
of this package is to create DTO's that are as predictable as PHP will allow.

Where other implementations are very general purpose, this package comes with a lot of rules and conventions on how to 
create and instantiate DTO's. It follows a sort of 'Create with care, use with ease' sort of mentality.

This package is created for Laravel, but can easily be modified to work in other frameworks, or with vanilla PHP.

## Requirements

* PHP ^8.1

## Installation

Install the package via composer:

```bash
composer require adventure-tech/data-transfer-object
```

## Usage

### Instantiating a DTO
Instantiating a new DTO is very simple. There is one, and <ins>only one</ins> way of doing it, 
namely by calling the static method *from()* on the DTO class like this: 

```php
$dto = MyDTO::from($source);
```

The method accepts one argument which is the source array or object  that your DTO will map it's properties from.
The argument can be one of three types:
* *array* | An associative array with key value pairs matching your DTO
* *stdClass* | A generic php object with properties matching your DTO
* *Model* | A Laravel Eloquent model with attributes matching your DTO

### Creating new DTO classes

Here is a basic example of a new DTO called *User*:

```php
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;

class User extends DataTransferObject
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public Carbon $created_at;
    public Carbon $deleted_at;
}
```
This is the simplest, but also the most strict definition of a DTO.
There are a few rules and assumptions you need to be aware of when creating a
new DTO. Note that the some of the default behaviour can be modified by using
one of the provided [attributes](https://www.php.net/manual/en/language.attributes.overview.php)

### Rules, conventions and how to modify them

#### Property type declaration

Every property must be declared with a type. Always.

#### Visibility modifiers

Every property you want to be auto assigned from the source needs to be *public*.

#### Nullable properties
You can make a property nullable, e.g ?string, but the DTO will still expect
the corresponding property on the source to be present, even though it's value is *null*.

>By using the attribute **#[Optional]** on a property, you can modify this behaviour.
The DTO will no longer care if the property is instantiated, present on the source or 
instantiated with a null value (given the field is declared nullable).

#### Naming properties

The DTO will expect the corresponding class property name or array key on the source to be named exactly the same
as the DTO property.

> By using the attribute **#[MapFrom]** you can override which property name or key the DTO will
be looking for on the source. See example below.

```php
#[MapFrom('first_name')]
public string $firstName;
```

#### Default values

By using the attribute **#[DefaultValue]** you can define a default value for the property.

If the DTO property is declared nullable, the default value will be assigned if the source property is *null*.

If the DTO property is non nullable, the default value will work as a fallback if the corresponding source
property is not present, or it's value is *null*.

```php
#[DefaultValue(false)]
public bool $isAdmin;
```

#### Handling dates

If your DTO property is declared with the type Carbon, the DTO will automatically cast the source date/datetime value to
Carbon before assigning it.

```php
// The DTO will look for the created_at property on the source, and cast it to Carbon
#[MapFrom('created_at')]
public Carbon $createdAt;
```

#### Boolean properties

If your database uses int/tinyint to represent boolean values, they will automatically be cast to bool as
long as the DTO property is declared with the *bool* type.

```php
// The source property value can be true/false or 0/1
public bool $isPaid;
```

#### Mapping from JSON

The package uses the [JsonMapper](https://github.com/cweiske/jsonmapper) library to work with database json fields. 
The library allows us to map entire json structures to a nested DTO structure.

The way this works is that you need to specify a root class on your DTO and annotate it with the ``#[JsonMapper]``
attribute. This root class should not be an instance of the DataTransferObject, but instead be a POPO 
(Plain Old Php Object). Each sub class matching the JSON structure should also be a POPO with docblock annotations 
according to the library api. Visit the library's GitHub page to read more on how use nested structures.

```php
// The main DTO
class Person extends DataTransferObject
{
    #[MapFrom('first_name')]
    public string $firstName;

    #[JsonMapper(Address::class)]
    public Address $address;
}
```

```php
// The Address POPO
class Address
{
    public string $street;
    public string $zip;
    public string $city;
}
```

```javascript
let sourceJsonStructure = {
    "first_name": "John",
    "address": {
        "street": "Example street",
        "zip": "0000",
        "city": "Example city" 
    }
}
```

#### Mapping from Enum
If your database field is of the type enum, you can create a corresponding enum class
in you application and use it as type for your DTO property, and the value will automatically
be cast to the correct enum value. It is important to note that your enum class must be backed
by int or string to make this work. Read more about backed enums [here](https://www.php.net/manual/en/language.enumerations.backed.php).

```php
// The enum value from the database will be mapped to the correct enum value.
public MyEnum $myEnum;
```

#### Immutable fields

Sadly PHP doesn't support immutable objects (yet). The *readonly* declaration is not supported by this
package. The reason is that the parent *DataTransferObject* relies on *Reflection* to assign values to
whichever child class being instantiated. PHP does not allow a parent class to assign *readonly* properties
to it's children.

PhpStorm comes with the attribute **#[Immutable]** built into the IDE. You have the opportunity to use this
attribute on your DTO properties, but all it accomplishes is a warning in the IDE when you are about to 
assign a value to an immutable property. It does not prevent you from doing so.

```php
#[Immutable]
public string $weCanPretendThisIsImmutable;
```
#### Triggers

The `#[Trigger]` attribute provides a way do initialize properties after other properties mapped from source have been initialized.
Typical use of this attribute is that you want to do some computation on the DTO data, and store it as a property.

The attribute requires one argument, namely the method that will be triggered to initialize the property.
Let's take a look at an example where we want to concatenate the user's name and store it as a new property.

```php
class User extends DataTransferObject
{
    #[MapFrom('first_name')]
    public string $firstName;

    #[MapFrom('last_name')]
    public string $lastName;

    #[Trigger('setFullName')]    
    public string $fullName;
    
    protected function setFullName()
    {
        $this->fullName = $this->firstName . ' ' . $this->lastName; 
    }
}
```

## Laravel Artisan

The package comes with an artisan command to create new DTO classes:

```bash
php artisan make:dto --name=MyDTO
```

## Examples

### Laravel example

Define the DTO:

```php
use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class User extends DataTransferObject
{
    public int $id;
    #[MapFrom('first_name')]
    public string $firstName;
    #[MapFrom('last_name')]
    public string $lastName;
    public string $email;
    #[MapFrom('created_at')]
    public Carbon $createdAt;
    #[MapFrom('deleted_at')]
    public Carbon $deletedAt;
    #[Optional]
    public string $iAmNotImportant;
    // Relations
    #[DefaultValue([])]
    public array $posts;
}
```

Use the DTO:

```php
$eloquentModel = App\Models\User::find(1);

$dto = App\Dto\User::from($eloquentModel);
```
