<?php
namespace Craft;

/**
 * Contact Form service
 */
class ContactForm_FormService extends BaseApplicationComponent {

    public function run(){
        $this->_createFirstForm();
    }

	public function getFormById($id = null){
		$formRecord = ContactForm_FormRecord::model()->findById($id);
        if ($formRecord) {
            return ContactForm_FormModel::populateModel($formRecord);
        }
        return null;
	}

	public function saveForm(ContactForm_FormModel $form){
		if ($form->id) {
            $formRecord = ContactForm_FormRecord::model()->findById($form->id);
            if (! $formRecord) {
                throw new Exception(Craft::t('No form exists with the ID “{id}”.', array('id' => $form->id)));
            }
        } else {
            $formRecord = new ContactForm_FormRecord();
        }
        // Set attributes
        $formRecord->setAttributes($form->getAttributes());
        // Validate
        $formRecord->validate();
        $form->addErrors($formRecord->getErrors());
        // Save form
        if (! $form->hasErrors()) {
            // Save in database
            return $formRecord->save();
        }
        return false;
	}

    public function getForms(){
        $formRecords = ContactForm_FormRecord::model()->with('entryCount')->findAll();
        $forms = ContactForm_FormModel::populateModels($formRecords);
        return $forms;
    }


    private function _createFirstForm(){
        $form = new ContactForm_FormModel();
        $form->setAttributes(array(
                "name" => "Contact Form",
            )
        );
        if($this->saveForm($form)){
            ContactFormPlugin::log('Contact Form Created', LogLevel::Info);
        } else {
            ContactFormPlugin::log('Error Creating First Form', LogLevel::Info);
        }
    }

}
