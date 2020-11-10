<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('code')->unique();
            $table->double('sale_price')->nullable($value = true);
            $table->string('name');
            $table->string('description')->nullable($value = true);
            $table->bigInteger('units_available');
            $table->timestamps();
            $table->softDeletes(); //Borrado suave
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
