# Pdf-generator-client
[![GitHub license](https://img.shields.io/github/license/flash-global/pdf-generator-client.svg)](https://github.com/flash-global/pdf-generator-client)
![continuousphp](https://img.shields.io/continuousphp/git-hub/flash-global/pdf-generator-client.svg)
[![Build Status](https://travis-ci.org/flash-global/pdf-generator-client.svg?branch=master)](https://travis-ci.org/flash-global/pdf-generator-client)
[![GitHub issues](https://img.shields.io/github/issues/flash-global/pdf-generator-client.svg)](https://github.com/flash-global/pdf-generator-client/issues)

You can use this client to consume the Pdf Generator service.

With this client you can use one kind of transport to send the requests :

* Synchronous transport implemented by `BasicTransport`

`BasicTransport` uses the classic HTTP layer to send pdf synchronously.

# Installation

Pdf Client client needs at least PHP 5.5 to work properly.

Add this requirement to your `composer.json`: `"fei/pdf-generator-client": : "^1.0"`

Or execute `composer.phar require fei/pdf-generator-client` in your terminal.

# Entities and classes

### PdfContainer entity

PdfContainer entity has **two** important properties:

| Properties    | Type              |
|---------------|-------------------|
| data            | `string`         |
| originName     | `string`        |

* `data` is the content of the Pdf base64 encoded
* `originName` is a string representing the name of the Pdf

# Configuration

In order to make the client working properly, you need to configure a couple of parameters :.

The configuration takes place in the `config/config.php` file. Here is a complete example of the configurations :

```php
<?php
return [
    'pdf-generator-url' => 'http://pdf-generator-api.com',
];
```
* `pdf-generator-url`: The url of your pdf generator api

# Basic usage

In order to consume `PdfGenerator` methods, you have to define a new `PdfGenerator` instance and set the right transport (Asynchronously or Synchronously).


```php
<?php

use Fei\Service\Translate\Client\ Translate;
use Fei\ApiClient\Transport\BasicTransport;
use Fei\ApiClient\Transport\BeanstalkProxyTransport;
use Pheanstalk\Pheanstalk;

$pdfGenerator = new PdfGenerator([PdfGenerator::OPTION_BASEURL => 'http://pdf-generator-api.com']); // Put your translate API base URL here
$pdfGenerator->setTransport(new BasicTransport());

$proxy = new BeanstalkProxyTransport();
$proxy->setPheanstalk(new Pheanstalk('127.0.0.1'));

$pdfGenerator->setAsyncTransport($proxy);

// Use the pdf generator client methods...
```

There are several methods in Translate class, all listed in the following table:

| Method         | Parameters                                                       | Return                              |
|---------------|------------------------------------------------------------------|-------------------------------------|
| generateUrl          | `string $url`                                                        | `PdfContainer`                        |
| generateHtml           | `string $html`            | `PdfContainer`   |

### Client option

Only one option is available which can be passed either by the constructor or by calling the `setOptions` method `PdfGenerator::setOptions(array $options)`:

| Option         | Description                                    | Type   | Possible Values                                | Default |
|----------------|------------------------------------------------|--------|------------------------------------------------|---------|
| OPTION_BASEURL | This is the server to which send the requests. | string | Any URL, including protocol but excluding path | -       |


**Note**: All the examples below are also available in the examples directory.