<?php

/**
 * Xtendable_CustomTrackingUrl
 *
 * @see README.md
 *
 */

namespace Xtendable\CustomTrackingUrl\Plugin;

use Magento\Framework\Registry;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\ShipmentDocumentFactory;
use Magento\Sales\Api\Data\ShipmentCommentCreationInterface;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface;

/**
 * Plugin of ShipmentDocumentFactory
 */
class ShipmentDocument
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param ShipmentDocumentFactory $subject
     * @param OrderInterface $order
     * @param ShipmentItemCreationInterface[] $items
     * @param ShipmentTrackCreationInterface[] $tracks
     * @param ShipmentCommentCreationInterface|null $comment
     * @param bool $appendComment
     * @param ShipmentPackageCreationInterface[] $packages
     * @param ShipmentCreationArgumentsInterface|null $arguments
     * @return array
     */
    public function beforeCreate(
        ShipmentDocumentFactory $subject,
        OrderInterface $order,
        array $items = [],
        array $tracks = [],
        ShipmentCommentCreationInterface $comment = null,
        $appendComment = false,
        array $packages = [],
        ShipmentCreationArgumentsInterface $arguments = null
    ) {
        if ($this->registry->registry('custom_tracking_url')) {
            $tracks = $this->registry->registry('custom_tracking_url');
            $this->registry->unregister('custom_tracking_url');
        }

        return [$order, $items, $tracks, $comment, $appendComment, $packages, $arguments];
    }
}
