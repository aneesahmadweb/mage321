<?php

namespace Plazathemes\Productlinks\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class Productfeatured extends \Magento\Catalog\Block\Product\ListProduct {

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_collection;

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_productCollection;

    /**
     * System configuration values
     *
     * @var array
     */
    protected $_scopeConfig;
    

    /**
     * Initialize
     *
     * @param Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
			\Magento\Catalog\Block\Product\Context $context, 
            \Magento\Framework\Data\Helper\PostHelper $postDataHelper, 
            \Magento\Catalog\Model\Layer\Resolver $layerResolver, 
            CategoryRepositoryInterface $categoryRepository,
            \Magento\Framework\Url\Helper\Data $urlHelper, 
            \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, 
            array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_collection = $collection;
        $this->_scopeConfig = $context->getScopeConfig();

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    /**
     * Get product collection
     */                                              
    protected function getProducts() {
		  $limit = $this->getProductLimit();   if($limit <1) $limit = 8;     
        $collection = $this->_collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addUrlRewrite()
                ->addAttributeToFilter('featured', 1, 'left');

        $collection->getSelect()
                ->order('rand()') 
				->limit($limit);
				

        // Set Pagination Toolbar for list page
        $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'filterproducts.grid.record.pager')->setCollection($collection);
        $this->setChild('pager', $pager); // set pager block in layout

        $this->_productCollection = $collection;
        return $this->_productCollection;
    }

    /**
     * load and return product collection
     */
    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    /**
     * Get product toolbar
     */
    public function getToolbarHtml() {
        return '';
    }

    /**
     * Get grid mode
     */
    public function getMode() {
        return 'grid';
    }

    /**
     * Get image helper
     */
    public function getImageHelper() {
        return $this->imageHelper;
    }

    /**
     * Get module configuration
     */                                
    public function getConfig()
	{
		return $this->_scopeConfig->getValue('productlinks/featured', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    /**
     * Check that module is enabled or not
     * @return int
     */
    public function getSectionStatus() {
		$config = $this->getConfig();
        return $config["enable"];
    }

    /**
     * Get the configured limit of products
     * @return int
     */
    public function getProductLimit() {
		$config = $this->getConfig();
        $limit = $this->getData("product_count");
        if(!$limit)
            $limit = $config["limit"];
        return $limit;
    }

    /**
     * Get the configured title of section
     * @return int
     */
    public function getPageTitle() {
		$config = $this->getConfig();
        return $config["title"];
    }

}
