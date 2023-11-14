<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YclientsBranchTotalReport extends Model
{
    use HasFactory;

    protected $table = "yclients_branch_total_report";

    protected $fillable = [
        "company_id",
        "average_sum",
        "fullnesss",
        "new_client",
        "income_total",
        "loyalty",
        "additional_services",
        "sales",
        "income_goods",
        "comments_total",
        "comments_best",
        "start_date",
        "end_date",
    ];

    public static function addRecord($table) {
        YclientsBranchTotalReport::updateOrCreate(
            [
                "company_id" => $table["company_id"],
                "start_date" => $table["start_date"],
                "end_date"   => $table["end_date"],
            ],
            $table
        );
    }
}
