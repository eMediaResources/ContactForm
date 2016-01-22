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
}
