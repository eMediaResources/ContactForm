<?php
namespace Craft;

class ContactForm_FormModel extends BaseModel {
	protected function defineAttributes() {
		return array(
			'id' => AttributeType::Number,
			'name' => array(AttributeType::String, 'label' => 'Form Name', 'required' => true),
			'entryCount' => AttributeType::Number,
			'dateCreated' => AttributeType::DateTime,
		);
	}
}
