<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->text('notes');
			$table->text('address');
			$table->string('cost');
			$table->string('delivery_cost');
			$table->string('total');
			$table->integer('restaurant_id')->unsigned();
			$table->integer('client_id')->unsigned();
			$table->datetime('delivery_time');
			$table->string('status');
			$table->string('commission');
			$table->enum('payment_method', array('cash', 'visa'));

		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}