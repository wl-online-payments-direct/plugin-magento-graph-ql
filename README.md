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
