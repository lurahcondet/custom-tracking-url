<?php

/**
 * Xtendable_CustomTrackingUrl
 *
 * @see README.md
 *
 */

namespace Xtendable\CustomTrackingUrl\Plugin;

use Magento\Framework\Registry;
use Magento\Sales\Api\Data\ShipmentTrackCreationInterfaceFactory;
use Magento\Sales\Api\Data\ShipmentTrackCreationExtensionInterfaceFactory;
use Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader as AdminShipmentLoader;
use Magento\Framework\Exception\LocalizedException;

/**
 * Plugin of Loader for shipment
 *
 */
class ShipmentLoader
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var ShipmentTrackCreationInterfaceFactory
     */
    protected $trackFactory;

    /**
     * @var ShipmentTrackCreationExtensionInterfaceFactory
     */
    protected $trackCreationExtension;

    /**
     * @param Registry $registry
     * @param ShipmentTrackCreationInterfaceFactory $trackFactory
     * @param ShipmentTrackCreationExtensionInterfaceFactory $trackCreationExtension
     */
    public function __construct(
        Registry $registry,
        ShipmentTrackCreationInterfaceFactory $trackFactory,
        ShipmentTrackCreationExtensionInterfaceFactory $trackCreationExtension
    ) {
        $this->registry = $registry;
        $this->trackFactory = $trackFactory;
        $this->trackCreationExtension = $trackCreationExtension;
    }

    /**
     * befor load
     * @param  AdminShipmentLoader $subject
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeLoad(AdminShipmentLoader $subject)
    {
        if ($this->registry->registry('custom_tracking_url')) {
            $this->registry->unregister('custom_tracking_url');
        }
        $trackCreation = $this->getTrackingArray($subject->getTracking());
        $this->registry->register('custom_tracking_url', $trackCreation);
        return [];
    }

    /**
     * after load
     * @param  AdminShipmentLoader $subject
     * @param  mixed $result
     * @return mixed
     */
    public function afterLoad(AdminShipmentLoader $subject, $result)
    {
        if ($this->registry->registry('custom_tracking_url')) {
            $this->registry->unregister('custom_tracking_url');
        }

        return $result;
    }

    /**
     * get track data
     *
     * @return bool|ShipmentTrackCreationInterface[]
     */
    protected function getTrackingArray($tracks)
    {
        $tracks = $tracks ?: [];
        $trackingCreation = [];
        foreach ($tracks as $track) {
            if (!isset($track['number']) || !isset($track['title']) || !isset($track['carrier_code'])) {
                return false;
            }
            $this->validateUrl($track['url']);
            /** @var ShipmentTrackCreationInterface $trackCreation */
            $trackCreation = $this->trackFactory->create();
            $trackCreation->setTrackNumber($track['number']);
            $trackCreation->setTitle($track['title']);
            $trackCreation->setCarrierCode($track['carrier_code']);
            $ext = $trackCreation->getExtensionAttributes();
            if (!$ext) {
                $ext = $this->trackCreationExtension->create();
            }
            $ext->setUrl(isset($track['url']) ? $track['url']:null);
            $trackCreation->setExtensionAttributes($ext);
            $trackingCreation[] = $trackCreation;
        }

        return $trackingCreation;
    }

    /**
     * validate url
     * @param  string $url
     * @return void
     * @throws LocalizedException
     */
    protected function validateUrl($url)
    {
        if (!empty($url) && ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new LocalizedException(
                __('Tracking url must have a valid url. Use http or https.')
            );
        }
    }
}
