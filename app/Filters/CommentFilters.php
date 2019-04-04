<?php


namespace App\Filters;

use Illuminate\Http\Request;

class CommentFilters extends QueryFilters
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
        parent::__construct($request);
    }

    public function user($id) {
        return $this->builder->where('user_id', 'LIKE', $id);
    }

    public function skip($skipped){
        return $this->builder->skip($skipped);
    }
}