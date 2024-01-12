<?php

namespace App;

use Identicon;
use Illuminate\Http\Request;

class utils
{

    public static function arrayToObj(array $array): object
    {
        return json_decode(json_encode($array));
    }

    /***
     * 获取搜索
     * @param Request $request
     * @param array $field
     * @param string $ignore_data
     * @return array
     */
    public static function getWhereLike(Request $request, array $field = [], string $ignore_data = 'ALL'): array
    {

        $where = [];
        foreach ($field as $k => $v) {
            if ($request->has($k) && $request->input($k) != $ignore_data) {
                $where[] = [$v, 'like', '%' . $request->input($k) . '%'];
            }
        }
        return $where;

    }

    /***
     * 生成UUID
     * @return string
     */
    public static function generate_uuid4(): string
    {

        $chars = md5(uniqid(mt_rand(), true));
        return substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);

    }

    /***
     * 生成随机字符串
     * @param int $length
     * @param bool $lower
     * @param bool $upper
     * @return string
     */
    public static function generate_random(int $length = 6, bool $lower = false, bool $upper = false): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        if ($lower) {
            $random = strtolower($random);
        }
        if ($upper) {
            $random = strtoupper($random);
        }
        return $random;

    }


    /***
     * 生成头像
     * @return Identicon\Identicon
     */
    public static function Identicon(): Identicon\Identicon
    {
        return (new Identicon\Identicon());
    }


    /***
     * 创建以后的时间
     * @param $end_time
     * @return string
     */
    public static function created_at($end_time): string
    {
        $begin_time = date('Y-m-d H:i:s');
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //获取相差
        $timediff = strtotime($endtime) - strtotime($starttime);
        $days = intval($timediff / 86400);
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        $secs = $remain % 60;
        if ($mins == 0 && $hours == 0) {
            return $secs . '秒';
        }
        if ($hours == 0) {
            return $mins . '分钟';
        }
        if ($days == 0) {
            return $hours . '小时' . $mins . '分钟';
        }
        return $days . '日' . $hours . '小时';
    }

}


