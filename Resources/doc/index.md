Installation steps
==================

1.In your project composer.json file "extra" section add the following information

    "extra": {
        "installer-paths": {
            "src/Ibtikar/ShareEconomyPayFortBundle/": ["Ibtikar/ShareEconomyPayFortBundle"]
        }
    }

2.Require the package using composer by running

    composer require Ibtikar/ShareEconomyPayFortBundle

3.Add to your appkernel the next line
    new Lsw\ApiCallerBundle\LswApiCallerBundle(),
    new Ibtikar\ShareEconomyPayFortBundle\IbtikarShareEconomyPayFortBundle(),

4.Add this route to your routing file

    ibtikar_share_economy_payfort:
        resource: "@IbtikarShareEconomyPayFortBundle/Resources/config/routing.yml"
        prefix:   /

5.Add the next line to your .gitignore file

    /src/Ibtikar/ShareEconomyPayFortBundle

6.Run doctrine migrations command

    bin/console doctrine:migrations:migrate --configuration=src/Ibtikar/ShareEconomyPayFortBundle/Resources/config/migrations.yml

7.Add PayFort sectrity settings

    -Add the following block to your project parameters.yml

    # payfort configuration
    payfort_environment: "sandbox"
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

    -Add the bundle configurations as following in your config.yml
    
    ibtikar_share_economy_pay_fort:
        environment: "sandbox"
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

8.Add api caller options under your project config.yml parameters section     # https://github.com/LeaseWeb/LswApiCallerBundle#configuration

    parameters:
        api_caller.options:
            timeout: 10  # maximum transport + execution duration of the call in sec.
            ssl_verifypeer: false  # to stop cURL from verifying the peer's certificate.
            useragent: "LeaseWeb API Caller"  # contents of the "User-Agent: " header.
            followlocation: true  # to follow any "Location: " header that the server sends.
            sslversion: 3  # set to 3 to avoid any bugs that relate to automatic version selection.
            fresh_connect: false  # set to true to force full reconnect every call.

9.Now you can use the service as following:
    
    $payfortIntegration = $this->get('ibtikar.shareeconomy.payfort.integration');
    $response = $payfortIntegration->storeCustomerCredit("4005550000000001", "123", "0517");