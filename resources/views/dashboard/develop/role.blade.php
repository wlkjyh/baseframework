@extends('layouts.form',['title' => '角色设计'])


@section('content')
    <div class="card-btns mb-2-5">
        <a class="btn btn-primary me-1 ajax-modal" link="{{url(route('develop.role.create'))}}" title="创建角色"
           href="javascript:;">创建角色</a>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
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
                    {{$val->updated_at}}
                </td>
                <td>
                    {{$val->created_at}}
                </td>
                <td>
                    @if($val->name != 'admin' && $val->id != 1)

                        <div class="btn-group">
                            <button class="btn btn-default ajax-modal" link="{{url(route('develop.role.edit',['id'=>$val->id]))}}" title="编辑角色">编辑</button>

                            <button class="btn btn-default dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>

                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                <li><a class="dropdown-item ajax-del" href="javascript:;"
                                       link="{{url(route('develop.role.delete',['id'=>$val->id]))}}"
                                       ts="删除角色" text="你确定要删除这个角色吗？" style="color:red">删除</a></li>

                            </ul>


                        </div>
                    @else
                        <div class="btn-group">
                            <button class="btn btn-default" disabled>编辑</button>

                            <button class="btn btn-default dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>

                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">


                            </ul>


                        </div>
                    @endif




                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{$rows->links('vendor.pagination.bootstrap-5')}}
@endsection
