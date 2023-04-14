<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\Data\CartInterface;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Worldline\CreditCard\Ui\ConfigProvider;
use Worldline\PaymentCore\Api\QuoteTotalInterface;
use Worldline\PaymentCore\Api\SurchargingQuoteRepositoryInterface;

class UpdateSurchargeInformation implements ResolverInterface
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var QuoteTotalInterface
     */
    private $quoteTotal;

    /**
     * @var SurchargingQuoteRepositoryInterface
     */
    private $surchargingQuoteRepository;

    public function __construct(
        GetCartForUser $getCartForUser,
        QuoteTotalInterface $quoteTotal,
        SurchargingQuoteRepositoryInterface $surchargingQuoteRepository
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->quoteTotal = $quoteTotal;
        $this->surchargingQuoteRepository = $surchargingQuoteRepository;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlInputException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $maskedCartId = $args['input']['cart_id'];
        if (!$maskedCartId) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing.'));
        }

        $paymentMethod = $args['input']['selected_payment_method'];
        if (!$paymentMethod) {
            throw new GraphQlInputException(__('Required parameter "selected_payment_method" is missing.'));
        }

        $amount = $baseAmount = 0.0;
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);
        $cart->getPayment()->setMethod($paymentMethod);

        $paymentMethod = str_replace('_vault', '', $paymentMethod);
        if ($paymentMethod !== ConfigProvider::CODE) {
            $this->surchargingQuoteRepository->deleteByQuoteId((int)$cart->getId());
            return $this->getResultData($cart, $amount, $baseAmount);
        }

        $surchargingQuote = $this->surchargingQuoteRepository->getByQuoteId((int)$cart->getId());
        $quoteTotal = $this->quoteTotal->getTotalAmount($cart);

        if ($paymentMethod === $surchargingQuote->getPaymentMethod()
            && $quoteTotal === (float)$surchargingQuote->getQuoteTotalAmount()
        ) {
            $amount = (float)$surchargingQuote->getAmount();
            $baseAmount = (float)$surchargingQuote->getBaseAmount();
        }

        return $this->getResultData($cart, $amount, $baseAmount);
    }

    private function getResultData(CartInterface $cart, float $amount, float $baseAmount): array
    {
        return [
            'cart' => [
                'model' => $cart
            ],
            'amount' => $amount,
            'base_amount' => $baseAmount,
            'currency_code' => $cart->getQuoteCurrencyCode()
        ];
    }
}
