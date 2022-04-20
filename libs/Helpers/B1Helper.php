<?php
namespace B1Integration\libs\helpers;

use B1Integration\libs\B1Class\B1;

class B1Helper {
    public static function getSections(B1 $b1,Array $filter=[]){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $data['filter']=$filter;
        $response = $b1->request('reference-book/item-groups/list', $data);
        $answer= $response->getContent();
        //@TODO:Сделать рекурсию для получения значений более 100
        return count($answer['data']>0)?$answer['data']:false;
    }

    public static function addSection(B1 $b1,string $name,bool $active=true){
        $data = [
            'name' => $name,
            'isActive' => $active
        ];
        $response = $b1->request('reference-book/item-groups/create', $data);
        $answer= $response->getContent();
        return isset($answer['data']['id'])?$answer['data']['id']:false;
    }

    public static function getItems(B1 $b1,Array $filter=[]){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $data['filter']=$filter;
        $response = $b1->request('reference-book/items/list', $data);
        $answer= $response->getContent();
        //@TODO:Сделать рекурсию для получения значений более 100
        return count($answer['data']>0)?$answer['data']:false;
    }

    public static function addItem(B1 $b1,$data){

        $response = $b1->request('reference-book/items/create', $data);
        $answer= $response->getContent();
        //return $answer;
        return isset($answer['data']['id'])?$answer['data']['id']:false;
    }

    public static function getMeasures(B1 $b1){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $response = $b1->request('reference-book/measurement-units/list', $data);
        $answer= $response->getContent();
        return isset($answer['data'])?$answer['data']:false;
    }

    public static function getWarehouseDocuments(B1 $b1,Array $filter=[]){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $data['filter']=$filter;
        $response = $b1->request('reference-book/items/list', $data);
        $answer= $response->getContent();
        //@TODO:Сделать рекурсию для получения значений более 100
        return count($answer['data']>0)?$answer['data']:false;
    }

    public static function getWarehouses(B1 $b1,Array $filter=[]){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $data['filter']=$filter;
        $response = $b1->request('reference-book/warehouses/list', $data);
        $answer= $response->getContent();
        //@TODO:Сделать рекурсию для получения значений более 100
        return count($answer['data']>0)?$answer['data']:false;
    }

    public static function getClientList(B1 $b1,Array $filter=[]){
        $data = [
            'page' => 1,
            'rows' => 100,
            'sidx' => 'name',
            'sord' => 'asc',
        ];
        $data['filter']=$filter;
        $response = $b1->request('clients/list', $data);
        $answer= $response->getContent();
        //@TODO:Сделать рекурсию для получения значений более 100
        return count($answer['data']>0)?$answer['data']:false;
    }

    public static function getB1CompanyFields(){
        $fields = array(
            ''
        );
        return $fields;
    }

}

?>