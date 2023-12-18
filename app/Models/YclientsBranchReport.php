<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YclientsBranchReport extends Model
{
    use HasFactory;

    protected $table = "yclients_branch_report";

    protected $fillable = [
        "company_id",
        "staff_id",
        "name",
        "specialization",
        "average_sum",
        "fullness",
        "new_client",
        "return_client",
        "total_client" ,
        "income_total",
        "income_goods",
        "comments_total",
        "comments_best",
        "loyalty",
        "sales",
        "additional_services",
        "sum",
        "start_date",
        "end_date",
    ];

    public static function addRecord($table) {
        self::updateOrCreate(
            [
                "company_id" => $table["company_id"],
                "staff_id"   => $table["staff_id"],
                "start_date" => $table["start_date"],
                "end_date"   => $table["end_date"],
            ],
            $table
        );
    }
}
