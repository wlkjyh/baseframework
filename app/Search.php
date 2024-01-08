<?php
/***
 * 模型搜索功能 trait
 */
namespace App;

trait Search{
    /***
     * @var array 搜索字段
     */
    public static $query_fields = [];

    /***
     * @param ...$args 设置搜索字段
     * @return Models\Role|Search
     */
    public static function searchField(...$args){
        foreach ($args as $arg){
            if (is_array($arg)){
                self::$query_fields = array_merge(self::$query_fields,$arg);
            }else{
                self::$query_fields[] = $arg;
            }
        }
        return new self;
    }


    /***
     * 获取搜索实例
     * @param $query_content 搜索内容
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getSearch($query_content){
        $query = self::query();
        foreach (self::$query_fields as $field){
            $query->orWhere($field,'like','%'.$query_content.'%');
        }
        return $query;
    }

    /***
     * 执行搜索
     * @param $query_content 搜索内容
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function runSearch($query_content){
        return $this->getSearch($query_content)->get();
    }

    /***
     * 搜索并分页
     * @param $query_content
     * @param $page_size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchPaginate($query_content,$page_size = 10){
        return $this->getSearch($query_content)->paginate($page_size);
    }

}
