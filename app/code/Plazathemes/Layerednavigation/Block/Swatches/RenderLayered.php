<?php
/**
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

namespace Plazathemes\LayeredNavigation\Block\Swatches;

use Magento\Eav\Model\Entity\Attribute;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory;
use Magento\Framework\View\Element\Template;
use Magento\Eav\Model\Entity\Attribute\Option;
use Plazathemes\Layerednavigation\Block\Layerednavigation;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;

/**
 * Class RenderLayered Render Swatches at Layered Navigation
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RenderLayered extends \Magento\Swatches\Block\LayeredNavigation\RenderLayered
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $_template = 'Plazathemes_Layerednavigation::product/layered/renderer.phtml';

    /**
     * @var \Plazathemes\Layerednavigation\Block\Layerednavigation
     */
    protected $_ajaxLayeredBlock;

    /**
     * @param Template\Context $context
     * @param Attribute $eavAttribute
     * @param AttributeFactory $layerAttribute
     * @param \Magento\Swatches\Helper\Data $swatchHelper
     * @param \Magento\Swatches\Helper\Media $mediaHelper
     * @param \Plazathemes\Layerednavigation\Block\Layerednavigation $ajaxLayeredBlock;
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Attribute $eavAttribute,
        AttributeFactory $layerAttribute,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Swatches\Helper\Media $mediaHelper,
        \Plazathemes\Layerednavigation\Block\Layerednavigation $ajaxLayeredBlock,
        array $data = []
    ) {
        $this->_ajaxLayeredBlock = $ajaxLayeredBlock;

        parent::__construct($context, $eavAttribute, $layerAttribute, $swatchHelper, $mediaHelper, $data);
    }

    /**
     * Check use Ajax Layered module
     *
     * @return bool
     */
    public function isEnabledAjaxLayered() {
        return $this->_ajaxLayeredBlock->isEnabledModule();
    }
}
