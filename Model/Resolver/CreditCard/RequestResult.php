<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver\CreditCard;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Worldline\CreditCard\Model\ReturnRequestProcessor;
use Worldline\PaymentCore\Model\OrderState\OrderState;

class RequestResult implements ResolverInterface
{
    /**
     * @var ReturnRequestProcessor
     */
    private $returnRequestProcessor;

    public function __construct(ReturnRequestProcessor $returnRequestProcessor)
    {
        $this->returnRequestProcessor = $returnRequestProcessor;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): array
    {
        $hostedTokenizationId = $args['paymentId'] ?? null;
        if (!$hostedTokenizationId) {
            return [];
        }

        try {
            $orderState = $this->returnRequestProcessor->processRequest(null, $hostedTokenizationId);
            if (!$orderState) {
                return [];
            }

            if ($orderState->getState() === ReturnRequestProcessor::WAITING_STATE) {
                $result['result'] = ReturnRequestProcessor::WAITING_STATE;
                $result['methodCode'] = $orderState->getPaymentMethod();
                $result['orderIncrementId'] = $orderState->getIncrementId();
                $result['paymentProductId'] = $orderState->getPaymentProductId();

                return $result;
            }

            return [
                'result' => ReturnRequestProcessor::SUCCESS_STATE,
                'methodCode' => $orderState->getPaymentMethod(),
                'orderIncrementId' => $orderState->getIncrementId(),
                'paymentProductId' => $orderState->getPaymentProductId()
            ];
        } catch (LocalizedException $e) {
            return [
                'result' => ReturnRequestProcessor::FAIL_STATE,
                'methodCode' => '',
                'orderIncrementId' => '',
                'paymentProductId' => null
            ];
        }
    }
}
