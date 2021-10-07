Adflex - b2b payments made easy

Adflex has been at the heart of the fintech revolution from the beginning.
We are known for fostering innovation and helping companies harness the power of digital payments.
Our technology and expertise bring together buyers and suppliers to make transactions fast,
cost-effective and straightforward to manage. We take the pain out of the supply chain by
delivering seamless and secure payment integration that adds value to both buyers and merchants.

Features:

- Fully PCI-DSS compliant payment method utilising Adflex's AHPP API.
- Saved cards functionality implemented via Magento Vault.
- View/remove saved cards in Magento Customer Portal.
- Choose between a modal window or inline credit card fields.
- Uses store logo for lightbox, show your store logo on Adflex hosted forms.
- JWT token based authorisation for enhanced security.
- Level 1 and Level 3 credit/debit card support.
- 3DSecure V1 and V2.
- 3DS + AVS details (+ relevant card details) logged in Magento order view.
- Supports Magento 2.1.x, 2.2.x and 2.3.x
- Authorise Only or Authorise + Capture.

Installation:

Step 1:

Extract extension into: <magento root>/app/code (if installing from Magento marketplace).
If using linux please simply copy and paste this command:

bin/magento module:enable Adflex_Payments && bin/magento setup:upgrade && bin/magento setup:di:compile 
&& bin/magento setup:static-content:deploy en_US en_GB <+ any additional store locales you may have>

These commands can be run procedurally as so:

bin/magento module:enable Adflex_Payments
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy en_US en_GB <+ any additional store locales you may have>

Step 2:

Adflex uses JWT (JSON Web Token) for authorisation requests, you must install the lcobucci/jwt library via composer 
for the extension to work properly.

Please see: https://github.com/lcobucci/jwt/blob/3.3/README.md for more information.

composer require lcobucci/jwt

Step 3:

Please insert your Adflex login details in the system configuration under Sales -> Payment Methods -> Adflex.

Please enable level 3 payments IF your account is setup to process these, please contact Adflex Support if you are unsure about this before enabling.

Use the test environment first, and test multiple transactions using various product types. After this, please move to production mode.

Further information:

Base Currency:

You can set which currency is used via the extension Base Currency setting, this setting allows you to define which currency the transaction should be processed in:

- Base Currency (your stores admin store currency)
- Store Currency (your store view currency setting)
- Currency Dropdown (this will take the currency dropdown value as the currency to process the transaction in).

Please note that this defaults to your base currency, if you have a Magento 2 webstore implementing seperate webstores for different locales in a multicurrency environment, you must set this setting per store view.

You must also have the currencies you wish to trade in, configured and setup with Adflex. Please contact Adflex support if you are unsure.

Paypage display types:

Inline:

Displays within the Magento checkout.

Lightbox:

Display the payment page within a modal window. Please note, that your store logo is displayed within the
modal window, and the extension accesses this from (please ensure this is configured for your logo to
correctly display):

Design -> Configuration -> <your store> -> Header -> Logo Image

Versions tested:

2.3.5p1
2.2.10
2.1.18

Magento 2.0.x

The extension may support this Magento version as it uses a Magento standard implementation. However, it has not been tested and we strongly encourage our customers to upgrade to the latest version of Magento 2 due to significant security concerns with this version.
This also applies to 2.1.x and 2.2.x, as these are no longer actively supported by Magento and are EOL (End of Life). Magento 2's upgrade paths are significantly less complex and timeframes as a result, are dramatically shorter compared to version 1.x.
Please contact Adflex support if you need assistance upgrading your Magento 2 version.
