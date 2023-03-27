<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\RedirectPayment;

use Worldline\GraphQl\Model\PaymentIcons\IconsRetrieverInterface;
use Worldline\PaymentCore\Api\Ui\PaymentIconsProviderInterface;

class IconsRetriever implements IconsRetrieverInterface
{
    /**
     * @var PaymentIconsProviderInterface
     */
    private $iconProvider;

    public function __construct(PaymentIconsProviderInterface $iconProvider)
    {
        $this->iconProvider = $iconProvider;
    }

    /**
     * @param string $code
     * @param string $originalCode
     * @param int $storeId
     * @return array[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getIcons(string $code, string $originalCode, int $storeId): array
    {
        $offset = strrpos($originalCode, '_') + 1;
        $payProductId = (int)substr($originalCode, $offset);

        $icon = $this->iconProvider->getIconById($payProductId, $storeId);

        return [
            [
                IconsRetrieverInterface::ICON_TITLE => $icon['title'] ?? '',
                IconsRetrieverInterface::ICON_URL => $icon['url'] ?? '',
            ]
        ];
    }
}
