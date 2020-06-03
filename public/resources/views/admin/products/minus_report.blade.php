@extends('layouts.admin')

@section('content')

    <div class="btns-top">
        <form id="search">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="to">إسم المخزن :</label>
                    <select class="form-control" name="stock" id="stock">
                        <option readonly value="">إختر</option>
                        <option value="0"> المخزن الرئيسي</option>
                        <option value="1">المخزن الفرعى</option>
                        <option value="2">المحل</option>
                    </select>
                </div>
                {{--<div class="form-group col-md-3">--}}
                    {{--<label for="type">نوع الإذن :</label>--}}
                    {{--<select class="form-control" name="type" id="type">--}}
                        {{--<option readonly value="">إختر</option>--}}
                        {{--<option value="3"> إذن هالك</option>--}}
                        {{--<option value="4">إذن مرتحع</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
                <div class="form-group col-md-3">
                    <label for="to">إلى :</label>
                    <input class="form-control" type="date" name="toData"/>
                </div>
                <div class="form-group col-md-3">
                    <label for="to"> من :</label>
                    <input class="form-control" type="date" name="FromData"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <br/>
                    <input type="submit" class="btn btn-warning" value="بحث">
                </div>
                <div class="form-group col-md-3">
                    <label for="to">الأدمن :</label>
                    <select class="form-control" name="admin_id" id="admin_id">
                        <option readonly value="">إختر</option>
                        @foreach($admins as $admin)
                            <option value="{{$admin->id}}"> {{$admin->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <table class="table table-bordered" id="products-table">
            <thead>
            <tr>
                <th>كود المنتج</th>
                <th>إسم المنتج</th>
                <th>كمية الهالك</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <hr/>

    <div class="row">
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى البيع</span>: <span id="addTotal">{{$addTotal}}</span>
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>إجمالى الشراء</span>: <span id="sarfTotal">{{$sarfTotal}}</span>
            </span></bdi>
        </div>
        <div class="col-md-12">
            <bdi> <span>
                   <span>الربح </span>: <span id="total">{{$total}}</span>
            </span></bdi>
        </div>
    </div>

@stop

@push('scripts')
    <script>
        $(function () {
            $('#stock').select2();
            $('#type').select2();
            $('#admin_id').select2();
            loadTable();

            $('#search').on('submit', function (e) {
                e.preventDefault();
                if ($.fn.DataTable.isDataTable('#products-table')) {
                    $('#products-table').DataTable().destroy();
                }
                loadTable('?' + $(this).serialize());
                $.ajax({
                    url: '{{url('/minus/report')}}',
                    method: 'GET',
                    data: $(this).serialize() + '&json=1',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#addTotal').text(response.data.addTotal);
                        $('#sarfTotal').text(response.data.sarfTotal);
                        $('#total').text(response.data.total);
                    }
                });
            });
        });

        function loadTable(query = null) {
            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{url('/minus/report/data')}}' + ((query !== null) ? query : ''),
                columns: [
                    {data: 'code', name: 'code'},
                    {data: 'title_ar', name: 'title_ar'},
                    {data: 'halek', name: 'halek'},
                ],
                order: [[1, 'desc']]
            });
        }

    </script>
@endpush