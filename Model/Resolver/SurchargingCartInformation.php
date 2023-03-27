<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Worldline\PaymentCore\Api\SurchargingQuoteRepositoryInterface;

class SurchargingCartInformation implements ResolverInterface
{
    /**
     * @var SurchargingQuoteRepositoryInterface
     */
    private $surchargingQuoteRepository;

    public function __construct(SurchargingQuoteRepositoryInterface $surchargingQuoteRepository)
    {
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
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value must be specified'));
        }

        $cart = $value['model'];
        $amount = $baseAmount = 0.0;
        $surchargingQuote = $this->surchargingQuoteRepository->getByQuoteId((int)$cart->getId());

        if ($cart->getPayment()->getMethod() === $surchargingQuote->getPaymentMethod()
            && (float)$cart->getGrandTotal() === (float)$surchargingQuote->getQuoteGrandTotal()
        ) {
            $amount = (float)$surchargingQuote->getAmount();
            $baseAmount = (float)$surchargingQuote->getBaseAmount();
        }

        return [
            'amount' => $amount,
            'base_amount' => $baseAmount,
            'currency_code' => $surchargingQuote->getAmount() ? $cart->getQuoteCurrencyCode() : null
        ];
    }
}
