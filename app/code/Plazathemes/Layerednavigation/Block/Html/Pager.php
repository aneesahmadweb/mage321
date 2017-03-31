<?php
/**
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

namespace Plazathemes\LayeredNavigation\Block\Html;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
/**
 * Html pager block
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Plazathemes_Layerednavigation::html/pager.phtml';

    /**
     * @var \Plazathemes\Layerednavigation\Block\Layerednavigation
     */
    protected $_ajaxLayeredBlock;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Plazathemes\Layerednavigation\Block\Layerednavigation $ajaxLayeredBlock
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Plazathemes\Layerednavigation\Block\Layerednavigation $ajaxLayeredBlock,
        array $data = []
    ) {
        $this->_ajaxLayeredBlock = $ajaxLayeredBlock;

        parent::__construct($context, $data);
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