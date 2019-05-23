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
        return $this->builder->where('user_id', $id);
    }

    public function page($page){
        return $this->builder->skip(($page)*env("PAGE_SIZE", 5));
    }

    public function closed($value){
        return ($value) ? $this->builder->where('state', 3) : $this->builder->where('state', '!=', 3);
    }
}