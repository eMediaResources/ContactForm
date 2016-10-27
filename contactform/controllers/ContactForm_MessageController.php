<?php
namespace Craft;

/**
 * Contact Form controller
 */
class ContactForm_MessageController extends BaseController {

	protected $allowAnonymous = true;

	public function actionAdd() {
		$this->requirePostRequest();

		$settings = craft()->plugins->getPlugin('contactform')->getSettings();

		$message = new ContactForm_MessageModel();
		$savedBody = false;

		$message->email = craft()->request->getPost('email');
		$message->name = craft()->request->getPost('name');
		$message->formId = craft()->request->getPost('formId');

		if ($settings->allowAttachments) {
			if (isset($_FILES['attachment']) && isset($_FILES['attachment']['name'])) {
				if (is_array($_FILES['attachment']['name'])) {
					$message->attachment = \CUploadedFile::getInstancesByName('attachment');
				} else {
					$message->attachment = array(\CUploadedFile::getInstanceByName('attachment'));
				}
			}

		}

		// Set the message body
		$postedMessage = craft()->request->getPost('message');

		if ($postedMessage) {

			$bodyHeaders = '';
			if($settings->addNameEmailBody){
				$bodyHeaders .= "Name: ".$message->name;
				$bodyHeaders .= "\n\nEmail: ".$message->email."\n\n";
			}

			if (is_array($postedMessage)) {
				$savedBody = false;

				if (isset($postedMessage['body'])) {
					// Save the message body in case we need to reassign it in the event there's a validation error
					$savedBody = $postedMessage['body'];
				}

				// If it's false, then there was no messages[body] input submitted.  If it's '', then validation needs to fail.
				if ($savedBody === false || $savedBody !== '') {
					// Compile the message from each of the individual values
					$compiledMessage = '';

					foreach ($postedMessage as $key => $value) {
						if ($key != 'body') {
							if ($compiledMessage) {
								$compiledMessage .= "\n\n";
							}

							$compiledMessage .= $key.': ';

							if (is_array($value)) {
								$compiledMessage .= implode(', ', $value);
							} else {
								$compiledMessage .= $value;
							}
						}
					}

					if (!empty($postedMessage['body'])) {
						if ($compiledMessage) {
							$compiledMessage .= "\n\n";
						}

						$compiledMessage .= $postedMessage['body'];
					}

					$message->message = $bodyHeaders.$compiledMessage;
				}
			} else {
				$message->message = $bodyHeaders.$postedMessage;
			}
		}

		if ($message->validate()) {
			// Only actually save to the db if the honeypot test was valid
			if ($this->validateHoneypot($settings->honeypotField)) {
				craft()->contactForm_message->saveMessage($message);
			}
			// Only actually send email if the honeypot test was valid, but show success regardless
			if (!$this->validateHoneypot($settings->honeypotField) || craft()->contactForm_message->sendMessage($message)) {
				if (craft()->request->isAjaxRequest()) {
					$this->returnJson(array('success' => true, 'message' => $settings->successMessage));
				} else {
					$successRedirectUrl = craft()->request->getPost('successRedirectUrl');

					if ($successRedirectUrl) {
						$_POST['redirect'] = $successRedirectUrl;
					}

					craft()->userSession->setNotice($settings->successMessage);
					$this->redirectToPostedUrl($message);
				}
			}
		}

		// Something has gone horribly wrong.
		if (craft()->request->isAjaxRequest()) {
			return $this->returnErrorJson($message->getErrors());
		} else {
			craft()->userSession->setError('There was a problem with your submission, please check the form and try again!');

			if ($savedBody !== false) {
				$message->message = $savedBody;
			}

			craft()->urlManager->setRouteVariables(array(
				'message' => $message,
				'errors' => $message->getErrors(),
			));
		}
	}

	public function actionGetEntry(array $variables = array()){
		if(! empty($variables['entryId'])){
    		$variables['entry'] = craft()->contactForm_message->getEntryById($variables['entryId']);
        	$this->renderTemplate('contactForm/_entry', $variables);
    	} else {
    		throw new HttpException(404);
    	}
	}

	protected function validateHoneypot($fieldName) {
		if (!$fieldName) {
			return true;
		}

		$honey = craft()->request->getPost($fieldName);
		return $honey == '';
	}
}
