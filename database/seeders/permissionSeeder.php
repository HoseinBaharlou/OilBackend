<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
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
            ],
            [
                'name'=>'create_post',
                'persian_name'=>'ایجاد پست'
            ],
            [
                'name'=>'post_trash_manager',
                'persian_name'=>'مدیریت سطل زباله پست ها'
            ],
            [
                'name'=>'ticket_manager',
                'persian_name'=>'مدیریت تیکت ها'
            ],
            [
                'name'=>'config_pages',
                'persian_name'=>'مدیریت صفحات'
            ],
            [
                'name'=>'create_category',
                'persian_name'=>'ایجاد دسته بندی'
            ],
            [
                'name'=>'edit_category',
                'persian_name'=>'ویرایش دسته بندی'
            ],
            [
                'name'=>'delete_category',
                'persian_name'=>'حذف دسته بندی'
            ],
            [
                'name'=>'header_manager',
                'persian_name'=>'مدیریت هدر سایت'
            ],
            [
                'name'=>'edit_post',
                'persian_name'=>'ویرایش پست'
            ],
            [
                'name'=>'edit_role_user',
                'persian_name'=>'ویرایش سطح دسترسی کاربر'
            ],
            [
                'name'=>'edit_role',
                'persian_name'=>'ویرایش نقش'
            ],
            [
            'name'=>'create_role',
            'persian_name'=>'ایجاد نقش'
            ],
            [
                'name'=>'role_list',
                'persian_name'=>'لیست نقش ها'
            ],
            [
                'name'=>'edit_product',
                'persian_name'=>'ویرایش محصول'
            ],
            [
                'name'=>'delete_comment',
                'persian_name'=>'حذف کامنت'
            ],
            [
              'name'=>'edit_users',
              'persian_name'=>'ویرایش کاربر'
            ],
            [
                'name'=>'tellMe_manager',
                'persian_name'=>'مدیریت بخش تماس با ما'
            ],
            [
                'name'=>'page_manager',
                'persian_name'=>'مدیریت صفحات'
            ]
        ];

        for ($i=0;$i < count($permission);$i++){
            Permission::create([
               'name'=>$permission[$i]['name'],
               'persian_name'=>$permission[$i]['persian_name']
            ]);
        }
    }
}
