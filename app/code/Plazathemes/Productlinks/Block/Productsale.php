<?php

namespace Plazathemes\Productlinks\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class Productsale extends \Magento\Catalog\Block\Product\ListProduct {

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
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    
    /**
     * Initialize
     *
     * @param CategoryRepositoryInterface $categoryRepository,
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
    public function getProducts() {
        $limit = $this->getProductLimit();   if($limit <1) $limit = 8;                    
        $category_id = $this->getData("category_id");
        $this->_collection->clear()->getSelect()->reset(\Magento\Framework\DB\Select::WHERE)->reset(\Magento\Framework\DB\Select::ORDER)->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)->reset(\Magento\Framework\DB\Select::GROUP);
        if(!$category_id) {
            $category_id = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryRepository->get($category_id);
        if(isset($category) && $category) {
            $collection = $this->_collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addUrlRewrite()
                ->addAttributeToFilter('is_saleable', 1, 'left')
                ->addCategoryFilter($category)
                ->addPriceDataFieldFilter('%s < %s', ['final_price', 'price']);
        } else {
            $collection = $this->_collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
                ->addUrlRewrite()
                ->addAttributeToFilter('is_saleable', 1, 'left')
                ->addPriceDataFieldFilter('%s < %s', ['final_price', 'price']);
        }
        
        $collection->getSelect()
                ->order('created_at','desc')
                ->limit($limit);
                
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
		return $this->_scopeConfig->getValue('productlinks/sale', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
        return $this->_config["title"];
    }

}
