<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\Resolver\HostedCheckout;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Worldline\GraphQl\Model\Payment\AdditionalInformationManager;
use Worldline\HostedCheckout\Api\RedirectManagementInterface;

class RequestRedirect implements ResolverInterface
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var RedirectManagementInterface
     */
    private $redirectManagement;

    /**
     * @var AdditionalInformationManager
     */
    private $additionalInformationManager;

    public function __construct(
        GetCartForUser $getCartForUser,
        RedirectManagementInterface $redirectManagement,
        AdditionalInformationManager $additionalInformationManager
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->redirectManagement = $redirectManagement;
        $this->additionalInformationManager = $additionalInformationManager;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws \Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): array
    {
        $maskedCartId = $args['input']['cart_id'];
        if (!$maskedCartId) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing.'));
        }

        $code = $args['input']['payment_method']['code'];
        if (!($code)) {
            throw new GraphQlInputException(__('Required parameter "code" for "payment_method" is missing.'));
        }

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);

        $this->additionalInformationManager->setAdditionalInformation($cart, $code);

        $redirectUrl = $this->redirectManagement->createRequest((int)$cart->getId(), $cart->getPayment());

        return [
            'redirect_url' => $redirectUrl
        ];
    }
}
