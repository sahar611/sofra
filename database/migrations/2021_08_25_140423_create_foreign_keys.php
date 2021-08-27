<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('clients', function(Blueprint $table) {
			$table->foreign('region_id')->references('id')->on('regions')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('restaurants', function(Blueprint $table) {
			$table->foreign('region_id')->references('id')->on('regions')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('regions', function(Blueprint $table) {
			$table->foreign('city_id')->references('id')->on('cities')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->foreign('client_id')->references('id')->on('clients')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('offers', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('category_restaurant', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('category_restaurant', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('order_product', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('order_product', function(Blueprint $table) {
			$table->foreign('product_id')->references('id')->on('products')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->foreign('order_id')->references('id')->on('orders')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('payments', function(Blueprint $table) {
			$table->foreign('restaurant_id')->references('id')->on('restaurants')
						->onDelete('no action')
						->onUpdate('no action');
		});
	}

	public function down()
	{
		Schema::table('clients', function(Blueprint $table) {
			$table->dropForeign('clients_region_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_restaurant_id_foreign');
		});
		Schema::table('orders', function(Blueprint $table) {
			$table->dropForeign('orders_client_id_foreign');
		});
		Schema::table('restaurants', function(Blueprint $table) {
			$table->dropForeign('restaurants_region_id_foreign');
		});
		Schema::table('regions', function(Blueprint $table) {
			$table->dropForeign('regions_city_id_foreign');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->dropForeign('reviews_client_id_foreign');
		});
		Schema::table('reviews', function(Blueprint $table) {
			$table->dropForeign('reviews_restaurant_id_foreign');
		});
		Schema::table('offers', function(Blueprint $table) {
			$table->dropForeign('offers_restaurant_id_foreign');
		});
		Schema::table('products', function(Blueprint $table) {
			$table->dropForeign('products_restaurant_id_foreign');
		});
		Schema::table('category_restaurant', function(Blueprint $table) {
			$table->dropForeign('category_restaurant_restaurant_id_foreign');
		});
		Schema::table('category_restaurant', function(Blueprint $table) {
			$table->dropForeign('category_restaurant_category_id_foreign');
		});
		Schema::table('order_product', function(Blueprint $table) {
			$table->dropForeign('order_product_order_id_foreign');
		});
		Schema::table('order_product', function(Blueprint $table) {
			$table->dropForeign('order_product_product_id_foreign');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropForeign('notifications_order_id_foreign');
		});
		Schema::table('payments', function(Blueprint $table) {
			$table->dropForeign('payments_restaurant_id_foreign');
		});
	}
}