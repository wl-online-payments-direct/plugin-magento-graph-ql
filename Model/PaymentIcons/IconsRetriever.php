<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\PaymentIcons;

class IconsRetriever
{
    /**
     * @var IconsPool
     */
    private $iconsPool;

    public function __construct(IconsPool $iconsPool)
    {
        $this->iconsPool = $iconsPool;
    }

    /**
     * @param string $code
     * @param string $originalCode
     * @param int $storeId
     * @return array|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getIcons(string $code, string $originalCode, int $storeId): ?array
    {
        $retriever = $this->iconsPool->getIconsRetriever($code);
        if (!$retriever) {
            return null;
        }

        return $retriever->getIcons($code, $originalCode, $storeId);
    }
}
