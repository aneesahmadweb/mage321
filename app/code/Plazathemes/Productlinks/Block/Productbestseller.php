<?php

namespace Plazathemes\Productlinks\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
class Productbestseller extends \Magento\Catalog\Block\Product\ListProduct {

    /**
     * System configuration values
     *
     * @var array
     */
    protected $_scopeConfig;
	
	protected $connection;
	protected $resource;
    protected $productFactory;
    /**
     * Initialize
     *
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
	
	public function __construct(
			\Magento\Catalog\Block\Product\Context $context, 
            \Magento\Framework\Data\Helper\PostHelper $postDataHelper, 
            \Magento\Catalog\Model\Layer\Resolver $layerResolver, 
            CategoryRepositoryInterface $categoryRepository,
            \Magento\Framework\Url\Helper\Data $urlHelper, 
            \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
			\Magento\Catalog\Model\ProductFactory $productFactory,
			 ResourceConnection $resource,			
            array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_collection = $collection;
        $this->_scopeConfig = $context->getScopeConfig();
		$this->resource = $resource;
		$this->productFactory = $productFactory;
		$this->connection = $resource->getConnection();

        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }
    /**
     * Get product collection
     */                                              
    public function getProducts() {
				$limit = $this->getProductLimit();   if($limit <1) $limit = 8;                    
   
			    $select = $this->connection->select()
					 ->from($this->resource->getTableName('sales_order_item'), 'product_id')
					 ->order('sum(`qty_ordered`) Desc')
					 ->group('product_id')
					 ->limit($limit);
					 
				   $producIds = array(); 
				   foreach ($this->connection->query($select)->fetchAll() as $row) {
					   $producIds[] = $row['product_id'];
				   }
				  
				$products = array();
				//echo "<pre>"; print_r($producIds); die;
				foreach($producIds as $product_id) {
					$product = $this->productFactory->create()->load($product_id);
						if($product->getVisibility() == 2||$product->getVisibility() == 3||$product->getVisibility() == 4)
						$products[] = $product;	
					
				}
			if(count($products)>=1)
				return $products;
				return array();
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
		return $this->_scopeConfig->getValue('productlinks/bestseller', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
