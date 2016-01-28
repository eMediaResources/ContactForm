<?php
namespace Craft;

class ContactForm_MessageRecord extends BaseRecord{
	public function getTableName(){
		return 'contactform_message';
	}

	protected function defineAttributes(){
		return array(
			'name' => AttributeType::Name,
			'email' => array(AttributeType::Email, 'required' => true),
			'message' => array(AttributeType::String, 'required' => true),
			'formId' => AttributeType::Number,
			'attachment' => AttributeType::String,
		);
	}

	public function scopes() {
        return array(
            'ordered' => array('order' => 'dateCreated desc'),
        );
    }
}
