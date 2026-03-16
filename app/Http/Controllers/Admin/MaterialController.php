<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialLog;
use App\Models\AluminiumItem;
use App\Models\AluminiumLength;
use App\Models\AluminiumProfileType;
use App\Models\AluminumSurfaceFinish;
use App\Models\GlassItem;
use App\Models\GlassSize;
use App\Models\GlassType;
use App\Models\AccessoryItem;
use App\Models\AccessoryType;
use App\Models\ToolItem;
use App\Models\ToolType;
use App\Models\ConsumableItem;
use App\Models\ColourItem;
use App\Models\Dealer;
use App\Models\User;
use App\Models\StockEditLog;
use App\Models\ConsumableType;
use App\Models\MaterialPrice;
use App\Models\Price;
use App\Models\Unit;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index(Request $request)
    {

        $query = Material::with([
            'aluminiumItem.aluminiumType',
            'aluminiumItem.aluminumSurfaceFinish',
            'aluminiumItem.aluminiumLengths',
            'glassItem.glassType',
            'glassItem.colourItem',
            'glassItem.glassSize',
            'accessoryItem.accessoryType',
            'accessoryItem.aluminumSurfaceFinish',
            'accessoryItem.unit',
            'toolItem.toolType',
            'toolItem.unit',
            'consumableItem.unit',
            'consumableItem.consumabletype',
            'price',
            'user',


        ])->orderBy('id', 'desc');

        if ($request->filled('material_type')) {
            $typematerial = [
                'aluminum'   => 'อลูมิเนียม',
                'glass'      => 'กระจก',
                'accessory'  => 'อุปกรณ์เสริม',
                'consumable' => 'วัสดุสิ้นเปลือง',
                'tool'       => 'เครื่องมือช่าง'
            ];

            $type = $request->material_type;

            if (array_key_exists($type, $typematerial)) {
                $query->where('material_type', $typematerial[$type]);
            }
        }

        $material = $query->get();



        return view('admin.materials.index', compact('material',));
    }

    public function showselecttypematerials()
    {
        return view('admin.materials.create.select-type');
    }


    public function formaluminium()
    {
        $aluminiumType = AluminiumProfileType::all();
        $aluminiumsurface = AluminumSurfaceFinish::all();
        return view('admin.materials.create.aluminium', compact('aluminiumType', 'aluminiumsurface'));
    }

    public function createaluminium(Request $request)
    {

        $file = $request->file('image_aluminium_item');
        $imageData = file_get_contents($file->getRealPath());


        $aluminiumItem = AluminiumItem::create([
            'aluminium_profile_types_id' => $request->aluminium_profile_types_id,
            'aluminum_surface_finish_id' => $request->aluminum_surface_finish_id,
            'image_aluminium_item' => $imageData,
        ]);

        $material = Material::create([
            'material_type' => 'อลูมิเนียม',
            'aluminium_item_id' => $aluminiumItem->id,
            'user_id'           => Auth::id(),
        ]);

        return redirect()->route('admin.materials.index')->with('success', 'บันทึกข้อมูลอลูมิเนียมสำเร็จ');
    }

    public function formglass()
    {
        $glasstype = GlassType::all();
        $colour = ColourItem::all();
        return view('admin.materials.create.glass', compact('glasstype', 'colour'));
    }


    public function createglass(Request $request)
    {

        $file = $request->file('image_glass_item');
        $imageData = file_get_contents($file->getRealPath());

        $glassItem = GlassItem::create([
            'glass_type_id' => $request->glass_type_id,
            'colouritem_id' => $request->colouritem_id,
            'image_glass_item' => $imageData,
        ]);

        $material = Material::create([
            'material_type' => 'กระจก',
            'glass_item_id' => $glassItem->id,
            'user_id' => Auth::id()
        ]);




        return redirect()->route('admin.materials.index')->with('success', 'บันทึกข้อมูลกระจกสำเร็จ');
    }

    public function formaccessory()
    {

        $aluminiumsurface = AluminumSurfaceFinish::all();
        $unit = Unit::all();
        $accessorytype = AccessoryType::all();

        return view('admin.materials.create.accessory', compact('aluminiumsurface', 'unit', 'accessorytype'));
    }

    public function createaccessory(Request $request)
    {

        $file = $request->file('image_accessory_item');
        $imageData = file_get_contents($file->getRealPath());



        $accessoryItem = AccessoryItem::create([
            'accessory_type_id' => $request->accessory_type_id,
            'aluminum_surface_finish_id' =>  $request->aluminum_surface_finish_id,
            'unit_id' => $request->unit_id,
            'image_accessory_item' => $imageData,
        ]);

        $material = Material::create([
            'material_type' => 'อุปกรณ์เสริม',
            'accessory_item_id' => $accessoryItem->id,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('admin.materials.index')->with('success', 'บันทึกข้อมูลอุปกรณ์เสริมสำเร็จ');
    }


    public function formtool()
    {
        $tooltype = ToolType::all();
        $unit = Unit::all();
        return view('admin.materials.create.tool', compact('tooltype', 'unit'));
    }

    public function createtool(Request $request)
    {
        $file = $request->file('image_tool_item');
        $imageData = file_get_contents($file->getRealPath());

        $toolItem = ToolItem::create([
            'tool_type_id' => $request->tool_type_id,
            'description' => $request->description,
            'unit_id' => $request->unit_id,
            'image_tool_item' => $imageData,
        ]);

        Material::create([
            'material_type' => 'เครื่องมือช่าง',
            'tool_item_id' => $toolItem->id,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('admin.materials.index')->with('success', 'บันทึกข้อมูลเครื่องช่างสำเร็จ');
    }

    public function formconsumable()
    {
        $consumabletype = ConsumableType::all();
        $unit = Unit::all();
        return view('admin.materials.create.consumable', compact('consumabletype', 'unit'));
    }

    public function createconsumable(Request $request)
    {
        $file = $request->file('image_consumable_item');
        $imageData = file_get_contents($file->getRealPath());

        $consumableItem = ConsumableItem::create([
            'consumable_type_id' => $request->consumable_type_id,
            'unit_id' => $request->unit_id,
            'image_consumable_item' => $imageData
        ]);

        $material = Material::create([
            'material_type' => 'วัสดุสิ้นเปลือง',
            'consumable_item_id' => $consumableItem->id,
            'user_id' => Auth::id()
        ]);


        return redirect()->route('admin.materials.index')->with('success', 'บันทึกข้อมูลวัสดุสิ้นเปลืองสำเร็จ');
    }


    public function showdetailmaterial(Request $request, $id)
    {
        $material = Material::with([
            'aluminiumItem.aluminiumType',
            'aluminiumItem.aluminumSurfaceFinish',
            'aluminiumItem.aluminiumLengths',
            'glassItem.glassType',
            'glassItem.colourItem',
            'glassItem.glassSize',
            'accessoryItem.accessoryType',
            'accessoryItem.aluminumSurfaceFinish',
            'accessoryItem.unit',
            'toolItem.toolType',
            'toolItem.unit',
            'consumableItem.unit',
            'consumableItem.consumabletype',
            'user',
            'price',
        ])->findOrFail($id);

        $logquery = MaterialLog::with(['user', 'price'])->where('material_id', $id)->orderBy('created_at', 'desc'); // เรียงจากล่าสุดไปเก่าสุด

        if ($request->filled('direction')) {

            $directionTypes = ['in', 'out']; 

            if (in_array($request->direction, $directionTypes)) {
                $logquery->where('direction', $request->direction);
            }
        }

        $material->setRelation('materialLogs', $logquery->get());

        return view('admin.materials.show', compact('material'));
    }

    public function editmaterial($id)
    {
        $material = Material::with([
            'aluminiumItem',
            'glassItem',
            'accessoryItem',
            'toolItem',
            'consumableItem'
        ])->find($id);

        $aluminiumType = AluminiumProfileType::all();
        $glassType = GlassType::all();
        $accessoryType = AccessoryType::all();
        $toolType = ToolType::all();
        $consumableType = ConsumableType::all();
        $unit = Unit::all();
        $surface = AluminumSurfaceFinish::all();
        $colour = ColourItem::all();

        return view('admin.materials.edit', compact(
            'material',
            'aluminiumType',
            'glassType',
            'accessoryType',
            'toolType',
            'consumableType',
            'unit',
            'surface',
            'colour'
        ));
    }

    public function updatematerial(Request $request, $id)
    {
        $material = Material::find($id);

        if ($material->material_type == 'อลูมิเนียม') {

            $aluminiumItem = $material->aluminiumItem;

            $updatedata = [
                'aluminium_profile_types_id' => $request->aluminium_profile_types_id,
                'aluminum_surface_finish_id' => $request->aluminum_surface_finish_id,
            ];

            if ($request->hasFile('image_aluminium_item')) {
                $file = $request->file('image_aluminium_item');
                $updatedata['image_aluminium_item'] = file_get_contents($file->getRealPath());
            }

            $aluminiumItem->update($updatedata);
        } elseif ($material->material_type == 'กระจก') {
            $glassItem = $material->glassItem;

            $updatedata = [
                'glass_type_id' => $request->glass_type_id,
                'colouritem_id' => $request->colouritem_id,
            ];

            if ($request->hasFile('image_glass_item')) {
                $file = $request->file('image_glass_item');
                $updatedata['image_glass_item'] = file_get_contents($file->getRealPath());
            }

            $glassItem->update($updatedata);
        } elseif ($material->material_type == 'อุปกรณ์เสริม') {
            $accessoryItem = $material->accessoryItem;

            $updatedata = [
                'accessory_type_id' => $request->accessory_type_id,
                'aluminum_surface_finish_id' =>  $request->aluminum_surface_finish_id,
                'unit_id' => $request->unit_id,
            ];

            if ($request->hasFile('image_accessory_item')) {
                $file = $request->file('image_accessory_item');
                $updatedata['image_accessory_item'] = file_get_contents($file->getRealPath());
            }

            $accessoryItem->update($updatedata);
        } elseif ($material->material_type == 'เครื่องมือช่าง') {
            $toolItem = $material->toolItem;

            $updatedata = [
                'tool_type_id' => $request->tool_type_id,
                'description' => $request->description,
                'unit_id' => $request->unit_id,
            ];

            if ($request->hasFile('image_tool_item')) {
                $file = $request->file('image_tool_item');
                $updatedata['image_tool_item'] = file_get_contents($file->getRealPath());
            }

            $toolItem->update($updatedata);
        } elseif ($material->material_type == 'วัสดุสิ้นเปลือง') {
            $consumableItem = $material->consumableItem;

            $updatedata = [
                'consumable_type_id' => $request->consumable_type_id,
                'unit_id' => $request->unit_id,
            ];

            if ($request->hasFile('image_consumable_item')) {
                $file = $request->file('image_consumable_item');
                $updatedata['image_consumable_item'] = file_get_contents($file->getRealPath());
            }

            $consumableItem->update($updatedata);
        }

        return redirect()->route('admin.materials.showdetailmaterial', $material->id)->with('success', 'แก้ไขข้อมูล' . $material->material_type . 'สำเร็จ');
    }

    public function destroy($id)
    {
        $material = Material::find($id);
        $material->delete();

        return redirect()->route('admin.materials.index')->with('success', 'ลบข้อมูล' . $material->material_type . 'สำเร็จ');
    }

    public function trash()
    {
        $material = Material::onlyTrashed()
            ->with([
                'aluminiumItem.aluminiumType',
                'aluminiumItem.aluminumSurfaceFinish',
                'aluminiumItem.aluminiumLengths',
                'glassItem.glassType',
                'glassItem.colourItem',
                'glassItem.glassSize',
                'accessoryItem.accessoryType',
                'accessoryItem.aluminumSurfaceFinish',
                'accessoryItem.unit',
                'toolItem.toolType',
                'toolItem.unit',
                'consumableItem.unit',
                'consumableItem.consumabletype',
                'user'
            ])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('admin.materials.trash', compact('material'));
    }


    public function restore($id)
    {
        $material = Material::onlyTrashed()->find($id);
        $material->restore();

        return redirect()->route('admin.materials.trash')->with('success', 'กู้คืนข้อมูล' . $material->material_type . 'สำเร็จ');
    }



    public function addstockpage(Request $request)
    {
        $query = Material::with([
            'aluminiumItem.aluminiumType',
            'aluminiumItem.aluminumSurfaceFinish',
            'aluminiumItem.aluminiumLengths',
            'glassItem.glassType',
            'glassItem.colourItem',
            'glassItem.glassSize',
            'accessoryItem.accessoryType',
            'accessoryItem.aluminumSurfaceFinish',
            'accessoryItem.unit',
            'consumableItem.unit',
            'consumableItem.consumabletype',
            'toolItem.toolType',
        ]);

        $type = [
            'aluminum'   => 'อลูมิเนียม',
            'glass'      => 'กระจก',
            'accessory'  => 'อุปกรณ์เสริม',
            'consumable' => 'วัสดุสิ้นเปลือง',
            'tool'       => 'เครื่องมือช่าง',
        ];

        if ($request->filled('material_type') && isset($type[$request->material_type])) {
            $query->where('material_type', $type[$request->material_type]);
        }

        if ($request->filled('aluminium_type_id')) {
            $query->whereHas('aluminiumItem.aluminiumType', function ($q) use ($request) {
                $q->where('id', $request->aluminium_type_id);
            });
        }
        if ($request->filled('aluminium_surface_id')) {
            $query->whereHas('aluminiumItem.aluminumSurfaceFinish', function ($q) use ($request) {
                $q->where('id', $request->aluminium_surface_id);
            });
        }

        if ($request->filled('glass_type_id')) {
            $query->whereHas('glassItem.glassType', function ($q) use ($request) {
                $q->where('id', $request->glass_type_id);
            });
        }
        if ($request->filled('glass_colour_id')) {
            $query->whereHas('glassItem.colourItem', function ($q) use ($request) {
                $q->where('id', $request->glass_colour_id);
            });
        }

        if ($request->filled('accessory_type_id')) {
            $query->whereHas('accessoryItem.accessoryType', function ($q) use ($request) {
                $q->where('id', $request->accessory_type_id);
            });
        }
        if ($request->filled('aluminium_surface_id')) {
            $query->whereHas('accessoryItem.aluminumSurfaceFinish', function ($q) use ($request) {
                $q->where('id', $request->aluminium_surface_id);
            });
        }

        if ($request->filled('tool_type_id')) {
            $query->whereHas('toolItem.toolType', function ($q) use ($request) {
                $q->where('id', $request->tool_type_id);
            });
        }

        if ($request->filled('consumable_type_id')) {
            $query->whereHas('consumableItem.consumabletype', function ($q) use ($request) {
                $q->where('id', $request->consumable_type_id);
            });
        }

        $material = $query->get();

        $aluminumtype = AluminiumProfileType::all();
        $aluminumSurfaces = AluminumSurfaceFinish::all();
        $glassTypes = GlassType::all();
        $colour = ColourItem::all();
        $accessorytype = AccessoryType::all();
        $tooltype = ToolType::all();
        $consumable = ConsumableType::all();
        $dealers = Dealer::all();

        return view('admin.materials.stock.addstockpage', compact('material', 'dealers', 'aluminumSurfaces', 'glassTypes', 'colour', 'aluminumtype', 'consumable', 'accessorytype', 'tooltype'));
    }



    public function addstock(Request $request)
    {
        $material = Material::find($request->id);

        $priceColumn = null;
        $priceItemId = null;

        if ($material->material_type == 'อลูมิเนียม') {

            $aluminiumitem = AluminiumItem::find($material->aluminium_item_id);

            $aluminiumlength = AluminiumLength::where('aluminium_item_id', $aluminiumitem->id)
                ->where('length_meter', $request->length_meter)->first();

            if (!$aluminiumlength) {
                $aluminiumlength = AluminiumLength::create([
                    'aluminium_item_id' => $aluminiumitem->id,
                    'length_meter' => $request->length_meter
                ]);
            }

            $priceColumn = 'aluminium_length_id';
            $priceItemId = $aluminiumlength->id;
        } elseif ($material->material_type == 'กระจก') {

            $glassitem = GlassItem::find($material->glass_item_id);

            $glasssize = GlassSize::where('glass_item_id', $glassitem->id)
                ->where('width_meter', $request->width_meter)
                ->where('length_meter', $request->length_meter)
                ->where('thickness', $request->thickness)
                ->first();

            if (!$glasssize) {
                $glasssize = GlassSize::create([
                    'glass_item_id' => $glassitem->id,
                    'width_meter'   => $request->width_meter,
                    'length_meter'  => $request->length_meter,
                    'thickness'     => $request->thickness
                ]);
            }

            $priceColumn = 'glass_size_id';
            $priceItemId = $glasssize->id;
        } elseif ($material->material_type == 'อุปกรณ์เสริม') {

            $accessoryitem = AccessoryItem::find($material->accessory_item_id);

            $priceColumn = 'accessory_item_id';
            $priceItemId = $accessoryitem->id;
        } elseif ($material->material_type == 'เครื่องมือช่าง') {

            $toolitem = ToolItem::find($material->tool_item_id);

            $priceColumn = 'tool_item_id';
            $priceItemId = $toolitem->id;
        } elseif ($material->material_type == 'วัสดุสิ้นเปลือง') {
            $consumableitem = ConsumableItem::find($material->consumable_item_id);

            $priceColumn = 'consumable_item_id';
            $priceItemId = $consumableitem->id;
        }


        $qty = $request->quantity;
        $stcok = Price::where('material_id', $material->id)->sum('quantity');
        $sumqty = $stcok + $qty;


        $lotNumber = Price::where($priceColumn, $priceItemId)->where('dealer_id', $request->dealer_id)->count() + 1;

        $price = Price::create([
            'material_id' => $material->id,
            $priceColumn => $priceItemId,
            'dealer_id'  => $request->dealer_id,
            'price'      => $request->price,
            'quantity'   => $qty,
            'lot'        => 'ล็อตที่' . $lotNumber,
            'sumquantity' => $sumqty
        ]);


        MaterialLog::create([
            'material_id' => $material->id,
            'price_id'    => $price->id,
            'user_id'     => Auth::id(),
            'direction'   => 'in',
        ]);

        return redirect()->back()->with('success', 'เพิ่มจำนวนสต็อกสำเร็จ');
    }

    public function historystock(Request $request)
    {
        $query = MaterialLog::with([
            'material',
            'material.aluminiumItem.aluminiumType',
            'material.aluminiumItem.aluminumSurfaceFinish',
            'material.aluminiumItem.aluminiumLengths.price',
            'material.glassItem.glassType',
            'material.glassItem.colourItem',
            'material.glassItem.glassSize',
            'material.accessoryItem.accessoryType',
            'material.accessoryItem.aluminumSurfaceFinish',
            'material.accessoryItem.unit',
            'material.consumableItem.unit',
            'material.consumableItem.consumabletype',
            'material.toolItem.toolType',
            'price',
            'user'
        ]);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $materiallog = $query->orderBy('created_at', 'desc')->get();

        return view('admin.materials.stock.historystock', compact('materiallog'));
    }

    public function formeditsatock($id)
    {
        $price = Price::with('material')->find($id);
        $material = $price->material;
        $dealers = Dealer::all();

        $editLogs = StockEditLog::where('price_id', $price->id)->orderBy('created_at', 'desc')->get();

        return view('admin.materials.stock.formeditsatock', compact('price', 'material', 'dealers', 'editLogs'));
    }


    public function editstock(Request $request, $id)
    {
        $price = Price::findOrFail($id);
        $material = $price->material;
        $oldQuantity = $price->quantity;
        $oldPrice    = $price->price;

        $oldLength = $oldWidth = $oldThickness = null;

        if ($material->material_type == 'อลูมิเนียม') {

            $aluminiumlength = AluminiumLength::findOrFail($price->aluminium_length_id);

            $oldLength = $aluminiumlength->length_meter;

            $aluminiumlength->update([
                'length_meter' => $request->length_meter
            ]);

            $priceColumn = 'aluminium_length_id';
            $priceItemId = $aluminiumlength->id;
        } elseif ($material->material_type == 'กระจก') {

            $glasssize = GlassSize::findOrFail($price->glass_size_id);

            $oldWidth     = $glasssize->width_meter;
            $oldLength    = $glasssize->length_meter;
            $oldThickness = $glasssize->thickness;

            $glasssize->update([
                'width_meter'  => $request->width_meter,
                'length_meter' => $request->length_meter,
                'thickness'    => $request->thickness
            ]);

            $priceColumn = 'glass_size_id';
            $priceItemId = $glasssize->id;
        } elseif ($material->material_type == 'อุปกรณ์เสริม') {

            $accessoryitem = AccessoryItem::findOrFail($material->accessory_item_id);

            $priceColumn = 'accessory_item_id';
            $priceItemId = $accessoryitem->id;
        } elseif ($material->material_type == 'เครื่องมือช่าง') {

            $toolitem = ToolItem::findOrFail($material->tool_item_id);

            $priceColumn = 'tool_item_id';
            $priceItemId = $toolitem->id;
        } elseif ($material->material_type == 'วัสดุสิ้นเปลือง') {

            $consumableitem = ConsumableItem::findOrFail($material->consumable_item_id);

            $priceColumn = 'consumable_item_id';
            $priceItemId = $consumableitem->id;
        }

        $price->update([
            $priceColumn => $priceItemId,
            'dealer_id'  => $request->dealer_id,
            'price'      => $request->price,
            'quantity'   => $request->quantity
        ]);

        StockEditLog::create([
            'material_id' => $material->id,
            'price_id'    => $price->id,
            'user_id'     => Auth::id(),

            'old_quantity' => $oldQuantity,
            'new_quantity' => $request->quantity,

            'old_price' => $oldPrice,
            'new_price' => $request->price,

            'old_length_meter' => $oldLength,
            'new_length_meter' => $request->length_meter,

            'old_width_meter'  => $oldWidth,
            'new_width_meter'  => $request->width_meter,

            'old_thickness'    => $oldThickness,
            'new_thickness'    => $request->thickness,

            'reason' => $request->reason
        ]);

        return redirect()->back()->with('success', 'แก้ไขสต็อกสำเร็จ');
    }
}
