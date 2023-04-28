<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver\CreditCard;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Worldline\CreditCard\Api\CalculateSurchargeManagementInterface;
use Worldline\CreditCard\Ui\ConfigProvider;

class CalculateSurcharge implements ResolverInterface
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var CalculateSurchargeManagementInterface
     */
    private $calculateSurchargeManagement;

    public function __construct(
        GetCartForUser $getCartForUser,
        PriceCurrencyInterface $priceCurrency,
        CalculateSurchargeManagementInterface $calculateSurchargeManagement
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->priceCurrency = $priceCurrency;
        $this->calculateSurchargeManagement = $calculateSurchargeManagement;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlNoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $maskedCartId = $args['input']['cart_id'];
        if (!$maskedCartId) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing.'));
        }

        $hostedTokenizationId = $args['input']['hosted_tokenization_id'];
        if (!$hostedTokenizationId) {
            throw new GraphQlInputException(__('Required parameter "hosted_tokenization_id" is missing.'));
        }

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);
        $cart->getPayment()->setMethod(ConfigProvider::CODE);

        $surcharging = $this->calculateSurchargeManagement->calculate((int)$cart->getId(), $hostedTokenizationId);

        return [
            'surcharging' => $this->priceCurrency->convertAndFormat($surcharging, false),
            'note' => __('Please note that a surcharge may be added to the amount you have to pay ' .
                'depending on the payment method you have chosen.')
        ];
    }
}
