<?php
namespace B1Integration\libs\helpers;
use B1Integration\libs\helpers\Helpers;


class UserSettings {
    public static function setData($conf,string $key, $value=''){
        $conf->set($key,$value);
        $conf->toFile(USER_SETTINGS);
    }

    public static function saveAllData($conf,$post){
        unset($post['type']);
        foreach ($post as $key=>$value){
            $conf->set($key,$value);
        }
        $conf->toFile(USER_SETTINGS);
    }

    public static function saveMeasureData($conf,$post){
        unset($post['type']);
        $matches=json_decode($conf->get('measureMatch'));
        $matches[]=array(
            'uid'=>uniqid(),
            'b1'=>$post['b1m'],
            'bitrix'=>$post['bitrixm']
        );
        $conf->set('measureMatch',Helpers::toJson($matches));
        $conf->toFile(USER_SETTINGS);

    }

    public static function delMeasureData($conf,$post){
        unset($post['type']);
        $matches=json_decode($conf->get('measureMatch'));
        foreach ($matches as $match){
            if($match->uid!=$post['uid']){
                $newMatch[]=$match;
            }
            //print_r($match);
        }
        $conf->set('measureMatch',Helpers::toJson($newMatch));
        $conf->toFile(USER_SETTINGS);
    }

    public static function saveWarehouseData($conf,$post){
        unset($post['type']);
        $matches=json_decode($conf->get('warehouseMatch'));
        $matches[]=array(
            'uid'=>uniqid(),
            'b1'=>$post['b1w'],
            'bitrix'=>$post['bitrixw']
        );
        $conf->set('warehouseMatch',Helpers::toJson($matches));
        $conf->toFile(USER_SETTINGS);
    }

    public static function delWarehouseData($conf,$post){
        unset($post['type']);
        $matches=json_decode($conf->get('warehouseMatch'));
        foreach ($matches as $match){
            if($match->uid!=$post['uid']){
                $newMatch[]=$match;
            }
            //print_r($match);
        }
        $conf->set('warehouseMatch',Helpers::toJson($newMatch));
        $conf->toFile(USER_SETTINGS);
    }
}
?>