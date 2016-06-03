<?php
namespace Craft;

class ContactFormPlugin extends BasePlugin {

	public function getName() {
		return Craft::t('Contact Form');
	}

	public function getVersion() {
		return '2.0.2';
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
                'action' => 'contactForm/form/index'
            ),
            'contactform/form/new' => array(
                'action' => 'contactForm/form/edit'
            ),
            'contactform/form/(?P<formId>\d+)/edit' => array(
                'action' => 'contactForm/form/edit'
            ),
            'contactform/form/(?P<formId>\d+)/entries' => array(
                'action' => 'contactForm/form/getEntries'
            ),
            'contactform/entry/(?P<entryId>\d+)' => array(
                'action' => 'contactForm/message/getEntry'
            ),
		);
	}

	public function onAfterInstall(){
		craft()->contactForm_form->run();
		craft()->request->redirect(UrlHelper::getCpUrl('settings/plugins/contactform'));
	}

	protected function defineSettings() {
		return array(
			'toEmail' => array(AttributeType::String, 'required' => true),
			'fromEmail' => array(AttributeType::String,
				'required' => true,
				'default' => Craft::t('no-reply@{domain}', array('domain' => craft()->request->getServerName()))
			),
			'prependSender' => array(AttributeType::String, 'default' => Craft::t('On behalf of')),
			'subject' => array(AttributeType::String, 'default' => Craft::t('New message from {siteName}', array('siteName' => craft()->getSiteName()))),
			'allowAttachments' => AttributeType::Bool,
			'honeypotField' => AttributeType::String,
			'successMessage' => array(AttributeType::String, 'default' => Craft::t('Your message has been sent.'), 'required' => true),
			'addNameEmailBody' => AttributeType::Bool,
		);
	}
}
