<?php
namespace Craft;

class ContactForm_FormRecord extends BaseRecord{
	public function getTableName(){
		return 'contactform_form';
	}

	protected function defineAttributes(){
		return array(
			'name' => AttributeType::Name,
		);
	}

	public function defineRelations(){
		return array(
			'entries' => array(static::HAS_MANY, 'ContactForm_MessageRecord', 'formId'),
			'entryCount' => array(self::STAT, 'ContactForm_MessageRecord', 'formId'),
		);
	}
}
