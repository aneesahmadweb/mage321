<?php
namespace Packt\HelloWorld\Block;
   use Magento\Framework\View\Element\Template;
    class Lastpage extends Template
    {
		
	  public function getLastUrl()
    {
          return $this->getUrl('hello');
    }	
		
		
	}
		
		
		
		
		
		
		?>