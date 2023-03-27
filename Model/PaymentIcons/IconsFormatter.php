<?php
declare(strict_types=1);

namespace Worldline\GraphQl\Model\PaymentIcons;

class IconsFormatter
{
    public function formatIcons(array $icons): array
    {
        $iconsDetails = [];
        foreach ($icons as $icon) {
            $iconsDetails[] = [
                IconsRetrieverInterface::ICON_TITLE => $icon['title'] ?? '',
                IconsRetrieverInterface::ICON_URL => $icon['url'] ?? '',
            ];
        }

        return $iconsDetails;
    }
}
