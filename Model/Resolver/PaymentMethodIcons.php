<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Worldline\GraphQl\Model\PaymentIcons\IconsRetriever;

class PaymentMethodIcons implements ResolverInterface
{
    /**
     * @var IconsRetriever
     */
    private $iconsRetriever;

    public function __construct(IconsRetriever $iconsRetriever)
    {
        $this->iconsRetriever = $iconsRetriever;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|null
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): ?array
    {
        if (!isset($value['code'])) {
            throw new LocalizedException(__('"code" value should be specified'));
        }

        $code = preg_replace('/[0-9]+/', '', $value['code']);
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();

        return $this->iconsRetriever->getIcons($code, $value['code'], $storeId) ?? [];
    }
}
