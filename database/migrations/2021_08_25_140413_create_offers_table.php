<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->text('details');
			$table->datetime('start_time');
			$table->datetime('end_time');
			$table->integer('restaurant_id')->unsigned();
			$table->string('image');
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}