<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contractor;
use App\Location;
use App\Transaction;
use App\TransactionDetail;
use App\ContractorLocations;
use App\Services\RoleRightService;
use DB;

class ContractorController extends Controller
{

    public function __construct(
		RoleRightService $roleRightService
	) {
		$this->roleRightService = $roleRightService;
	}
    public function index(Request $request)
    {
        $rolesPermissions = $this->roleRightService->hasPermissions("Contractors");

        if (!$rolesPermissions['view']) {
            abort(401);
        }

        $create = $rolesPermissions['create'];
        $edit = $rolesPermissions['edit'];
        $delete = $rolesPermissions['delete'];

        $contractors = Contractor::where('isActive', 1)
            ->where(function ($query) use ($request) {
                if ($request->has('searchtxt') && $request->searchtxt != '') {
                    $query->where('code', 'like', '%' . $request->searchtxt . '%')
                        ->orWhere('fname', 'like', '%' . $request->searchtxt . '%')
                        ->orWhere('lname', 'like', '%' . $request->searchtxt . '%')
                        ->orWhere('mname', 'like', '%' . $request->searchtxt . '%');
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $locations = Location::where('isActive', 1)->orderBy('name')->get();

        return view('pages.contractors.index', compact(
            'contractors',
            'locations',
            'create',
            'edit',
            'delete'
        ));
    }


    public function store(Request $request)
    {

        $data = $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'mname' => '',
            'code' => 'required'
        ]);

        Contractor::create($data);

        return redirect()->route('maintenance.contractors.index')->with('success', 'Contactor has been saved!!');
    }


    public function update($id, Request $request)
    {

        $contractor = Contractor::find($id);

        $data = $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'mname' => '',
            'code' => 'required'
        ]);

        $contractor->update($data);

        return redirect()->route('maintenance.contractors.index')->with('success', 'Contactor has been updated!!');
    }

    public function disable(Request $request) {

        $contractor = Contractor::find($request->disable_contractor_id);
        $insert_to_deleted = \App\ContractorDeleted::create([
            'fname' => $contractor->fname,
            'lname' => $contractor->lname,
            'mname' => $contractor->mname,
            'code' => $contractor->code,
            'created_at' => \Carbon\Carbon::now(),
            'deleted_by' => auth()->user()->name
        ]);
        
        $locations = ContractorLocations::where('contractor_id',$request->disable_contractor_id)->where('isActive',1)->update([
            'isActive' => 0, 'removedBy' => auth()->user()->name, 'removeDate' => \Carbon\Carbon::now()
        ]);

        $contractor->update(['deleted_by' => auth()->user()->name, 'isActive' => 0]);
        $contractor->delete();

        return back()->with('success','Successfully disabled contractor');

    }


    public function detachLocation($id, $locId)
    {

        $contractor = Contractor::find($id);

        $contractor->location()->updateExistingPivot(
            $locId,
            ['isActive' => 0, 'removedBy' => auth()->user()->name, 'removeDate' => \Carbon\Carbon::now()]
        );

        return 'yes';
        return redirect()->route('maintenance.contractors.index')->with('success', 'Location has been removed!!');
    }


    public function attachLocation($id, $locId)
    {

        $contractor = Contractor::find($id);

        // if( $c = $contractor->location()->where('location_id',$locId)->first() ) {
        // return redirect()->route('maintenance.contractors.index')->with('error_message', 'Location has been added already!!');
        // $contractor->location()->updateExistingPivot($locId, ['isActive' => 0]);
        // } else {
        $contractor->location()->attach($locId, ['addedBy' => auth()->user()->name]);
        // }
        return 'yes';
        return redirect()->route('maintenance.contractors.index')->with('success', 'Location has been added!!');
    }


    public function getContractorLocations($id)
    {

        $contractor = Contractor::find($id);
        if ($contractor)
            return response()->json($contractor->location()->where('locations.isActive', 1)->get());

        return response()->json([]);
    }


    public function getContractorExpiredDDETOS($id)
    {

        $transactions = TransactionDetail::where('contractor_id', $id)->whereNotNull('transaction_details.DDETOSrefNo')->where('transaction_details.DDETOSrefNo', '!=', '')->where('transaction_details.DDETOScommittedDate', '<', now())->where('transaction_details.DDETOSstatus', '!=', 'CLOSED')->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')->distinct()->pluck('transaction_details.DDETOSrefNo');
        // $transactions = TransactionDetail::where('DDETOSstatus', 'OPEN')->where('contractor_id', $id)->whereNotNull('transaction_details.DDETOSrefNo')->where('transaction_details.DDETOSrefNo', '!=', '')->where('transaction_details.DDETOScommittedDate', '<', now())->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')->distinct()->pluck('transaction_details.DDETOSrefNo');

        if ($transactions)
            return response()->json($transactions);

        return response()->json([]);
    }


    public function getExpiredDDETOSdetails($DDETOSrefNo)
    {

        $transaction = Transaction::with('details.itemz', 'details.transaction')->where('DDETOSrefNo', $DDETOSrefNo)->first();

        if ($transaction)
            return response()->json($transaction->details->whereNotNull('DDETOSrefNo')->where('DDETOSrefNo', '!=', '')->where('DDETOScommittedDate', '<', now())->values());

        return response()->json([]);
    }
    
}
