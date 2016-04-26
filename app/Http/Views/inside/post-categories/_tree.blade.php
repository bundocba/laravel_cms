@foreach ($list as $index => $row)
<tr class="table-tr">
    <td class = "table-td">
    </td>
    <td class="table-td">
        <p>{{  $index + 1 }}</p>
    </td>
    <td class="table-td">
        <p>{!! str_limit(strip_tags($row['name']), 50) !!}</p>
    </td>
    <td class="table-td">
        <p>{!! str_limit(strip_tags($row['_lft']), 50) !!}</p>
    </td>
    <td class="table-td">
        <p>{!! str_limit(strip_tags($row['_rgt']), 50) !!}</p>
    </td>
    <td class="table-td">
        <p>{!! $row['parent_id'] !!}</p>
    </td>
    <td class="table-td">
        <p>
            @if ($row['is_deleted']) 
            <span class="glyphicon glyphicon-minus-sign clr-red" aria-hidden="true"></span>
            @else
            <span class="glyphicon glyphicon-ok-sign clr-green" aria-hidden="true"></span>
            @endif
        </p>
    </td>
    <td class="table-td">
        <p>
            @if ($row['is_deleted'] )
            @if(isset($myPermission['admin.post-categories.undo']))
            <a href="{!! route('admin.post-categories.undo', $row['id']) !!}" class="btn btn-primary tooltips btn-xs" data-placement="top" data-original-title="Kích hoạt lại">
                <i class="fa fa-undo"></i>
            </a>
            @endif
            @if (isset($myPermission['admin.post-categories.postDeleted']))
            <button class="btn btn-danger tooltips btn-xs warning confirm" onclick="sweetID('{!! $row['id'] !!}')" data-placement="top" data-original-title="Xoá Vĩnh Viễn">
                <i class="fa fa-remove"><a href="{!! route('admin.post-categories.postDeleted', $row['id']) !!}"></a></i>
            </button>
            @endif

            @else
            @if (isset($myPermission['admin.post-categories.getEdit']))
            <a href="{!! route('admin.post-categories.getEdit', $row['id']) !!}" class="btn btn-primary tooltips btn-xs" data-placement="top" data-original-title="Sửa">
                <i class="fa fa-pencil"></i>
            </a>
            @endif

            @if (isset($myPermission['admin.post-categories.inactive']))
            <a href="{!! route('admin.post-categories.inactive', $row['id']) !!}" class="btn btn-danger tooltips btn-xs" data-placement="top" data-original-title="Khóa">
                <i class="fa fa-trash-o"></i>
            </a>
            @endif
            @endif
        </p>
    </td>
    <td class="table-td none">
        <p>{{ $row['created_at'] }}</p>
    </td>
    <td class="table-td none">
        <p>{{ $row['updated_at'] }}</p>
    </td>
</tr>
@if (isset($row['children']) )
@include('inside.post-categories._tree', [
'list' => $row['children'],
])
@endif
@endforeach


