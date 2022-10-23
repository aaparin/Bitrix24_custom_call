<?php
namespace CallApplication\libs\helpers;
use CallApplication\libs\crest\CRestPlus as CRP;
use Noodlehaus\Config;

class BitrixApiHelper {


    public function __construct()
    {

    }

    public static function getSections($filter=[]){
        $conf = new Config(USER_SETTINGS);
        $items = CRP::call(
            'catalog.section.list',
            [
                'order' => [
                ],
                'filter' => [
                    'iblock_id'=>$conf->get('bxIblockID'),
                ],
                'start' => 0,
            ]
        );
        //TODO:Add filter, add pagination
        return count($items['result']['sections']>0)?$items['result']['sections']:false;
    }

    public static function updateSection(int $id, array $fields){
        $result = CRP::call(
            'catalog.section.update',
            [
                'id'=>$id,
                'fields'=>$fields
            ]
        );
        return $result;

    }

    public static function getItems($filter=[],$fields=array('id','name','purchasingPrice','xmlId','iblockId','iblockSectionId','active')){
        $conf = new Config(USER_SETTINGS);
        $items = CRP::call(
            'catalog.product.list',
            [
                'select'=>$fields,
                'order' => [
                    'id'=>'ASC'
                ],
                'filter' => [
                    'iblockId'=>$conf->get('bxIblockID'),
                ],
                'start' => 0,
            ]
        );
        foreach ($items['result']['products'] as $key=>$product){
            $items['result']['products'][$key]['prices']=self::getPrice($product['id']);
        }
        return count($items['result']['products']>0)?$items['result']['products']:false;
    }

    public static function getPrice($id){
        $prices = CRP::call(
            'catalog.price.get',
            [
                'id'=>$id
            ]
        );
        return isset($prices['result']['price'])?$prices['result']['price']:false;
    }

    public static function getWarehouseDocuments($filter=['STATUS'=>'Y']){
        $conf = new Config(USER_SETTINGS);
        $documents = CRP::call(
            'catalog.document.list',
            [
//                'select'=>array('id','iblockId','name'),
                'order' => [
                    'DATE_MODIFY'=>'DESC'
                ],
                'filter' => $filter,
                'start' => 0,
                'limit'=>100
            ]
        );
        foreach ($documents['result'] as $key=>$document){
            $documents['result'][$key]['ITEMS']=self::getItemsFromWarehouseDocument($document['ID']);
        }
        return $documents;
    }

    public static function getItemsFromWarehouseDocument($documentID){
        $items = CRP::call(
            'catalog.document.element.list',
            [
                'select'=>array('id','iblockId','name'),
                'order' => [
                    'ID'=>'ASC'
                ],
                'filter' => [
                    'DOC_ID'=>$documentID
                ],
                'offset' => 0,
                'limit'=>100
            ]
        );
        return $items;
    }

    public static function getMeasures(){
        $items = CRP::call(
            'catalog.measure.list',
            [
                'select'=>array('*'),
                'order' => [
                    'id'=>'ASC'
                ],
                'filter' => [],
                'start'  => 0

            ]
        );
        return isset($items['result']['measures'])?$items['result']['measures']:false;
        //return $items;
    }
    public static function getWarehouses(){
        $items = CRP::call(
            'catalog.store.list',
            [
                'select'=>array('*'),
                'order' => [
                    'id'=>'ASC'
                ],
                'filter' => [],
                'start'  => 0
            ]
        );
        return isset($items['result']['stores'])?$items['result']['stores']:false;
        //return $items;
    }

    public static function getCompanies(Array $filter=[]){
        $items = CRP::call(
            'crm.company.list',
            [
                'select'=>array('*'),
                'order' => [
                    'DATE_MODIFY'=>'DESC'
                ],
                'filter' => $filter,
                'start'  => 0
            ]
        );
        return isset($items['result'])?$items['result']:false;
        //return $items;
    }

    public static function getRequisiteByCompany($filter){
        $items = CRP::call(
            'crm.requisite.list',
            [
                'select'=>array('*'),
                'order' => [
                    'ID'=>'DESC'
                ],
                'filter' => $filter,
                'start'  => 0
            ]
        );
        //return isset($items['result'])?$items['result']:false;
        return $items;
    }

    public static function callApi(String $method,Array $filter=[],Array $select=['*'],Array $order=[]){
        $items = CRP::call(
            $method,
            [
                'select'=>$select,
                'order' => $order,
                'filter' => $filter,
                'start'  => 0
            ]
        );
        return isset($items['result'])?$items['result']:$items;
    }

    public static function callParamApi($method,$params=[]){
        $items = CRP::call(
            $method,$params
        );
        return isset($items['result'])?$items['result']:$items;
    }
}

?>