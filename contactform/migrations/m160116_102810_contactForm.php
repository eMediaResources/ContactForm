<?php
namespace Craft;

class m160116_102810_contactForm extends Base Migration{
	public function safeUp(){
		$this->createTable('contactform_form');
		$this->createTable('contactform_message');
		return true;
	}
	public function safeDown() {
        $this->dropTable('contactform_form');
        $this->dropTable('contactform_message');
    }
}
