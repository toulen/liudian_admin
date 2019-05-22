<?php
namespace Liudian\Admin\Logic;

use Liudian\Admin\Model\ChinaArea;

class ChinaAreaLogic
{

    public function getProvinces(){

        $provinces = ChinaArea::where(['parent_id' => -1])->get();

        return $provinces;
    }

    public function getArea($id){

        return ChinaArea::find($id);
    }

    public function getParent($id){

        return $this->getArea($id)->parent;
    }

    public function getChildren($id){

        return $this->getArea($id)->children;
    }
}