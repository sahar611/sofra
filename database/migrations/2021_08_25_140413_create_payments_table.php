<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	public function up()
	{
		Schema::create('payments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('amount');
			$table->integer('restaurant_id')->unsigned();
			$table->text('notes');
			$table->date('payment_date');
		});
	}

	public function down()
	{
		Schema::drop('payments');
	}
}