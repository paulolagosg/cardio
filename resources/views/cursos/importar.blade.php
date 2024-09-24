@extends('adminlte::page')

@section('title', 'Cursos')

@section('content_header')
<span class="text-xl text-bold text-dark">Cursos</span> <span> Importar</span>
@stop
@section('content')
<div class="container mt-5">

    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {!! session('error') !!}
    </div>
    @endif

    <div>
        <h2 class="text-center mb-5">Laravel Import Excel to Database</h2>

        <form id="excel-csv-import-form" method="POST" action="{{ url('import-excel-csv-file') }}" accept-charset="utf-8" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <a href="{{url('export-excel-csv-file')}}" class="btn btn-success mb-2">Export</a>
                <label for="fileInput" class="btn btn-primary mx-2">Import</label>
                <input type="file" id="fileInput" name="file" class="d-none" onchange="submitForn(this)">
                <a href="{{ url('download-import-template') }}" class="text-decoration-none">(Download the import template)</a>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Fax</th>
                    <th scope="col">Zip</th>
                </tr>
            </thead>
            <tbody>
                @if(count($users) > 0)
                @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->fax }}</td>
                    <td>{{ $user->zip }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">


<style>
    .page-item.active .page-link {
        background-color: #811916 !important;
        border: 1px solid #dee2e6;
        color: #fff !important;
    }

    .page-link {
        color: #811916 !important;
    }
</style>
@stop

@section('js')
<script src="/vendor/adminlte/dist/js/functions.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function submitForn(input) {
        document.getElementById('excel-csv-import-form').submit();
    }
</script>



@stop