<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Worldline\PaymentCore\Api\PendingOrderManagerInterface;

class ProcessPendingOrder implements ResolverInterface
{
    /**
     * @var PendingOrderManagerInterface
     */
    private $pendingOrderManager;

    public function __construct(PendingOrderManagerInterface $pendingOrderManager)
    {
        $this->pendingOrderManager = $pendingOrderManager;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): bool
    {
        if (empty($args['incrementId'])) {
            return false;
        }

        return $this->pendingOrderManager->processPendingOrder($args['incrementId']);
    }
}
