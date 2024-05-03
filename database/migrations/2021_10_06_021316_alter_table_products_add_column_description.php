<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductsAddColumnDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'product_category_id')) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropForeign(['product_category_id']);
                });
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        Schema::table('products', function (Blueprint $table) {
            $table->mediumText('description')->nullable();
            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
