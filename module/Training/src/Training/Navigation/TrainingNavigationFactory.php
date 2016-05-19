<?php
namespace Training\Navigation;
use Zend\Navigation\Service\DefaultNavigationFactory;
class TrainingNavigationFactory extends DefaultNavigationFactory {
	protected function getName(){
		return 'training';
	}
}