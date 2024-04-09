[![N|Solid](https://www.maib.md/images/logo.svg)](https://www.maib.md)

# Maib Payment Gateway Module for Opencart v. 3.x
Accept Visa / Mastercard / Apple Pay / Google Pay on your store with the Maib Payment Gateway Module for Opencart v. 3.x

Download module on [opencart.com](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=44246)

## Description
You can familiarize yourself with the integration steps and website requirements [here](https://docs.maibmerchants.md/en/integration-steps-and-requirements).

To test the integration, you will need access to a Test Project (Project ID / Project Secret / Signature Key). For this, please submit a request to the email address: ecom@maib.md.

To process real payments, you must have the e-commerce contract signed and complete at least one successful transaction using the Test Project data and test card details.

After signing the contract, you will receive access to the maibmerchants platform and be able to activate the Production Project.

## Functional
**Online payments**: Visa / Mastercard / Apple Pay / Google Pay.

**Three currencies**: MDL / USD / EUR (depending on your Project settings).

**Payment refund**: To refund the payment it is necessary to update the order status (see _refund.png_) to the selected status for _Refunded payment_ in **maib** extension settings (see _settings.png_). The payment amount will be returned to the customer's card.

## Requirements
- Registration on the maibmerchants.md
- Opencart v. 3.x
- _curl_ and _json_ extensions enabled

## Installation
1. Download the extension file from Github or Opencart repository (_maib3.ocmod.zip_).
2. In the opencart admin panel, go to _Extensions > Extension Installer_.
3. Click the _Upload_ button and select the extension file. Once the upload is complete, OpenCart will begin the installation process.
4. Go to _Extensions > Modifications_ and click the _Refresh_ button.
5. Go to _Extensions > Extensions_ and choose the _Payments_ extensions type. You should see **maib** extension in the list.
6. Click the _Install_ button.
7. Click the _Edit_ button for extension settings.

## Settings
1. Title - title of the payment method displayed to the customer on the checkout page.
2. Status - enable/disable extension.
3. Debug - enable/disable extension logs in _maib.log_ file.
4. Sort Order - payment method sort order on the checkout page.
5. Geo Zone - select the geographic regions to which the payment method will apply.
6. Project ID - Project ID from maibmerchants.md
7. Project Secret - Project Secret from maibmerchants.md. It is available after project activation.
8. Signature Key - Signature Key for validating notifications on Callback URL. It is available after project activation.
9. Ok URL / Fail URL / Callback URL - add links in the respective fields of the Project settings in maibmerchants.
10. Order status settings: Pending payment - Order status when payment is in pending.
11. Order status settings: Completed payment - Order status when payment is successfully completed.
12. Order status settings: Failed payment - Order status when payment failed.
13. Order status settings: Refunded payment - Order status when payment is refunded. For payment refund, update the order status to the this selected status (see _refund.png_).

## Troubleshooting
Enable debug mode in the plugin settings and access the log file.

If you require further assistance, please don't hesitate to contact the **maib** ecommerce support team by sending an email to ecom@maib.md. 

In your email, make sure to include the following information:
- Merchant name
- Project ID
- Date and time of the transaction with errors
- Errors from log file
