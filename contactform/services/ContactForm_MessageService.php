<?php
namespace Craft;

/**
 * Contact Form service
 */
class ContactForm_MessageService extends BaseApplicationComponent {

	private $isValid = true;
	private $fakeIt = false;

	public function sendMessage(ContactForm_MessageModel $message) {
		$settings = craft()->plugins->getPlugin('contactform')->getSettings();

		if (!$settings->toEmail) {
			throw new Exception('The "To Email" address is not set on the plugin’s settings page.');
		}
		if (!$settings->fromEmail) {
			throw new Exception('The "From Email" address is not set on the plugin’s settings page.');
		}

		$this->validateMessage($message);

		if ($this->isValid) {
			if (!$this->fakeIt) {
				// Grab any "to" emails set in the plugin settings.
				$toEmails = ArrayHelper::stringToArray($settings->toEmail);

				foreach ($toEmails as $toEmail) {
					$email = new EmailModel();
					$emailSettings = craft()->email->getSettings();

					$email->fromEmail = $settings->fromEmail;
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

	public function saveMessage(ContactForm_MessageModel $message){
		$record = new ContactForm_MessageRecord();
		if(!empty($message->name)){
			$record->setAttribute('name', $message->name);
		}
		$record->setAttribute('email', $message->email);
		$record->setAttribute('formId', $message->formId);
		$record->setAttribute('message', $message->message);

		if(isset($message->attachment)){
			$currentAttachments = "";
			$len = count($message->attachment);
			for($i=0; $i < $len; $i++){
				$currentAttachments .= $message->attachment[$i]->getName();
				if($i !== ($len - 1)){
					$currentAttachments .= ", ";
				}
			}
			$record->setAttribute('attachment', $currentAttachments);
		}
		$record->validate();
        $message->addErrors($record->getErrors());
        // Save message
        if (! $message->hasErrors()) {
            // Save in database
            return $record->save();
        }
        return false;
	}

	public function getEntries(){
		$entryRecords = ContactForm_MessageRecord::model()->ordered()->findAll();
		$entries = ContactForm_MessageModel::populateModels($entryRecords);
		return $entries;
	}

	public function getEntryById($entryId = null){
		if($entryId == null){
			return false;
		}
		$entryRecord = ContactForm_MessageRecord::model()->findByPk($entryId);
		$entry = ContactForm_MessageModel::populateModel($entryRecord);
		return $entry;
	}

	public function getEntriesByFormId($formId = null){
		if($formId == null){
			return false;
		}
		$entryRecords = ContactForm_MessageRecord::model()->ordered()->findAllByAttributes(['formId' => $formId]);
		$entries = ContactForm_MessageModel::populateModels($entryRecords);
		return $entries;
	}

	private function validateMessage(ContactForm_MessageModel $message){
	}
}
