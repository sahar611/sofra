<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('email');
			$table->string('phone');
			$table->string('password');
			$table->string('delivery_cost');
			$table->string('minimum_order');
			$table->string('image');
			$table->string('whatsapp');
			$table->enum('activated', array('0', '1'));
			$table->integer('region_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}