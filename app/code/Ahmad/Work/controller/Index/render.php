<?php
namespace Mageplaza\Helloworld\Controller\Index;

class render extends \Magento\Framework\App\Action\Action
{
	public function __construct(
	\Magento\Framework\App\Action\Context $context)
  {
    return parent::__construct($context);
  }
  
	public function execute()
	{
		echo 'Wellcome to Render';
		exit;
		
	}
	
	
}

?>

