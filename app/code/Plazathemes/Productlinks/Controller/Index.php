<?php 
/**
* Copyright © 2015 PlazaThemes.com. All rights reserved.

* @author PlazaThemes Team <contact@plazathemes.com>
*/

namespace Plazathemes\Productlinks\Controller;
use Magento\Framework\App\Action\Context;

abstract class Index extends \Magento\Framework\App\Action\Action 
{

	/**
	 * A factory that knows how to create a "page" result
	 * Requires an instance of controller action in order to impose page type,
	 * which is by convention is determined from the controller action class
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	 
	/**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
    }
}
