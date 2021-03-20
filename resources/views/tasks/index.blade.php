@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Lista de tareas</h1>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <input type="text" name="description" id="description">
        <input type="button" value="Crear" onclick="createTask();">
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>¿Pendiente?</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td>
                        {{ $task->id }}
                    </td>
                    <td>
                        {{ $task->description }}
                    </td>
                    <td>
                        {{ $task->is_done ? 'No' : 'Sí' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('layout_end_body')
<script>
    function createTask() {
        let theDescription = $('#description').val();
        $.ajax({
            url: '{{ route('tasks.store') }}',
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                description: theDescription
            }
        })
        .done(function(response) {
            $('#description').val('');
            $('.table tbody').append('<tr><td>' + response.id + '</td><td> ' + response.description + '</td><td>Sí</td></tr>');
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }
</script>
@endpush