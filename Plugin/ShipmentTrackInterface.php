<?php

/**
 * Xtendable_CustomTrackingUrl
 *
 * @see README.md
 *
 */

namespace Xtendable\CustomTrackingUrl\Plugin;

use Magento\Sales\Api\Data\ShipmentTrackInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;

/**
 * Plugin of ShipmentTrackInterfaceFactory
 */
class ShipmentTrackInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param ShipmentTrackInterfaceFactory $subject
     * @return array
     */
    public function afterCreate(
        ShipmentTrackInterfaceFactory $subject,
        $result,
        $data = []
    ) {
        if ($this->request->getParam('custom_tracking_url')) {
            $this->validateUrl($this->request->getParam('custom_tracking_url'));
            $result->setUrl($this->request->getParam('custom_tracking_url'));
        }
        return $result;
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
