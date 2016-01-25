<?php
namespace Craft;

/**
 * Contact Form service
 */
class ContactFormService extends BaseApplicationComponent {

	private $isValid = true;
	private $fakeIt = false;

	public function sendMessage(ContactFormModel $message) {
		$settings = craft()->plugins->getPlugin('contactform')->getSettings();

		if (!$settings->toEmail) {
			throw new Exception('The "To Email" address is not set on the plugin’s settings page.');
		}

		$this->validateMessage($message);

		if ($this->isValid) {
			if (!$this->fakeIt) {
				// Grab any "to" emails set in the plugin settings.
				$toEmails = ArrayHelper::stringToArray($settings->toEmail);

				foreach ($toEmails as $toEmail) {
					$email = new EmailModel();
					$emailSettings = craft()->email->getSettings();

					$email->fromEmail = $emailSettings['emailAddress'];
					$email->replyTo = $message->email;
					$email->sender = $emailSettings['emailAddress'];
					$email->fromName = $settings->prependSender . ($settings->prependSender && $message->name ? ' ' : '') . $message->name;
					$email->toEmail = $toEmail;
					$email->subject = $settings->subject;
					$email->body = $message->message;

					if (!empty($message->attachment)) {
						foreach ($message->attachment as $attachment) {
							if ($attachment) {
								$email->addAttachment($attachment->getTempName(), $attachment->getName(), 'base64', $attachment->getType());
							}
						}
					}
					craft()->email->sendEmail($email);
				}
			}

			return true;
		}

		return false;
	}

	public function saveMessage(ContactFormModel $message){
		$record = new ContactForm_MessageRecord();
		if(!empty($message->name)){
			$record->setAttribute('name', $message->name);
		}
		$record->setAttribute('email', $message->email);
		$record->setAttribute('message', $message->message);
		$record->save();
	}

	public function getEntries(){
		$entryRecords = ContactForm_MessageRecord::model()->ordered()->findAll();
		$entries = ContactFormModel::populateModels($entryRecords);
		return $entries;
	}

	private function validateMessage(ContactFormModel $message){
		// echo '<pre>';
		// var_dump($message);
		// die();
	}
}
