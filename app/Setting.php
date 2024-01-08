<?php

namespace App;

use App\Models\setting as Config;

class Setting
{
    public static $config_cache = [];

    /***
     * 创建或更新配置
     * @param string $key
     * @param string|null $value
     * @return string|null
     */
    public static function set(string $key, string|null $value = null): string|null
    {
        Config::updateOrCreate(
            ['k' => $key],
            ['k' => $key, 'v' => $value]
        );
        return $value;
    }

    /***
     * 创建或更新配置
     * @param string $key
     * @param string|null $value
     * @return string|null
     */
    public static function setting(string $key, string|null $value = null): string|null
    {
        return self::set($key, $value);
    }


    /***
     * 获取配置
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public static function get(string $key, string|null $default = null): string|null
    {
        if (isset(self::$config_cache[$key])) {
            return self::$config_cache[$key];
        }
        $config = Config::where('k', $key)->value('v') ?? $default;

        $match = preg_match_all('/{(.*)}/', $config, $matches);
        if ($match) {
            foreach ($matches[1] as $k => $v) {
                $query_key = trim($v);
                $query_value = Config::where('k', $query_key)->value('v');
                $config = str_replace($matches[0][$k], $query_value, $config);
            }
        }
        // 函数替换
        $match = preg_match_all('/\@(.*)\@/', $config, $matches);
        foreach($matches[1] as $k => $v){
            $config = str_replace($matches[0][$k], eval("return $v;"), $config);
        }

        $change = ['true' => true, 'false' => false];
        if (in_array(strtolower($config), array_keys($change))) {
            $config = $change[$config];
        }


        self::$config_cache[$key] = $config;
        return $config;
    }

    /***
     * 获取配置
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public static function getSetting(string $key, string|null $default = null): string|null
    {
        return self::get($key, $default);
    }

    public static function has(string $key, $func = null): bool
    {
        $setting = Config::where('k', $key)->first();
        if ($setting) {
            return true;
        } else {
            if ($func) {
                $func();
            }
            return false;
        }
    }

    public function checking(string $key, $func = null): bool
    {
        return self::has($key, $func);
    }

    public static function forget(string $key): bool
    {
        return Config::where('k', $key)->delete();
    }

    /**
     * 删除配置
     * @param string $key
     * @return bool
     */
    public static function delete(string $key): bool
    {
        return self::forget($key);
    }


    /***
     * 自动保存
     * @param array $field  包含的字段数组
     * @param string $submitUrl 保存提交地址
     * @param bool $reload 保存成功后是否刷新页面
     * @param bool $closeModal 保存成功后是否关闭bootstrap模态框
     * @param string $saveButtonId 保存按钮的id
     * @param string $OkTips 保存成功后的提示
     * @param int $ReturnOkCode json中执行成功后的code
     * @param string $faildMessageFiled 执行失败后的提示字段
     * @param string $submitType 提交方式：post/get
     * @return string
     */
    public static function autoSave(array $field, string $submitUrl, bool $reload = false, bool $closeModal = false, string $saveButtonId = 'save', string $OkTips = '保存成功', int $ReturnOkCode = 200, string $faildMessageFiled = 'msg', string $submitType = 'post')
    {

        $h = '';
        $d = '_token:"' . csrf_token() . '",';
        foreach ($field as $k => $v) {
            if (str_starts_with($k, 'name:')) {
                $k = str_replace('name:', '', $k);
                $select = " var {$k} = $('input[name={$k}]:checked').val();";
            }
            if(str_starts_with($k, 'custom:[')){
            //    custom:[is_open,1]
                $params = preg_replace('/custom:\[(.*)\]/', '$1', $k);
                list($k, $v) = explode(',', $params);
                $select = " var {$k} = $('[$k={$v}]').val();";
            }
            else {
                $select = " var {$k} = $('#{$k}').is(':checkbox') ? ($('#{$k}').is(':checked') ? 1 : 0) : $('#{$k}').val();";
            }

            $h .= <<<HTML
//            var {$k} = $('#{$k}').val();-
           {$select}
           if('{$k}' != 'ids'){
            if({$k} == '' || {$k} == null){
               // 判断是不是checkbox
                if($('#{$k}').is(':checkbox') == false){
                    layer.msg('{$v}不能为空');
                    return false;
                }
            }}
HTML;
            $d .= <<<HTML
            {$k}:{$k},
HTML;

        }
        $ifReload = ($reload) ? 'location.reload();' : '';
        //data-bs-dismiss
        $ifCloseBootstrapModal = ($closeModal) ? '$("[data-bs-dismiss=modal]").click();' : '';

        $html = <<<HTML
<script>
        $('#{$saveButtonId}').click(function(){
{$h}
            var load = layer.msg('正在进行...', {icon: 16,shade: 0.3,time:0});
            $.{$submitType}({
                url:'{$submitUrl}',
                data:{
                    {$d}
                },
                success:function(data){
                    layer.close(load);
                    if(data.code == {$ReturnOkCode}){
                        layer.msg('{$OkTips}');
                        setTimeout(function(){
                            {$ifReload}
                            {$ifCloseBootstrapModal}
                        },500);

                    }else{
                        layer.msg(data.{$faildMessageFiled});
                    }
                },
                error : function(data) {
                    layer.close(load);
                    layer.msg('网络错误：' + data.status + '，请稍后重试');
                }
            });
        });
</script>

HTML;

        return $html;

    }


}
