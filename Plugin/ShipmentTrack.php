<?php

/**
 * Xtendable_CustomTrackingUrl
 *
 * @see README.md
 *
 */

namespace Xtendable\CustomTrackingUrl\Plugin;

use Magento\Sales\Model\Order\Shipment\TrackFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;

/**
 * Plugin of TrackFactory
 */
class ShipmentTrack
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param TrackFactory $subject
     * @return array
     */
    public function afterCreate(
        TrackFactory $subject,
        $result,
        $data
    ) {
        if (isset($data["data"]['extension_attributes']['url'])) {
            $result->setUrl($data["data"]['extension_attributes']['url']);
        }

        return $result;
    }
}
