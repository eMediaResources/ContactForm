<?php
namespace Craft;

class ContactForm_FormRecord extends BaseRecord{
	public function getTableName(){
		return 'contactform_form';
	}

	public function defineRelations(){
		return array(
			'entries' => array(static::HAS_MANY, 'ContactForm_MessageRecord', 'formId'),
		);
	}

	protected function defineAttributes(){
		return array(
			'name' => AttributeType::Name,
		);
	}
}
