<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    //
    public static function getTopData($arr,$pid='pid',$top=0)
    {
        //$arr = collect($arr);
        $data = collect();
        $arr->each(function ($item, $key) use($pid,$top,$data){
            if($item[$pid]==$top){
                $data->push($item);
            }
        });
        return $data;
    }

    public static function getLevelData($arr,&$topArr,$pid='pid')
    {
        $topArr->each(function ($item, $key) use($arr,$pid){
            $children = collect();
            $arr->each(function ($item2, $key2) use($item,$children, $key,$pid){
                if($item->id==$item2[$pid]){
                    $children->push($item2);
                }
            });
            $item->children = $children;
            self::getLevelData($arr,$item->children,$pid);
        });
    }

    public static function getTopMenu($arr)
    {
        $data = collect();
        $num = 1;
        $arr->each(function ($item, $key) use(&$num,$data){
            if($item->pid==0){
                $item->index = (string)$num;
                $data->push($item);
                $num++;
            }
        });
        return $data;
    }

    public static function getLevelMenu($arr,&$topArr,$pid='pid')
    {
        $topArr->each(function ($item, $key) use($arr,$pid){
            $children = collect();
            $num = 1;
            $arr->each(function ($item2, $key2) use($item,$children,&$num, $key,$pid){
                if($item->id==$item2[$pid]){
                    $item2->index = $item->index.'-'.$num;
                    $children->push($item2);
                    $num++;
                }
            });
            $item->child = $children;
            self::getLevelMenu($arr,$item->child,$pid);
        });
    }

    public static function getTopMenu2($arr)
    {
        $data = collect();
        $num = 1;
        $arr->each(function ($item, $key) use(&$num,$data){
            if($item->pid==0){
                $item->label = $item->name;
                $data->push($item);
                $num++;
            }
        });
        return $data;
    }

    public static function getLevelMenu2($arr,&$topArr,$pid='pid')
    {
        $topArr->each(function ($item, $key) use($arr,$pid){
            $children = collect();
            $num = 1;
            $arr->each(function ($item2, $key2) use($item,$children,&$num, $key,$pid){
                if($item->id==$item2[$pid]){
                    $item2->label = $item2->name;
                    $children->push($item2);
                    $num++;
                }
            });
            $item->children = $children;
            self::getLevelMenu2($arr,$item->children,$pid);
        });
    }


}
