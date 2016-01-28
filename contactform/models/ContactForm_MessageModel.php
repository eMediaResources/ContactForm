<?php
namespace Craft;

class ContactForm_MessageModel extends BaseModel {
	protected function defineAttributes() {
		return array(
			'id' => AttributeType::Number,
			'name' => array(AttributeType::String, 'label' => 'Your Name'),
			'email' => array(AttributeType::Email,  'required' => true, 'label' => 'Your Email'),
			'message' => array(AttributeType::String, 'required' => true, 'label' => 'Message'),
			'formId' => AttributeType::Number,
			'attachment' => AttributeType::Mixed,
			'dateCreated' => AttributeType::DateTime,
		);
	}
}
