<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;
use App\Category;
use App\Services\RoleRightService;

class ItemsController extends Controller
{

	public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }
	public function index(Request $request) 
	{

        $rolesPermissions = $this->roleRightService->hasPermissions("Items");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];

		$items = Item::where('isNotActive', 0)->latest()
			->where(function($query) use ($request){
				if($request->has('searchtxt') && $request->searchtxt != '') {
					$query->where('name', 'like', '%'.$request->searchtxt.'%' )
						->orWhere('code', 'like', '%'.$request->searchtxt.'%')
						->orWhere('category', 'like', '%'.$request->searchtxt.'%');
				}
			})
			->orderBy('id','DESC')
			->paginate(15);
        
		$categories = Category::all();			
        return view('pages.items.index', compact('items','categories','create', 'edit', 'delete'));
	}

	public function store(Request $request) {

        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'uom' => 'required',
            'category' => 'required',
            'unit_cost' => 'required',            
        ]);
		                
        Item::create([
            'code' => $request->code,
            'name' => $request->name,
            'uom' => $request->uom,
            'category' => $request->category,
            'unit_cost' => $request->unit_cost,
            'addedBy' => auth()->user()->name,
            'addedDate' => \Carbon\Carbon::now(),
            'isNotActive' => 0
        ]);

        return redirect()->route('items.index')->with('success', 'Item has been saved!!');	
	}    

    public function item_update(Request $request)
    {
    
        Item::find($request->nameid)->update([
            'code' => $request->code,
            'name' => $request->name,
            'uom' => $request->uom,
            'category' => $request->category,
            'unit_cost' => $request->unit_cost
        ]);

        return redirect()->route('items.index')->with('success', 'Item has been updated!!');
    }    


	public function getItem($itemname) {

		$item = Item::where('name', 'like', '%'.$itemname.'%')
			->orWhere('code', 'like', '%'.$itemname.'%')
			->get();

		return response()->json($item);

	}

}
