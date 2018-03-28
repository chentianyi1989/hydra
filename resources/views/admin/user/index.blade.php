@extends('admin.layouts.main')
@section('content')
    <section class="content">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">管理员列表</h3>
            </div>
            <div class="panel-body">
                @include('admin.user.filter', ['excel' => true])

                <table class="table table-hover">
                    <tr class="row">
                        <th class="col-lg-1 text-center">ID</th>
                        <th class="text-center">姓名</th>
                        <th class="col-lg-2 text-center">管理组</th>
                        <th class="col-lg-2 text-center">email</th>
                        <th class="col-lg-2 text-center">操作</th>
                    </tr>
                    
                </table>
                <div class="clearfix">
                    <div class="pull-left" style="margin: 0;">
                    <p>总共 <strong style="color: red">{{ $data->total() }}</strong> 条</p>
                    </div>
                    <div class="pull-right" style="margin: 0;">
                    {!! $data->appends(['name' => $name, 'role_id' => $role_id])->links() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
@section("after.js")
     @include('admin.layouts.delete',['title'=>'操作提示','content'=>'你确定要删除这个用户吗?'])
@endsection