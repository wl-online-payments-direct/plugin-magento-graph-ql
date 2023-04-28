<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver\CreditCard;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Worldline\CreditCard\Ui\ConfigProvider\CreateHostedTokenizationResponseProcessor;

/**
 * Resolver to pull URL for iFrame
 */
class WorldlineConfig implements ResolverInterface
{
    /**
     * @var CreateHostedTokenizationResponseProcessor
     */
    private $createHostedTokenizationResponseProcessor;

    public function __construct(
        CreateHostedTokenizationResponseProcessor $createHostedTokenizationResponseProcessor
    ) {
        $this->createHostedTokenizationResponseProcessor = $createHostedTokenizationResponseProcessor;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return string[]
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $createHostedTokenizationResponse = $this->createHostedTokenizationResponseProcessor->buildAndProcess($storeId);

        return [
            'url' => 'https://payment.' . $createHostedTokenizationResponse->getPartialRedirectUrl()
        ];
    }
}
