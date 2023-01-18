# omnipay-dojo-checkout

**Dojo (Checkout) driver for the Omnipay PHP payment processing library**

Omnipay implementation of the Dojo payment gateway using their "Checkout" process.

## Compatibility

This is only compatible with Omnipay v2.

## Installation

The Dojo (Checkout) Omnipay driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "digitickets/omnipay-Dojo-checkout": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## What's Included

- Basic integration with Dojo Checkout's asynchronous payment gateway.

- Supports partial refunds.

- Extra Configuration parameters required:
```
apiKey - This is the key provided by Dojo.
```
- To confirm the payment has been made, `notifyUrl` is a required parameter. 
A global webhook will be set up at Dojo with this URL, and the postback from Dojo will need passing through into `acceptNotification()`. 
- Make sure that `notifyUrl` is always the same URL! As it will automatically create a new global webhook at Dojo for every new notifyUrl passed in.


## Basic Usage

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

Dojo documentation is here: [Dojo Checkout](https://docs.dojo.tech/docs/accept-payments/checkout-page/step-by-step-guide)

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-stripe-checkout/issues),
or better yet, fork the library and submit a pull request.
