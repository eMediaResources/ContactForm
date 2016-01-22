<?php
namespace Craft;

class ContactFormModel extends BaseModel {
	protected function defineAttributes() {
		return array(
			'id' => AttributeType::Number,
			'name' => array(AttributeType::String, 'label' => 'Your Name'),
			'email' => array(AttributeType::Email,  'required' => true, 'label' => 'Your Email'),
			'message' => array(AttributeType::String, 'required' => true, 'label' => 'Message'),
			'attachment' => AttributeType::Mixed,
			'dateCreated' => AttributeType::DateTime,
		);
	}
}
