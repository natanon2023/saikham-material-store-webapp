<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessoryType;
use App\Models\AluminiumProfileType;
use App\Models\ConsumableType;
use App\Models\GlassType;
use App\Models\ToolType;
use App\Models\Unit;
use App\Models\Dealer;



class MaterialTypeController extends Controller
{
    public function createFormaluminiumType(Request $request)
    {
        $searchName = $request->get('searchName');

        $aluminiumTypes = AluminiumProfileType::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();

        return view('admin.materialstype.create.aluminiumType', compact('aluminiumTypes', 'searchName'));
    }

    public function createaluminiumType(Request $request)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทอลูมิเนียม');
        }

        $existingType = AluminiumProfileType::where('name', $request->name)->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ประเภทอลูมิเนียมนี้มีอยู่แล้ว');
        }

        $newAluminiumType = new AluminiumProfileType();
        $newAluminiumType->name = trim($request->name);

        if ($newAluminiumType->save()) {
            return redirect()->route('admin.materials.formaluminium')
                ->with('success', 'เพิ่มประเภทอลูมิเนียมสำเร็จ');
        } else {
            return redirect()->route('admin.materalstype.createFormaluminiumType')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }


    public function editAluminiumType($id)
    {
        $aluminiumType = AluminiumProfileType::find($id);
        return view('admin.materialstype.edit.aluminiumType', compact('aluminiumType'));
    }

    public function updateAluminiumType(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทอลูมิเนียม');
        }

        $aluminiumType = AluminiumProfileType::find($id);

        $existingType = AluminiumProfileType::where('name', $request->name)->where('id', '!=', $id)->first();

        if ($existingType) {
            return redirect()->back()->with('error', 'ประเภทอลูมิเนียมนี้มีอยู่แล้ว');
        }

        $aluminiumType->name = trim($request->name);

        if ($aluminiumType->save()) {
            return redirect()->route('admin.materalstype.createFormaluminiumType')->with('success', 'แก้ไขประเภทอลูมิเนียมสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }


    public function deleteAluminiumType($id)
    {
        $aluminiumType = AluminiumProfileType::find($id);

        if ($aluminiumType->delete()) {
            return redirect()->back()->with('success', 'ลบประเภทอลูมิเนียมสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }



    public function createFormglassType(Request $request)
    {
        $searchName = $request->get('searchName');

        $glassTypes = GlassType::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();

        return view('admin.materialstype.create.glassType',compact('searchName','glassTypes'));
    }

    public function createglassType(Request $request)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทกระจก');
        }

        $existingType = GlassType::where('name', $request->name)->first();
        if ($existingType) {
            return redirect()->route('admin.materalstype.createFormglassType')->with('error', 'ประเภทกระจกนี้มีอยู่แล้ว');
        }

        $newGlassType = new GlassType();
        $newGlassType->name = trim($request->name);

        if ($newGlassType->save()) {
            return redirect()->route('admin.materials.formglass')
                ->with('success', 'เพิ่มประเภทกระจกสำเร็จ');
        } else {
            return redirect()->route('admin.materalstype.createFormglassType')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function editGlassType($id)
    {
        $glassType = GlassType::find($id);
        return view('admin.materialstype.edit.glassType', compact('glassType'));
    }

    public function updateGlassType(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทกระจก');
        }

        $glassType = GlassType::find($id);

        $existingType = GlassType::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ประเภทกระจกนี้มีอยู่แล้ว');
        }

        $glassType->name = trim($request->name);

        if ($glassType->save()) {
            return redirect()->route('admin.materalstype.createFormglassType')
                ->with('success', 'แก้ไขประเภทกระจกสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deleteGlassType($id)
    {
        $glassType = GlassType::find($id);

        if ($glassType->delete()) {
            return redirect()->route('admin.materalstype.createFormglassType')
                ->with('success', 'ลบประเภทกระจกสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }

    public function createFormaccessoryType(Request $request)
    {
        $searchName = $request->get('searchName');

        $accessoryTypes = AccessoryType::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();
        return view('admin.materialstype.create.accessoryType',compact('searchName','accessoryTypes'));
    }

    public function createaccessoryType(Request $request)
    {
        
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทอุปกรณ์เสริม');
        }

        $existingType = AccessoryType::where('name', $request->name)->first();
        if ($existingType) {
            return redirect()->route('admin.materalstype.createFormaccessoryType')->with('error', 'ประเภทอุปกรณ์เสริมนี้มีอยู่แล้ว');
        }

        $newAccessoryType = new AccessoryType();
        $newAccessoryType->name = trim($request->name);

        if ($newAccessoryType->save()) {
            return redirect()->route('admin.materials.formaccessory')
                ->with('success', 'เพิ่มประเภทกระอุปกรณ์เสริมสำเร็จ');
        } else {
            return redirect()->route('admin.materalstype.createFormaccessoryType')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function editAccessoryType($id)
    {
        $accessoryType = AccessoryType::find($id);
        return view('admin.materialstype.edit.accessoryType', compact('accessoryType'));
    }

    public function updateAccessoryType(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทอุปกรณ์เสริม');
        }

        $accessoryType = AccessoryType::find($id);

        $existingType = AccessoryType::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ประเภทอุปกรณ์เสริมนี้มีอยู่แล้ว');
        }

        $accessoryType->name = trim($request->name);

        if ($accessoryType->save()) {
            return redirect()->route('admin.materalstype.createFormaccessoryType')
                ->with('success', 'แก้ไขประเภทอุปกรณ์เสริมสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deleteAccessoryType($id)
    {
        $accessoryType = AccessoryType::find($id);

        if ($accessoryType->delete()) {
            return redirect()->route('admin.materalstype.createFormaccessoryType')
                ->with('success', 'ลบประเภทอุปกรณ์เสริมสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }

    public function createFormtoolType(Request $request)
    {
        $searchName = $request->get('searchName');

        $toolTypes = Tooltype::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();
        return view('admin.materialstype.create.toolType',compact('searchName' , 'toolTypes'));
    }

    public function createtoolType(Request $request)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทเครื่องมือช่าง');
        }

        $existingType = ToolType::where('name', $request->name)->first();
        if ($existingType) {
            return redirect()->route('admin.materalstype.createFormtoolType')->with('error', 'ประเภทเครื่องมือช่างนี้มีอยู่แล้ว');
        }

        $newToolType = new ToolType();
        $newToolType->name = trim($request->name);

        if ($newToolType->save()) {
            return redirect()->route('admin.materials.formtool')
                ->with('success', 'เพิ่มประเภทเครื่องมือช่างสำเร็จ');
        } else {
            return redirect()->route('admin.materalstype.createFormtoolType')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function editToolType($id)
    {
        $toolType = ToolType::find($id);
        return view('admin.materialstype.edit.toolType', compact('toolType'));
    }

    public function updateToolType(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทเครื่องมือช่าง');
        }

        $toolType = ToolType::find($id);

        $existingType = ToolType::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ประเภทเครื่องมือช่างนี้มีอยู่แล้ว');
        }

        $toolType->name = trim($request->name);

        if ($toolType->save()) {
            return redirect()->route('admin.materalstype.index')
                ->with('success', 'แก้ไขประเภทเครื่องมือช่างสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deleteToolType($id)
    {
        $toolType = ToolType::find($id);

        if ($toolType->delete()) {
            return redirect()->back()
                ->with('success', 'ลบประเภทเครื่องมือช่างสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }


    public function createFormdealer(Request $request)
    {   
        $searchName = $request->get('searchName');

        $dealers = Dealer::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();


        return view('admin.materialstype.create.dealer',compact('dealers','searchName'));
    }


    public function createdealer(Request $request)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อตัวแทนจำหน่าย');
        }

        $existingdealer = Dealer::where('name', $request->name)->first();
        if ($existingdealer) {
            return redirect()->back()->with('error', 'ชื่อตัวแทนจำหน่ายนี้มีอยู่แล้ว');
        }

        $newDealer = new Dealer();
        $newDealer->name = trim($request->name);

        if ($newDealer->save()) {
            return redirect()->route('admin.materials.addstockpage')
                ->with('success', 'เพิ่มชื่อตัวแทนจำหน่ายสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deletedealer($id)
    {
        $dealer = Dealer::find($id);

        if ($dealer->delete()) {
            return redirect()->back()
                ->with('success', 'ลบประเภทเครื่องมือช่างสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }

    public function editdealer($id)
    {
        $dealer = Dealer::find($id);
        return view('admin.materialstype.edit.dealer', compact('dealer'));
    }

    public function updatedealer(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อตัวแทนจำหน่าย');
        }

        $dealer = Dealer::find($id);

        $existingType = Dealer::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ตัวแทนจำหน่ายนี้มีอยู่แล้ว');
        }

        $dealer->name = trim($request->name);

        if ($dealer->save()) {
            return redirect()->route('admin.materalstype.createFormdealer')
                ->with('success', 'แก้ไขประเภทเครื่องมือช่างสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }







    public function createFormunit(Request $request)
    {
        $searchName = $request->get('searchName');

        $units = Unit::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();

        return view('admin.materialstype.create.unit',compact('searchName','units'));
    }




    public function createunit(Request $request)
    {

        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่กรอกหน่วยที่ต้องการเพิ่ม');
        }


        $existingdealer = Unit::where('name', $request->name)->first();
        if ($existingdealer) {
            return redirect()->route('admin.materalstype.createFormunit')
                ->with('error', 'ชื่อหน่วยนี้มีอยู่แล้ว');
        }


        $newUnit = new Unit();
        $newUnit->name = trim($request->name);

        if ($newUnit->save()) {
            return redirect()->back()
                ->with('success', 'เพิ่มหน่วยสำเร็จ');
        } else {
            return redirect()->back()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deleteunit($id)
    {
        $unit = Unit::find($id);

        if ($unit->delete()) {
            return redirect()->back()
                ->with('success', 'ลบหน่วยวัสดุ/อุปกรณ์สำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }

    public function editunit($id)
    {
        $unit = Unit::find($id);
        return view('admin.materialstype.edit.unit', compact('unit'));
    }

    public function updateunit(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อหน่วย');
        }

        $unit = Unit::find($id);

        $existingType = Unit::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ชื่อหน่วยนี้มีอยู่แล้ว');
        }

        $unit->name = trim($request->name);

        if ($unit->save()) {
            return redirect()->route('admin.materalstype.createFormunit')
                ->with('success', 'แก้ไขชื่อหน่วยสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }



    public function createFormconsumableType(Request $request)
    {
        $searchName = $request->get('searchName');

        $consumableTypes = Consumabletype::when($searchName, function ($query, $searchName) {
            return $query->where('name', 'like', '%' . $searchName . '%');
        })->get();

        return view('admin.materialstype.create.consumableType', compact('searchName','consumableTypes'));
    }

    public function createconsumableType(Request $request)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อประเภทวัสดุสิ้นเปลือง');
        }

        $existingType = ConsumableType::where('name', $request->name)->first();
        if ($existingType) {
            return redirect()->route('admin.materalstype.createFormconsumableType')->with('error', 'ประเภทวัสดุสิ้นเปลืองนี้มีอยู่แล้ว');
        }

        $newconsumableType = new ConsumableType();
        $newconsumableType->name = trim($request->name);

        if ($newconsumableType->save()) {
            return redirect()->route('admin.materials.formconsumable')
                ->with('success', 'เพิ่มประเภทวัสดุสิ้นเปลืองสำเร็จ');
        } else {
            return redirect()->route('admin.materalstype.createFormconsumableType')->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function deleteconsumableType($id)
    {
        $consumableTypes = ConsumableType::find($id);

        if ($consumableTypes->delete()) {
            return redirect()->route('admin.materialstype.edit.createFormconsumableType')
                ->with('success', 'ลบประเภทวัสดุสิ้นเปลืองสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถลบข้อมูลได้');
        }
    }

    public function editconsumableType($id)
    {
        $consumableTypes = ConsumableType::find($id);
        return view('admin.materialstype.edit.consumable', compact('consumableTypes'));
    }

    public function updateconsumableType(Request $request, $id)
    {
        if (empty($request->name)) {
            return redirect()->back()->with('error', 'กรุณาใส่ชื่อวัสดุสิ้นเปลือง');
        }

        $consumableTypes = ConsumableType::find($id);

        $existingType = ConsumableType::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();
        if ($existingType) {
            return redirect()->back()->with('error', 'ชื่อวัสดุสิ้นเปลืองนี้มีอยู่แล้ว');
        }

        $consumableTypes->name = trim($request->name);

        if ($consumableTypes->save()) {
            return redirect()->route('admin.materalstype.createFormconsumableType')
                ->with('success', 'แก้ไขชื่อวัสดุสิ้นเปลืองสำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }






    private $models = [
        'aluminium' => AluminiumProfileType::class,
        'glass' => GlassType::class,
        'accessory' => AccessoryType::class,
        'tool' => ToolType::class,
        'consumable' => ConsumableType::class,
        'dealer' => Dealer::class,
        'unit' => Unit::class
    ];

    private $names = [
        'aluminium' => 'อลูมิเนียม',
        'glass' => 'กระจก',
        'accessory' => 'อุปกรณ์เสริม',
        'tool' => 'เครื่องมือช่าง',
        'consumable' => 'วัสดุสิ้นเปลือง',
        'dealer' => 'ตัวแทนจำหน่าย',
        'unit' => 'หน่วยนับ'
    ];

    public function trash()
    {
        $allDeletedItems = collect(); 
        
        foreach ($this->models as $type => $modelClass) {
            $deletedItems = $modelClass::onlyTrashed()->get();
            
            foreach ($deletedItems as $item) {
                $item->material_type = $type; 
                $item->material_type_name = $this->names[$type]; 
            }
            
            $allDeletedItems = $allDeletedItems->merge($deletedItems);
        }
        
        $allDeletedItems = $allDeletedItems->sortByDesc('deleted_at');
        
        return view('admin.materialstype.trash', compact('allDeletedItems'));
    }


    public function restore(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $modelClass = $this->models[$type];
        
        $item = $modelClass::onlyTrashed()->find($id);
        $item->restore();
        
        return back()->with('success', 'กู้คืน' . $this->names[$type] . ' "' . $item->name . '" สำเร็จ');
    }



}
