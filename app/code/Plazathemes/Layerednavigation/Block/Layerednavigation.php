<?php
/**
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

namespace Plazathemes\Layerednavigation\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;


class Layerednavigation extends \Magento\Framework\View\Element\Template
{
    const XML_PATH_CONFIG_ENABLED_MODULE        = "ajaxlayerednavigation/general/enabled";
    const XML_PATH_CONFIG_ENABLED_PRICE_SLIDER  = "ajaxlayerednavigation/general/price_slider";
    const XML_PATH_CONFIG_LOADING_IMAGE         = "ajaxlayerednavigation/general/loading_image";


    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $_httpContext;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    protected $_productFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Directory\Model\Currency $currency,
        Template\Context $context,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_httpContext = $httpContext;
        $this->_productFactory = $productFactory;
        $this->_currency = $currency;
        parent::__construct($context, $data);
    }

    /**
     * Get current category
     *
     * @return Category
     */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    /**
     * Get current url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl(); // Give the current url of recently viewed page
    }

    /**
     * Get Currency Symbol
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    /**
     * Get max price of current category
     *
     * @return float
     */
    public function getCurrentCategoryMaxPrice() {
        $current_category = $this->getCurrentCategory();
        if($current_category) {
            $storeId = $this->_storeManager->getStore()->getId();
            $customerGroupId = 1;
            $websiteId = $this->_storeManager->getStore($storeId)->getWebsiteId();

            /** @var $product \Magento\Catalog\Model\Product */
            $productModel = $this->_productFactory->create();
            $productModel->setStoreId($storeId);

            $collection = $productModel->getResourceCollection()
                ->addPriceData($customerGroupId, $websiteId)
                ->addAttributeToSelect('*')
                ->addCategoryFilter($current_category)
                ->addAttributeToSort('price', 'desc');

            $product = $collection->getFirstItem();
            $max_price = $product->getFinalPrice();
            return ceil($max_price);
        } else {
            return false;
        }
    }

    /**
     * Check status of module
     *
     * @return bool
     */
    public function isEnabledModule() {
        $isEnabled = false;
        $config = $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_ENABLED_MODULE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($config == 1) $isEnabled = true;
        return $isEnabled;
    }

    /**
     * Check enable/disable Price Range Slider
     *
     * @return bool
     */
    public function isEnabledPriceSlider() {
        $isEnabled = false;
        $config = $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_ENABLED_PRICE_SLIDER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($config == 1) $isEnabled = true;
        return $isEnabled;
    }

    /**
     * Get Ajax loading image
     *
     * @return string
     */
    public function getLoadingImage() {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $loadingImgFolder = "catalog/layer/images/";
        $loadingImgValue = $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_LOADING_IMAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $loadingImgUrl = $mediaUrl . $loadingImgFolder .$loadingImgValue;
        return $loadingImgUrl;
    }
}
