<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Payment;

use Magento\Quote\Api\Data\CartInterface;
use Magento\QuoteGraphQl\Model\Cart\Payment\AdditionalDataProviderPool;

class AdditionalInformationManager
{
    /**
     * @var AdditionalDataProviderPool
     */
    private $dataProviderPool;

    public function __construct(AdditionalDataProviderPool $dataProviderPool)
    {
        $this->dataProviderPool = $dataProviderPool;
    }

    public function setAdditionalInformation(CartInterface $quote, string $code): void
    {
        $payment = $quote->getPayment();
        $paymentPubHash = $payment->getAdditionalInformation('public_hash');
        if (!$paymentPubHash && $quote->getCustomerId()) {
            $paymentData = $this->dataProviderPool->getData($code, ['code' => $code]);
            $additionalInfo = array_merge((array)$payment->getAdditionalInformation(), $paymentData);
            $payment->setAdditionalInformation($additionalInfo);
        }
    }
}
