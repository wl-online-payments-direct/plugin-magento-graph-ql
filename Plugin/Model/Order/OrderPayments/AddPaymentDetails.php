<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Plugin\Model\Order\OrderPayments;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesGraphQl\Model\Order\OrderPayments;
use Worldline\PaymentCore\Api\Ui\PaymentIconsProviderInterface;
use Worldline\PaymentCore\Model\Transaction\PaymentInfoBuilder;

class AddPaymentDetails
{
    /**
     * @var PaymentIconsProviderInterface
     */
    private $paymentIconProvider;

    /**
     * @var PaymentInfoBuilder
     */
    private $paymentInfoBuilder;

    public function __construct(
        PaymentIconsProviderInterface $paymentIconProvider,
        PaymentInfoBuilder $paymentInfoBuilder
    ) {
        $this->paymentIconProvider = $paymentIconProvider;
        $this->paymentInfoBuilder = $paymentInfoBuilder;
    }

    /**
     * @param OrderPayments $subject
     * @param array $result
     * @param OrderInterface $orderModel
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetOrderPaymentMethod(OrderPayments $subject, array $result, OrderInterface $orderModel): array
    {
        $paymentInfo = $this->paymentInfoBuilder->build($orderModel);
        $total = $paymentInfo->getAuthorizedAmount() . ' ' . $paymentInfo->getCurrency();
        $last4Digits = $paymentInfo->getCardLastNumbers();
        $payProductId = (int)$paymentInfo->getPaymentProductId();
        $iconUrl = $this->getIconForType($payProductId, (int)$orderModel->getStoreId())['url'] ?? '';

        foreach ($result as &$payment) {
            $payment['name'] .= ' ' . $last4Digits;
            $payment['additional_data'][] = ['name' => 'Total', 'value' => $total];
            $payment['additional_data'][] = ['name' => 'Url', 'value' => $iconUrl];
        }

        return $result;
    }

    private function getIconForType(int $payProductId, int $storeId): array
    {
        return $this->paymentIconProvider->getIconById($payProductId, $storeId);
    }
}
