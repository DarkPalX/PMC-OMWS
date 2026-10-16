<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable;

class Transaction extends Model implements AuditableContract
{
    use Auditable;

	protected $guarded = [];

	protected $dates = ['addedDate', 'docDate', 'datetimeedited', 'DDETOSexpiryDate', 'DDETOScommittedDate'];



	public function details () 
	{
		return $this->hasMany('App\TransactionDetail','transaction_id', 'id');
	}
	

	public function contractor () 
	{
		return $this->belongsTo('App\Contractor');
	}
	

	public function locationz () 
	{
		return $this->belongsTo('App\Location','location_id');
	}

	public static function hasOpenDDETOS($id) {
		$transaction = self::with('details')->find($id);
		if (!$transaction) {
			return false; // Transaction not found
		}

		foreach ($transaction->details as $detail) {
			if ($detail->DDETOScommittedDate && $detail->DDETOSstatus !== 'CLOSED') {
				return true; // Found an open DDETOS detail
			}
		}

		return false; // All DDETOS details are closed
	}
	

}
