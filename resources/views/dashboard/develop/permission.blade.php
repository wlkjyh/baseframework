@extends('layouts.form',['title' => '权限管理器'])


@section('content')
    @alert(权限管理是应用到”auth.dashboard“中间件中自动进行页面鉴权的服务，使用该服务将”auth.dashboard“添加到路由的中间件中。)
    <div class="card-btns mb-2-5">
        <a class="btn btn-primary me-1 ajax-modal" link="{{url(route('develop.permission.create'))}}" title="创建URI"
           href="javascript:;">创建URI</a>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>URI</th>
            <th>请求方式</th>
            <th>上一次更新时间</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $val)

            <tr>
                <td>{{$val->id}}</td>
                <td>
                    {{$val->name}}
                </td>
                <td>
                    {{$val->uri}}
                </td>
                <td>
                    @if(in_array('*',$val->methods))
                        <span class="badge bg-primary">任何</span>
                    @else
                        @foreach($val->methods as $method)
                            <span class="badge bg-primary">{{$method}}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    {{$val->updated_at}}
                </td>
                <td>
                    {{$val->created_at}}
                </td>
                <td>


                    <div class="btn-group">
                        <button class="btn btn-default ajax-modal"  link="{{url(route('develop.permission.edit',['id'=>$val->id]))}}" title="编辑URI">编辑</button>

                        <button class="btn btn-default dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>

                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                            <li><a class="dropdown-item ajax-del" href="javascript:;"
                                   link="{{url(route('develop.permission.delete',['id'=>$val->id]))}}"
                                   ts="删除URI" text="你确定要删除这个URI吗？" style="color: red">删除</a></li>

                        </ul>


                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$rows->links('vendor.pagination.bootstrap-5')}}
@endsection
