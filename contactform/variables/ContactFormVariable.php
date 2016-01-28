<?php
namespace Craft;

class ContactFormVariable {

	public function getName(){
        $plugin = craft()->plugins->getPlugin('contactForm');
        return $plugin->getName();
    }
}
