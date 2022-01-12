<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['firstname', 'lastname', 'personal_email', 'picture', 'created_by', 'client_id',
        'position_id', 'mobile_no', 'location', 'working_hours', 'working_time_start',
        'working_time_end', 'break_time_start', 'break_time_end', 'salary', 'bank_account_no',
        'nationality', 'nic_no', 'gender', 'passport_no', 'birth_date', 'birth_place',
        'birth_country', 'martial_status', 'children', 'emergency_contact', 'visa_number',
        'working_permit_number', 'visa_expire_date', 'education_level', 'education_field',
        'educational_institute'];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function position()
    {
        return $this->hasOne(JobPosition::class, 'id', 'position_id')->with('department');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

}
