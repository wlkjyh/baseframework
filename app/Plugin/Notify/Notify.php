<?php

namespace App\Plugin\Notify;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/***
 * 消息通知插件
 */
class Notify
{

    /***
     * 发送通知（Dashboard会10秒获取一次，获取到消息后会弹出消息）
     * @param int|object|null $user 用户ID、用户实例、null（当前用户）
     * @param string $message 通知内容
     * @return bool
     */
    public static function send(int|object|null $user = null, string $message = ''): bool
    {
        if (is_null($user)) {
            $user_id = Auth::user()->id;
        } else if (is_object($user)) {
            if ($user instanceof User) {
                $user_id = $user->id;
            } else {
                return false;
            }
        } else {
            $user_id = $user;
        }

        try {
            \App\Models\Notify::create([
                'user_id' => $user_id,
                'message' => $message,
            ]);
            return true;
        } catch (\Throwable $e) {
            return false;
        }

    }

    public static function getOne()
    {
        $user_id = Auth::user()->id;
        $notify = \App\Models\Notify::where('user_id', $user_id)->where('read', 0)->first();
        if ($notify) {
            $notify->read = 1;
            $notify->save();
            return $notify->message;
        }
        return '';
    }


}
