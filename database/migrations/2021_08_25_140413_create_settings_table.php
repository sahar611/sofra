<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('fb_link');
			$table->string('tw_link');
			$table->string('instgram_link');
			$table->string('phone');
			$table->string('email');
			$table->string('app_commission');
		});
	}

	public function down()
	{
		Schema::drop('settings');
	}
}