<?php

namespace App\Models;

use App\Traits\IsSortAndFilterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory;
    use SoftDeletes;
    use IsSortAndFilterable;

    protected $guarded = [
        'id',
    ];

    public function search($searchColumns, $searchValue, $exactMatch) {
        $operand = 'LIKE';
        $treatment = "%";
        if($exactMatch) {
            $operand = '=';
            $treatment = '';
        }
        $url = new Url;
        foreach($searchColumns as $col) {
            $url->where($col, $operand, $searchValue . $treatment);
        }

        return $url;
    }
}
