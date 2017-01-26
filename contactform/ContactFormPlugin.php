<?php
namespace Craft;

class ContactFormPlugin extends BasePlugin {

	public function getName() {
		return Craft::t('Contact Form');
	}

	public function getVersion() {
		return '2.0.5';
	}

	public function getDeveloper(){
		return 'Wheel Interactive';
	}

	public function getDeveloperUrl(){
		return 'https://www.wheelinteractive.com';
	}

  public function getDescription() {
    return 'Plugin for multiple forms and database integration';
  }

  public function getDocumentationUrl() {
    return 'https://github.com/xpertbot/ContactForm';
  }

  public function getReleaseFeedUrl() {
    return 'https://raw.githubusercontent.com/xpertbot/ContactForm/master/releases.json';
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
			'toEmail' => array(AttributeType::String,
        'required' => true,
        'default' => craft()->systemSettings->getSetting('email', 'emailAddress'),
      ),
			'fromEmail' => array(AttributeType::String,
				'required' => true,
				'default' => craft()->systemSettings->getSetting('email', 'emailAddress')
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
