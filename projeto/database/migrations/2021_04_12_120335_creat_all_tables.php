<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatAllTables extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //
	 Schema::dropIfExists('extra_products');
     Schema::create('extra_products', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->float('price');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('sellers');
     Schema::create('sellers', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('phone');
         $table->string('email');
         $table->integer('nif');
         $table->string('address');
         $table->string('postal_code');
         $table->string('city');
         $table->string('password');
         $table->integer('sort');
         $table->boolean('active');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('categories');
     Schema::create('categories', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('seller_id')->unsigned();
         $table->foreign('seller_id')->references('id')->on('seller');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('subcategories');
     Schema::create('subcategories', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('products');
     Schema::create('products', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->string('filepath');
         $table->string('filename');
         $table->float('price');
         $table->string('description');
         $table->integer('category_id')->unsigned();
         $table->integer('subcategory_id')->unsigned();
         $table->integer('quantity');
         $table->float('vat');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
         $table->foreign('category_id')->references('id')->on('category');
         $table->foreign('subcategory_id')->references('id')->on('subcategory');
     });

     Schema::dropIfExists('order_lines');
     Schema::create('order_lines', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('product_id')->unsigned();
         $table->foreign('product_id')->references('id')->on('product');
         $table->float('total_price');
         $table->integer('quantity');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('order_lines_seller');
     Schema::create('order_lines_seller', function (Blueprint $table) {
         $table->integer('order_line_id')->unsigned();
         $table->foreign('order_line_id')->references('id')->on('order_lines');
         $table->integer('seller_id')->unsigned();
         $table->foreign('seller_id')->references('id')->on('seller');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });


     Schema::dropIfExists('couriers');
     Schema::create('couriers', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('phone');
         $table->string('email');
         $table->integer('nif');
         $table->string('address');
         $table->string('postal_code');
         $table->string('city');
         $table->string('password');
         $table->integer('sort');
         $table->boolean('active');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('status');
     Schema::create('status', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('payment_types');
     Schema::create('payment_types', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('payments');
     Schema::create('payments', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('reference');
         $table->float('amount');
         $table->integer('phone');
         $table->integer('payment_type_id')->unsigned();
         $table->foreign('payment_type_id')->references('id')->on('payment_type');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('users');
     Schema::create('users', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('phone');
         $table->string('email');
         $table->integer('nif');
         $table->string('address');
         $table->string('postal_code');
         $table->string('city');
         $table->string('password');
         $table->integer('sort');
         $table->boolean('active');
         $table->timestamp('last_login');
         $table->int('ip');
         $table->string('remember_token');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('customers');
     Schema::create('customers', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name');
         $table->integer('phone');
         $table->string('email');
         $table->integer('nif');
         $table->string('address');
         $table->string('postal_code');
         $table->string('city');
         $table->string('password');
         $table->integer('sort');
         $table->boolean('active');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('addresses');
     Schema::create('addresses', function (Blueprint $table) {
         $table->increments('id');
         $table->string('address');
         $table->string('postal_code');
         $table->string('city');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('addresses_customer');
     Schema::create('addresses_customer', function (Blueprint $table) {
         $table->integer('addresses_id')->unsigned();
         $table->foreign('addresses_id')->references('id')->on('addresses');
         $table->integer('customer_id')->unsigned();
         $table->foreign('customer_id')->references('id')->on('customer');
     });

     Schema::dropIfExists('orders');
     Schema::create('orders', function (Blueprint $table) {
         $table->increments('id');
         $table->float('total_price');
         $table->float('vat');
         $table->string('shipment_address');
         $table->integer('status_id')->unsigned();
         $table->foreign('status_id')->references('id')->on('status');
         $table->integer('customer_id')->unsigned();
         $table->foreign('customer_id')->references('id')->on('customer');
         $table->integer('user_id')->unsigned();
         $table->foreign('user_id')->references('id')->on('user');
         $table->integer('courier_id')->unsigned();
         $table->foreign('courier_id')->references('id')->on('courier');
         $table->integer('payment_id')->unsigned();
         $table->foreign('payment_id')->references('id')->on('payment');
         $table->float('delivery_fee');
         $table->integer('sort');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });

     Schema::dropIfExists('roles');
     Schema::create('roles', function (Blueprint $table) {
         $table->increments('id');
         $table->string('source');
         $table->string('module');
         $table->string('name');
         $table->string('display_name');
         $table->string('description');
         $table->boolean('is_static');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });
 
     Schema::dropIfExists('permissions');
     Schema::create('permissions', function (Blueprint $table) {
         $table->increments('id');
         $table->string('group');
         $table->string('module');
         $table->string('name');
         $table->string('display_name');
         $table->string('description');
         $table->timestamp('created_at');
         $table->timestamp('updated_at');
         $table->timestamp('deleted_at');
     });
 
     Schema::dropIfExists('role_user');
     Schema::create('role_user', function (Blueprint $table) {
         $table->integer('role_id')->unsigned();
         $table->foreign('role_id')->references('id')->on('roles');
         $table->integer('user_id')->unsigned();
         $table->foreign('user_id')->references('id')->on('user');
     });

     Schema::dropIfExists('permission_role');
     Schema::create('permission_role', function (Blueprint $table) {
         $table->integer('role_id')->unsigned();
         $table->foreign('role_id')->references('id')->on('roles');
         $table->integer('permission_id')->unsigned();
         $table->foreign('permission_id')->references('id')->on('permissions');
     });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
