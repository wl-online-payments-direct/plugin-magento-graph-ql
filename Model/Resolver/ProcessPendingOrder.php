<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Worldline\PaymentCore\Api\PendingOrderManagerInterface;
use Worldline\PaymentCore\Api\QuoteResourceInterface;

class ProcessPendingOrder implements ResolverInterface
{
    /**
     * @var PendingOrderManagerInterface
     */
    private $pendingOrderManager;

    /**
     * @var QuoteResourceInterface
     */
    private $quoteResource;

    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    public function __construct(
        PendingOrderManagerInterface $pendingOrderManager,
        QuoteResourceInterface $quoteResource,
        QuoteIdMaskFactory $quoteIdMaskFactory
    ) {
        $this->pendingOrderManager = $pendingOrderManager;
        $this->quoteResource = $quoteResource;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return bool
     * @throws GraphQlInputException
     * @throws GraphQlAuthorizationException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): bool
    {
        $incrementId = (string)($args['incrementId'] ?? '');
        if ($incrementId === '') {
            throw new GraphQlInputException(__('No pending order for this increment id.'));
        }

        $quote = $this->quoteResource->getQuoteByReservedOrderId($incrementId);
        if ($quote === null || !$quote->getId()) {
            throw new GraphQlInputException(__('No pending order for this increment id.'));
        }

        $this->assertOwnership($context, $quote, $args);

        return $this->pendingOrderManager->processPendingOrder($incrementId);
    }

    /**
     * Enforce that the caller owns the quote that issued the increment id.
     *
     * Logged-in customers are bound to the authenticated user id (the cartId argument is
     * ignored); guests must present the masked cartId of the guest quote that issued the id.
     *
     * @param ContextInterface $context
     * @param CartInterface $quote
     * @param array|null $args
     * @throws GraphQlAuthorizationException
     */
    private function assertOwnership(ContextInterface $context, CartInterface $quote, ?array $args): void
    {
        $isCustomer = (bool)$context->getExtensionAttributes()->getIsCustomer();

        if ($isCustomer && (int)$quote->getCustomerId() !== (int)$context->getUserId()) {
            throw new GraphQlAuthorizationException(__('You are not authorized to process this order.'));
        }
        if ($isCustomer) {
            return;
        }

        $maskedCartId = (string)($args['cartId'] ?? ($args['cart_id'] ?? ''));
        if ($maskedCartId === '') {
            throw new GraphQlAuthorizationException(__('You are not authorized to process this order.'));
        }

        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($maskedCartId, 'masked_id');
        if ((int)$quoteIdMask->getQuoteId() !== (int)$quote->getId() || !$quote->getCustomerIsGuest()) {
            throw new GraphQlAuthorizationException(__('You are not authorized to process this order.'));
        }
    }
}
