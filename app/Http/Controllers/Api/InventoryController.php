<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        $inventaris_count = Inventory::count();
        $inventaris = Inventory::all();
        
        if (!$users || !$inventaris_count) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        } else {
            return response()->json([
                'status' => true,
                'list_user' => $users,
                'jumlah_inventaris' => $inventaris_count,
                'list_inventory' => $inventaris,
                'message' => 'Data ditemukan'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable',
            'quantity' => 'required|integer',
            'price' => 'required'
        ], [
            'name.required' => 'nama inventaris harus diisi',
            'quantity.required' => 'stok harus diisi',
            'quanity.integer' => 'masukkan data stok hanya angka saja',
            'price.required' => 'harga harus diisi'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->errors()
            ], 404);
        }

        //Check data inventory from databases where name request it same in database
        $valueInventory = Inventory::where('name', $request->name)->first();

        if ($valueInventory) {
            //add up quantity when data already in database
            $valueInventory->quantity += $request->quantity;
            $valueInventory->save();

            return response()->json([
                'status' => true,
                'data' => $valueInventory->id,
                'message' => 'stok sudah ditambahkan'
            ]);
        } else {
            //add new data inventory
            $inventories = new Inventory();
            $inventories->name = $request->name;
            $inventories->description = $request->description;
            $inventories->quantity = $request->quantity;
            $inventories->price = $request->price;
            $inventories->save();

            //validation when data inventories finsh add
            if ($inventories) {
                return response()->json([
                    'status' => true,
                    'data' => $inventories->id,
                    'message' => 'data inventaris berhasil di tambahkan'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'data tidak berhasil di tambahkan'
                ], 404);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $inventoris = Inventory::find($id);
            return response()->json([
                'status' => true,
                'data' => new InventoryResource($inventoris),
                'message' => 'data inventory ditemukan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'data inventory tidak dapat ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //code...
            $inventories = Inventory::find($id);
            $inventories->name = $request->name;
            $inventories->description = $request->description;
            $inventories->quantity = $request->quantity;
            $inventories->price = $request->price;
            $inventories->save();

            return response()->json([
                'status' => true,
                'data' => $inventories->id,
                'message' => 'Inventory berhasil di update'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak dapat diupdate'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $inventories = Inventory::find($id);
            $inventories->delete();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ], 404);
        }
    }
}
