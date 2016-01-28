<?php
namespace Craft;

/**
 * Contact Form controller
 */
class ContactForm_FormController extends BaseController {

	protected $allowAnonymous = true;

	public function actionIndex(){
        $variables['forms'] = craft()->contactForm_form->getForms();
        $this->renderTemplate('contactForm/_index', $variables);
	}

	public function actionEdit(array $variables = array()){
        if (! empty($variables['formId'])) {
            $variables['form'] = craft()->contactForm_form->getformById($variables['formId']);
            if (! $variables['form']) {
                throw new HttpException(404);
            }
        } else {
            $variables['form'] = new ContactForm_FormModel();
        }
        // Render the template
        $this->renderTemplate('contactform/editForm', $variables);
    }

    public function actionSave(){
    	$this->requirePostRequest();
    	$formId = craft()->request->getPost('formId');
    	if ($formId) {
            $form = craft()->contactForm_form->getFormById($formId);
            if (! $form) {
                throw new Exception(Craft::t('No form exists with the ID “{id}”.', array('id' => $formId)));
            }
        } else {
            $form = new ContactForm_FormModel();
        }

        $values = craft()->request->getPost();
        $form->setAttributes(array(
        		"name" => $values['name'],
    		)
    	);

    	//Save Form
    	if(craft()->contactForm_form->saveForm($form)){
    		craft()->userSession->setNotice(Craft::t('Form saved.'));
    	} else {
    		craft()->userSession->setError(Craft::t('Couldn’t save form.'));
    	}
    	$this->redirectToPostedUrl($form);
    }

    public function actionGetEntries(array $variables = array()){
    	if(! empty($variables['formId'])){
    		$variables['entries'] = craft()->contactForm_message->getEntriesByFormId($variables['formId']);
        	$this->renderTemplate('contactForm/_entries', $variables);
    	} else {
    		throw new HttpException(404);
    	}
    }
}
