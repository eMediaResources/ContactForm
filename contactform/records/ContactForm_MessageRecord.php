<?php
namespace Craft;

class ContactForm_MessageRecord extends BaseRecord{
	public function getTableName(){
		return 'contactform_message';
	}

	protected function defineAttributes(){
		return array(
			'name' => AttributeType::Name,
			'email' => AttributeType::Email,
			'message' => AttributeType::String,
			'formId' => AttributeType::Number,
		);
	}

	public function scopes() {
        return array(
            'ordered' => array('order' => 'dateCreated desc'),
        );
    }
}
