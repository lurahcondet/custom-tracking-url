<?php

/**
 * Xtendable_CustomTrackingUrl
 *
 * @see README.md
 *
 */

namespace Xtendable\CustomTrackingUrl\Plugin;

use Magento\Shipping\Model\Order\Track;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;

/**
 * Plugin of TrackFactory
 */
class TrackInfo
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Track $subject
     * @return array
     */
    public function afterGetNumberDetail(
        Track $subject,
        $result
    ) {
        if (is_array($result)) {
            $result["url"] = $subject->getUrl();
        }

        return $result;
    }
}
