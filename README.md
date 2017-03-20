# ShareEconomyPayFortBundle
This is a symfony bundle to integrate easly with PayFort payment gateway.

So far this bundle supports the following features:
- Dealing with different payfort environments (sandbox and production).
- Calculating requests signatures and verify responses signatures.
- Easly issue a request with payfort API.
- Requsting mobile SDK token.
- Issuing purchase.
- Checking specific transaction status.
- Logging transaction statuses history in the DB.
- Creating dynamic relation between payment method model and your user model.
- Creating dynamic relation between bundle payfort transaction model and your invoice model.
- Command for updating transactions statuses periodically.
- Event will be dispatched after any transaction status.
- Updating transactions statuses when payfort send a request to the transaction notification feedback action.
- Ready APIs for:
  - Requesting mobile SDK token.
  - Managing users payment methods (add, edit, delete, list, view details and set default payment method).

# Installation:

1. In your project composer.json file add the bundle installation path:

```yml
    "extra": {
        "installer-paths": {
            "src/Ibtikar/ShareEconomyPayFortBundle/": ["Ibtikar/ShareEconomyPayFortBundle"]
        }
    }
```

2. Require the package using composer by running the following command:

    composer require Ibtikar/ShareEconomyPayFortBundle

3. Register the bundle and LswApiCaller bundle to your AppKernel.php file as following:

```php
    new Lsw\ApiCallerBundle\LswApiCallerBundle(),
    new Ibtikar\ShareEconomyPayFortBundle\IbtikarShareEconomyPayFortBundle(),
```

# Configuration

1. Add the following route to your routing file:

```yml
    ibtikar_share_economy_payfort:
        resource: "@IbtikarShareEconomyPayFortBundle/Resources/config/routing.yml"
        prefix:   /
```

2. Add the next line to your .gitignore file:

    /src/Ibtikar/ShareEconomyPayFortBundle

3. Run doctrine migrations command (Recommendation: add this command to your git post-merge file):

```cli
    bin/console doctrine:migrations:migrate --env=prod --no-debug --configuration=src/Ibtikar/ShareEconomyPayFortBundle/Resources/config/migrations.yml
```

4. Configure the bundle as following:

- In your project parameters.yml.dist add the following block:

```yml
    payfort_environment: "sandbox"  # active environment. expected values (sandbox or production)
    payfort_sandbox_merchantIdentifier: null
    payfort_sandbox_accessCode: null
    payfort_sandbox_shaType: null
    payfort_sandbox_shaRequestPhrase: null
    payfort_sandbox_shaResponsePhrase: null
    payfort_production_merchantIdentifier: null
    payfort_production_accessCode: null
    payfort_production_shaType: null
    payfort_production_shaRequestPhrase: null
    payfort_production_shaResponsePhrase: null
    payfort_currency: null   # AED, SAR, ....
```
- Add the same previous block to your parameters.yml file then fill it with your payfort security configurations.
- Add the bundle configurations in your config.yml:
```yml
      ibtikar_share_economy_pay_fort:
          environment: "%payfort_environment%"
          sandbox:
              merchantIdentifier: "%payfort_sandbox_merchantIdentifier%"
              accessCode: "%payfort_sandbox_accessCode%"
              shaType: "%payfort_sandbox_shaType%"
              shaRequestPhrase: "%payfort_sandbox_shaRequestPhrase%"
              shaResponsePhrase: "%payfort_sandbox_shaResponsePhrase%"
          production:
              merchantIdentifier: "%payfort_production_merchantIdentifier%"
              accessCode: "%payfort_production_accessCode%"
              shaType: "%payfort_production_shaType%"
              shaRequestPhrase: "%payfort_production_shaRequestPhrase%"
              shaResponsePhrase: "%payfort_production_shaResponsePhrase%"
          prevent_last_payment_method_removal: true # whether prevent user from deleting his last payment method or not (true or false)
          currency: "%payfort_currency%"
```

5. Add api caller configuration block in your project config.yml parameters section     # https://github.com/LeaseWeb/LswApiCallerBundle#configuration

```yml
    parameters:
        api_caller.options:
            timeout: 10  # maximum transport + execution duration of the call in sec.
            ssl_verifypeer: false  # to stop cURL from verifying the peer's certificate.
            useragent: "LeaseWeb API Caller"  # contents of the "User-Agent: " header.
            followlocation: true  # to follow any "Location: " header that the server sends.
            sslversion: 3  # set to 3 to avoid any bugs that relate to automatic version selection.
            fresh_connect: false  # set to true to force full reconnect every call.
```

6. Relate your payment methods holder model to the payment method model and your invoice model to payfort transactions model:

- Your holder model should implement
```php
Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethodHolderInterface
```
- Your invoice model should implement
```php
Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface
```
- Use PfPaymentMethodHolderTrait trait inside your holder model by adding the following line inside it:

```php
      use \Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethodHolderTrait;
```

- Use PfTransactionInvoiceTrait trait inside your invoice model by adding the following line inside it:

```php
      use \Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceTrait;
```
- In your config.yml update doctrine section:

```yml
      doctrine:
          orm:
              resolve_target_entities:
                  Ibtikar\ShareEconomyPayFortBundle\Entity\PfPaymentMethodHolderInterface: {your holder class full qualified name space. ex: AppBundle\Entity\User}
                  Ibtikar\ShareEconomyPayFortBundle\Entity\PfTransactionInvoiceInterface: {your invoice class full qualified name space. ex: AppBundle\Entity\Invoice}
```

# create transaction status change listener:

create a service class and inject it with whatever you want

```php
<?php

namespace AppBundle\Service\Listener;

use Ibtikar\ShareEconomyPayFortBundle\Events\PfTransactionStatusChangeEvent;

/**
 * Description of PfTransactionStatusChangeListener
 */
class PfTransactionStatusChangeListener
{
    private $em;

    /**
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param PfTransactionStatusChangeEvent $event
     */
    public function successTransaction(PfTransactionStatusChangeEvent $event)
    {
        $transaction = $event->getTransaction();
        $myInvoice   = $transaction->getInvoice();
        
        // take some actions
    }

    /**
     * @param PfTransactionStatusChangeEvent $event
     */
    public function failTransaction(PfTransactionStatusChangeEvent $event)
    {
        $transaction = $event->getTransaction();
        $myInvoice   = $transaction->getInvoice();

        // take some actions
    }
}
```

# install cronjob to periodically update transactions statuses from payfort

```cli
bin/console ibtikar:share-economy-payfort:update-transactions-status --env=prod --no-debug
```

# Usage

1 . Pay invoice example:

```php
// while $invoice is an instance of your invoice model
$this->get('ibtikar.shareeconomy.payfort.PaymentOperations')->payInvoice($invoice);
```
