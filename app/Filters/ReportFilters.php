<?php
/**
 * Created by PhpStorm.
 * User: kotas
 * Date: 22.03.2019
 * Time: 18:57
 */

namespace App\Filters;

use Illuminate\Http\Request;

class ReportFilters extends QueryFilters
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
        parent::__construct($request);
    }

    public function user($id) {
        return $this->builder->where('user_id', 'LIKE', $id);
    }

    public function skippedRecords($skipped){
        return $this->builder->skip($skipped);
    }
}