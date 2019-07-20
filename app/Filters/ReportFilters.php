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

    /**
     * Filtrování podnětů podle uživatele, který je vytvořil
     *
     * @param $id
     * @return mixed
     */
    public function user($id) {
        return $this->builder->where('user_id', $id);
    }

    /**
     * Určení stránky, která se má uživateli zobrazit respektive přeskočení počtu záznamů odpovídajících X stránkám.
     *
     * @param $page
     * @return mixed
     */
    public function page($page){
        return $this->builder->skip(($page)*env("PAGE_SIZE", 5));
    }

    /**
     * Zobraní aktuálních či uzavřených podnětů
     *
     * @param $value
     * @return mixed
     */
    public function closed($value){
        return ($value) ? $this->builder->where('state', '>=', 2) : $this->builder->where('state', '<', 2);
    }
}