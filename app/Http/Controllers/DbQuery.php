<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

use App\Category;
use App\CategoryValue;
use App\User;
use App\Equipment;
use App\EquipmentCategories;

class DbQuery extends Controller
{
    // CATEGORIES
    public function selectAllCategories(){
    	return Category::select('category_id', 'name')->get();
    }
    public function selectCategoryValues($category_id){
    	return CategoryValue::select('descr')->where('category_id', $category_id)->get()
    	->map(function($item){
    		return $item->descr;
    	});
    }
    public function selectAllCategoryValues(){
        return CategoryValue::select('cat_values.cat_value_id AS categoryValueId', 'categories.name AS categoryName', 'cat_values.category_id AS categoryId', 'cat_values.descr AS name')
        ->join('categories', 'categories.category_id', '=', 'cat_values.category_id')
        ->get();
    }
    public function addCategoryValue($category_id = 0, $category_value_name = 'noname'){
        CategoryValue::insert([
            'category_id' => $category_id,
            'descr' => $category_value_name
        ]);
    }
    public function addCategory($category_id, $category_name = 'noname'){
    	Category::insert([
            'category_id' => $category_id,
    		'name' => $category_name
    	]);
    }
    public function deleteCategory($category_id){
        // DELETE FROM equip_categories
        // USING equip_categories ec
        // INNER JOIN cat_values cv ON cv.cat_value_id = ec.cat_value_id
        // WHERE cv.category_id = 1 AND equip_categories.cat_value_id = cv.cat_value_id;
        global $_category_id;
        $_category_id = $category_id;

        DB::transaction(function(){
            DB::statement('
                DELETE FROM equip_categories
                USING equip_categories ec
                INNER JOIN cat_values cv ON cv.cat_value_id = ec.cat_value_id
                WHERE cv.category_id = '.$GLOBALS['_category_id'].' AND equip_categories.cat_value_id = cv.cat_value_id
            ');
            CategoryValue::where('category_id', $GLOBALS['_category_id'])->delete();
            Category::where('category_id', $GLOBALS['_category_id'])->delete();
        });
    }

    // EQUIPMENT
    public function insertEquipment($equipment_info){
        global $equipment;
        $equipment = json_decode($equipment_info);

        DB::transaction(function(){
            global $equipment;
            Equipment::insert([
                'equipment_id' => $equipment->equipment_id,
                'name' => $equipment->name,
                'descr' => $equipment->descr
            ]);
            foreach($equipment->cat_value_ids as $cat_value_id) {
                EquipmentCategories::insert([
                    'equipment_id' => $equipment->equipment_id,
                    'cat_value_id' => $cat_value_id
                ]);
            }
        });
    }
    public function selectAllEquipment(){
        return Equipment::all();
    }
    public function deleteEquipment($equipment_id){
        global $_equipment_id;
        $_equipment_id = $equipment_id;
        DB::transaction(function(){
            global $_equipment_id; 
            EquipmentCategories::where('equipment_id', $_equipment_id)->delete();
            Equipment::where('equipment_id', $_equipment_id)->delete();
        });
    }

    // EQUIPMENT CATEGORIES
    public function selectEquipmentCategories($equipment_id){
        return EquipmentCategories::select('cat_values.descr AS cat_value_name', 'cat_values.category_id', 'categories.name')
        ->join('cat_values', 'cat_values.cat_value_id', '=', 'equip_categories.cat_value_id')
        ->join('equipment', 'equipment.equipment_id', '=', 'equip_categories.equipment_id')
        ->join('categories', 'categories.category_id', '=', 'cat_values.category_id')
        ->where('equip_categories.equipment_id', $equipment_id)
        ->get();
    }


    // USERS
    public function selectAllUsers(){
        return User::all();
    }
    public function addUser($user_id, $user_name = 'noname', $user_email = 'noemail', $user_password = 'nopass'){
        User::insert([
            'id' => $user_id,
            'name' => $user_name,
            'email' => $user_email,
            'password' => $user_password,
        ]);
    }
    public function deleteUser($user_id){
        User::where('id', $user_id)->delete();
    }
}
