# Worldline Online Payments

## Graph QL

[![M2 Coding Standard](https://github.com/wl-online-payments-direct/plugin-magento-graph-ql/actions/workflows/coding-standard.yml/badge.svg?branch=develop)](https://github.com/wl-online-payments-direct/plugin-magento-graph-ql/actions/workflows/coding-standard.yml)
[![M2 Mess Detector](https://github.com/wl-online-payments-direct/plugin-magento-graph-ql/actions/workflows/mess-detector.yml/badge.svg?branch=develop)](https://github.com/wl-online-payments-direct/plugin-magento-hostedcheckout/actions/workflows/mess-detector.yml)

This is a Graph QL addon for worldline payment solutions.

To install this solution, you may use
[adobe commerce marketplace](https://marketplace.magento.com/worldline-module-magento-payment.html)
or install it from the GitHub.

This addon is also included into:
- [main plugin for adobe commerce](https://github.com/wl-online-payments-direct/plugin-magento)

### Change log:

### 1.32.0
- Fix: Stability for 3DS exemption capabilities

### 1.31.0
- Improved exemptions capabilities related to 3DS exemption types
- Added phone number formatter for sending API requests

### 1.30.0
- Removed Mealvouchers logo from checkout page when using "Hosted Checkout (redirect to Worldline)"

#### 1.29.0
- Fix: Do not allow usage of decimals in the object cardPaymentMethodSpecificInput.paymentProduct130SpecificInput.threeDSecure.numberOfItems
- Fix amount discrepancy issues

#### 1.28.0
- Added: Possibility to auto-include primary webhooks URL in the payload of payment request, and to configure up to 4 additional endpoints.
- Fix Worldline Block/Info.php not compatible with Magento core Payment/Block/Info.php.

#### 1.27.0
- Improved: Data mapping to flag correctly exemptions requests to 3-D Secure.

#### 1.26.0
- Add new payment method: Pledg

#### 1.25.0
- Remove MealVouchers configuration from hosted checkout
- Fix mobile payment method information not being shown in order details

#### 1.24.0
- Add allow amount discrepancy option
- Fix print invoice issue
- Update payment brand logos

#### 1.23.0
- Add quote ID in request payload
- Fix wrong IP address being sent on checkout
- Decrease maximum payment method logos
- Add compatibility with 2.4.8-p2

#### 1.22.0
- Fix issue with sending email

#### 1.21.0
- Fix wrong handling of payment specific information on order page

#### 1.20.0
- Fix comma separated email validation in notification settings

#### 1.19.0
- Fix issue with showing split payment amounts on order details page for Mealvoucher transactions
- Fix issue with showing Mealvoucher in full redirect

#### 1.18.0
- Fix logo issue for CB on checkout page
- Fix PHP >= 8.2 issue with not sending parameter by reference

#### 1.17.0
- Add Mealvoucher payment product
- Add CVCO (Cheque Vacances Connect Online) payment product

#### 1.16.0
- Add compatibility with PHP 8.4
- Update SDK version

#### 1.15.0
- Fixed order creation using Google Pay & Apple Pay

#### 1.14.0
- Update plugin translations

#### 1.13.0
- Added 3DS exemption types to the plugin

#### 1.12.0
- Fixed validation for HTML template ID configuration. It is no longer required to have extension on HTML templates.
- Fixed issue where items quantities in decimals were not taken into account.
- Improved handling of orders where the total amount does not match the sum of line items amount due to the rounding.

#### 1.11.0
- Fixed issue where FPT (Fixed Product Tax) rates were not taken into account.
- Update "wl-online-payments-direct/sdk-php" library to 5.16.1

#### 1.10.0
- Improved display of shipping costs on the payment page for Hosted Checkout and Redirect Payment.

#### 1.9.0
- Added trusted URLs to the CSP whitelist.
- Improved reliability of fallback cron job.
- Fixed credentials caching issue when simultaneously processing refunds for multiple merchant IDs.

#### 1.8.0
- Improved the order creation process by tracking multiple paymentIDs.
- Improved logging and exception handling when multiple payments are done for a single order.

#### 1.7.0
- Added new payment method "Bank Transfer by Worldline".
- Added the "Contact email" field to the feature suggestion form.
- Added compatibility with Php Sdk 5.10.0.
- Replaced legacy Alipay payment method with the new Alipay+.
- Replaced legacy WeChat Pay payment method with the new version.
- Fixed validation error when placing orders with Virtual/downloadable products.
- Fixed error when adding new shipping address on checkout.

#### 1.6.0
- Added email to customer and “Copy To” for "Auto Refund For Out Of Stock Orders" notifications.
- Added translations for French (Belgium), French (Switzerland) and Dutch (Belgium).
- Improved notifications so they are only sent once per event.
- Improved "Failed Orders Notifications" to avoid triggering on transaction status 46.
- Fixed "Redirect Payments" display issue after customer modifies shipping options.
- Fixed server error on checkout page when "Specific Currencies" are not aligned with Magento’s non-default currencies.

#### 1.5.0
- Added "Session Timeout" configuration for the hosted checkout page.
- Added "Allowed Number Of Payment Attempts" configuration for the hosted checkout page.
- Added compatibility with Php Sdk 5.8.2.
- Added refund refused notifications functionality.
- Fixed update of the credit memo status when the refund request was refused by acquirer.

#### 1.4.1
- Fixed issue with partial invoices and partial credit memos.
- Fixed transaction ID value for request to check if payment can be cancelled.

#### 1.4.0
- Added own branded gift card compatibility for Intersolve payment method.
- Added compatibility with Php Sdk 5.7.0.
- Modified plugin tab "dynamic order status synchronization" to “Settings & Notifications”.
- Fixed value determination process for "AddressIndicator" parameter.
- Fixed issues with creating orders by cron.
- Fixed issue with Magento confirmation page when using PayPal payment method.
- Fixed issue with auto refund for out-of-stock feature.
- Fixed issue when using a database prefix.

#### 1.3.0
- Added new payment method “Union Pay International".
- Added new payment method “Przelewy24".
- Added new payment method “EPS".
- Added new payment method “Twint".
- Added compatibility with Php Sdk 5.6.0.
- Added compatibility with Amasty Subscriptions & Recurring Payments extension 1.6.15.
- Improved plugin landing page "About Worldline".
- Improved Hosted Tokenization error message when transaction is declined.
- Improved concatenation of streetline1 and streetline2 for billing & shipping address.

#### 1.2.0
- Added new payment method “Giftcard Limonetik".
- Added new setting "Enable Sending Payment Refused Emails".
- Improved handling of Magento 2 display errors.
- Fixed hosted tokenization js link for production transactions.
- Fixed order creation issue on successful transactions.
- Fixed webhooks issue for rejected transactions with empty refund object.
- General code improvements.

#### 1.1.3
- Fixed issue of products with special pricing not displaying the original price in order view.
- Fixed issue with configurable product on cart restoration when user clicks the browser back button.
- Fixed issue with last payment id not fetched properly.
- Fixed issue where carts are restored incompletely.
- Fixed issue when customer attribute doesn't display in order after paying.
- Added customer address attributes validation before placing order.
- Added a setting to stop sending refusal emails.
- Added compatibility with Php Sdk 5.4.0.

#### 1.1.2
- Add support for the 5.3.0 version of PHP SDK.
- Fix connection credential caching.

#### 1.1.1
- Add support for the 5.1.0 version of PHP SDK.
- General code improvements.

#### 1.1.0
- Add support for the 5.0.0 version of PHP SDK.
- Add support for the 13.0.0 PWA version and the surcharging functionality.

#### 1.0.2
- Add fix for Adobe Commerce cloud instances.

#### 1.0.1
- Update core modules.

#### 1.0.0
- Initial version.
