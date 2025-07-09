<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\OrderInterface;
use Worldline\PaymentCore\Api\SurchargingQuoteRepositoryInterface;

class SurchargingOrderInformation implements ResolverInterface
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
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): array
    {
        if (!(($value['model'] ?? null) instanceof OrderInterface)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $order = $value['model'];
        $amount = $baseAmount = 0.0;
        $surchargingQuote = $this->surchargingQuoteRepository->getByQuoteId((int)$order->getQuoteId());

        if ($order->getPayment()->getMethod() === $surchargingQuote->getPaymentMethod()) {
            $amount = (float)$surchargingQuote->getAmount();
            $baseAmount = (float)$surchargingQuote->getBaseAmount();
        }

        return [
            'amount' => $amount,
            'base_amount' => $baseAmount,
            'currency_code' => $surchargingQuote->getAmount() ? $order->getOrderCurrencyCode() : null
        ];
    }
}
