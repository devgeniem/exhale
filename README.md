# Exhale exporter framework
Easy to use xml data exporter library designed for WordPress. It uses [sabre/xml](http://sabre.io/xml/) library to produce clean results.

## Installation

This isn't yet available in packagist so you need to add the repository as well
```bash
$ composer config repositories.repo-name vcs https://github.com/devgeniem/exhale
$ composer require devgeniem/exhale:dev-master
```

## Example
```php
// Create new exporter with basic data
$food_exporter = new Exhale\Exporter( [
    // This is example of basic xml node
    [
        'name' => 'kebab',
        'value'=> 'Iskender',
        'attributes' => [
            'price' => '5€'
        ]
    ],

    // You can put nested elements in value too, all of them will be parsed recursively
    [
        'name' => 'partypack',
        'attributes' => [
            'price' => '20€'
        ],
        'value'=> [
            'pizza' => 'Quattro Formaggi',
            'salad' => 'chicken',
            'drink' => 'Pepsi'
        ]
    ],

    // Elements can also be like key -> value
    // But beware, now you can't have two elements with same name as the other will get wiped out
    'pizza' => 'Margherita'
] );

// It's also possible to push one item
$food_exporter->push_item('salad', 'falafel' );
$food_exporter->push_item('salad', 'chicken', ['price' => '10$'] );

// Wrap all elements in Foods element with custom attributes, namespaces are also added into this location
$food_exporter->set_root_element( [
    'name' => 'Foods',
    'attributes' => [
        'xsi:noNamespaceSchemaLocation' => 'canonical_model.xsd'
    ]
] );

// If your xml specification has namespaces
$food_exporter->add_xml_namespace( [ 'http://www.w3.org/2001/XMLSchema-instance' => 'xsi' ] );
$food_exporter->add_xml_namespace( [ 'http://localhost/functions' => 'f' ] );

// Create endpoint for the site
// After this the site will response with xml in your path
$food_exporter->create_wp_endpoint( '/api/export/food.xml', 'xml' );

// You can also export the data as xml
$xml_string = $food_exporter->to_xml();
```

This will generate a xml response like:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<Foods xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:f="http://localhost/functions" xsi:noNamespaceSchemaLocation="canonical_model.xsd">
 <kebab price="5€">Iskender</kebab>
 <partypack price="20€">
  <pizza>Quattro Formaggi</pizza>
  <salad>chicken</salad>
  <drink>Pepsi</drink>
 </partypack>
 <pizza>Margherita</pizza>
 <salad>falafel</salad>
 <salad price="10$">chicken</salad>
</Foods>
```

## Maintainers
[Onni Hakala](https://github.com/onnimonni)

## License
MIT
