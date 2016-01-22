<?php
namespace Craft;

class ContactFormPlugin extends BasePlugin {

	public function getName() {
		return Craft::t('Contact Form');
	}

	public function getVersion() {
		return '1.0.1';
	}

	public function getDeveloper(){
		return 'e-Media Resources';
	}

	public function getDeveloperUrl(){
		return 'http://e-mediaresources.com';
	}

	public function hasCpSection(){
		return true;
	}

	public function getSettingsHtml(){
		return craft()->templates->render('contactform/_settings', array(
			'settings' => $this->getSettings()
		));
	}

	public function registerCpRoutes(){
		return array(
			'contactform' => array(
                'action' => 'contactForm/index'
            ),
		);
	}

	protected function defineSettings() {
		return array(
			'toEmail' => array(AttributeType::String, 'required' => true),
			'prependSender' => array(AttributeType::String, 'default' => Craft::t('On behalf of')),
			'subject' => array(AttributeType::String, 'default' => Craft::t('New message from {siteName}', array('siteName' => craft()->getSiteName()))),
			'allowAttachments' => AttributeType::Bool,
			'honeypotField' => AttributeType::String,
			'successMessage' => array(AttributeType::String, 'default' => Craft::t('Your message has been sent.'), 'required' => true),
		);
	}
}
