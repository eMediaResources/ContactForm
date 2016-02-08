<?php
namespace Craft;

class ContactForm_MessageModel extends BaseModel {
	protected function defineAttributes() {
		return array(
			'id' => AttributeType::Number,
			'name' => array(AttributeType::String, 'label' => 'Your Name'),
			'email' => array(AttributeType::Email, 'label' => 'Your Email'),
			'message' => array(AttributeType::String, 'label' => 'Message'),
			'formId' => AttributeType::Number,
			'attachment' => AttributeType::Mixed,
			'dateCreated' => AttributeType::DateTime,
		);
	}

	public function rules(){
		return [
			[['email', 'message'], 'required'],
			['email', 'email'],
		];
	}
}
