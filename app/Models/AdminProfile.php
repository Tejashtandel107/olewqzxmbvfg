<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
	use HasFactory;

	protected $fillable = [
		'notify_on_client_create',
		'notify_on_client_update',
		'notify_on_client_delete',
		'notify_on_company_create',
		'notify_on_company_update',
		'notify_on_company_delete'
	];

	public function user()
	{
		return $this->morphOne(User::class, 'profile');
	}

	public function saveNotifications($notifcations=array(), $user)
	{
		$settings = [
			'notify_on_client_create',
			'notify_on_client_update',
			'notify_on_client_delete',
			'notify_on_company_create',
			'notify_on_company_update',
			'notify_on_company_delete'
		];
		$selected = array();
		foreach ($settings as $value) {
			$selected[$value]  = (in_array($value, $notifcations)) ? 1 : 0;
		}
		$user->profile()->update($selected);
	}
}
