<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDeleted;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('themes')) {
            Schema::create('themes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('slug')->nullable();
                $table->string('path')->nullable();
                $table->string('url')->nullable();
                $table->string('current_sha')->nullable();
                $table->string('sha')->nullable();
                $table->timestamp('last_update_at')->nullable();
                $table->tinyInteger('default')->default(0)->nullable();
                $table->text('status')->nullable();
            });
        }

        $data_type_id = \DB::table('data_types')->insertGetId(           
            array (
                'name' => 'themes',
                'slug' => 'themes',
                'display_name_singular' => 'Theme',
                'display_name_plural' => 'Themes',
                'icon' => NULL,
                'model_name' => 'Modules\\Theme\\Entities\\Theme',
                'policy_name' => NULL,
                'controller' => 'Modules\\Theme\\Http\\Controllers\\ThemeController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
            ),
        );

        \DB::table('data_rows')->insert(array (
            62 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            63 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'title',
                'type' => 'text',
                'display_name' => 'Title',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 2,
            ),
            64 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'description',
                'type' => 'text_area',
                'display_name' => 'Description',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 3,
            ),
            65 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'slug',
                'type' => 'text',
                'display_name' => 'Slug',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 4,
            ),
            66 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'path',
                'type' => 'text',
                'display_name' => 'Path',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 5,
            ),
            67 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'url',
                'type' => 'text',
                'display_name' => 'URL',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 6,
            ),
            68 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'default',
                'type' => 'checkbox',
                'display_name' => 'Default',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ),
            69 => 
            array (
                'data_type_id' => $data_type_id,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Status',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 8,
            ),
        ));

        \DB::table('menu_items')->insert(array (
            24 => 
            array (
                'menu_id' => 1,
                'title' => 'Themes',
                'url' => '',
                'target' => '_self',
                'icon_class' => NULL,
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 10,
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
                'route' => 'voyager.themes.index',
                'parameters' => NULL,
            ),
        ));

        $permissions = array (
            46 => 
            array (
                'key' => 'browse_themes',
                'table_name' => 'themes',
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
            ),
            47 => 
            array (                
                'key' => 'read_themes',
                'table_name' => 'themes',
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
            ),
            48 => 
            array (
                'key' => 'edit_themes',
                'table_name' => 'themes',
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
            ),
            49 => 
            array (
                'key' => 'add_themes',
                'table_name' => 'themes',
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
            ),
            50 => 
            array (
                'key' => 'delete_themes',
                'table_name' => 'themes',
                'created_at' => '2021-09-07 14:11:45',
                'updated_at' => '2021-09-07 14:11:45',
            ),
        );

        foreach ($permissions as $permission) {
            $permission_id = \DB::table('permissions')->insertGetId($permission);
            \DB::table('permission_role')->insert(array (
                array (
                    'permission_id' => $permission_id,
                    'role_id' => 1,
                )
            ));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dataType = \DB::table('data_types')->where('name', 'themes')->get()->first();
        if ($dataType) {
            $this->deleteBread($dataType->id);
        }
        Schema::dropIfExists('themes');

        
    }

    /**
     * Delete BREAD.
     *
     * @param Number $id BREAD data_type id.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBread($id)
    {
        //$this->authorize('browse_bread');

        /* @var \TCG\Voyager\Models\DataType $dataType */
        $dataType = Voyager::model('DataType')->find($id);

        // Delete Translations, if present
        if (is_bread_translatable($dataType)) {
            $dataType->deleteAttributeTranslations($dataType->getTranslatableAttributes());
        }

        $res = Voyager::model('DataType')->destroy($id);
        // $data = $res
        //     ? $this->alertSuccess(__('voyager::bread.success_remove_bread', ['datatype' => $dataType->name]))
        //     : $this->alertError(__('voyager::bread.error_updating_bread'));
        // if ($res) {
        //     event(new BreadDeleted($dataType, $data));
        // }

        if (!is_null($dataType)) {
            Voyager::model('Permission')->removeFrom($dataType->name);

            // Delete menu
            \DB::table('menu_items')->where('route', 'voyager.themes.index')->delete();
        }

        return $res;
    }
}
