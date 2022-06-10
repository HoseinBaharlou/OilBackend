<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('persian_name');
            $table->timestamps();
        });

        \App\Models\Category::create(
            [
                [
                    'name'=>'show_admin_panel',
                    'persian_name'=>'مشاهده پنل مدیریت'
                ],
                [
                    'name'=>'file_manager',
                    'persian_name'=>'مدیریت فایل'
                ],

                [
                    'name'=>'users_list',
                    'persian_name'=>'لیست کاربران'
                ],

                [
                    'name'=>'role_list',
                    'persian_name'=>'لیست نقش ها'
                ],

                [
                    'name'=>'product_list',
                    'persian_name'=>'لیست محصولات'
                ],

                [
                    'name'=>'product_create',
                    'persian_name'=>'ایجاد محصول'
                ],

                [
                    'name'=>'product_trash_manager',
                    'persian_name'=>'مدیریت سطل زباله محصولات'
                ],

                [
                    'name'=>'post_list',
                    'persian_name'=>'لیست پست ها'
                ]
            ],

        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
