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
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr id="{{$task->id}}">
                    <td>
                        {{ $task->id }}
                    </td>
                    <td>
                        {{ $task->description }}
                    </td>
                    <td>
                        <input type="checkbox" id="state" name="pending" value="pending" onclick="updateTask({{ $task->id }});"  {{ $task->is_done ? 'checked' }}>
                        {{ $task->is_done ? 'Terminada' : 'Pendiente' }}
                    </td>
                    <td>
                        <input type="button" value="Borrar" onclick="deleteTask({{ $task->id }});" >
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
            $('.table tbody').append('<tr><td>' + response.id + '</td><td> ' + response.description + '</td><td><input type="checkbox" id="state" name="pending"/> Pendiente</td><td><input type="button" value="Borrar" onclick="deleteTask({{ $task->id }});" ></td></tr>');
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }

    
</script>
<script>
    function updateTask(id){
        let theState = $('#state').val();
        var url = "{{ route('tasks.update', 0)}}";
        var updUrl = url+id;
        
        $.ajax({
            url: updUrl,
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                is_done: theState
            }
        }).done((res) => {
            var row = document.getElementById(id);
            row.parentNode.updateChild(row);
            console.log(res);
        }).fail((jqXHR, res)=> {
            console.log('Fallido', res);
        })

    }
 
</script>
<script>
    function deleteTask(id){
        var url = "{{ route('tasks.destroy', 0)}}";
        var dltUrl = url+id;

        $.ajax({
            url: dltUrl,
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done((res) => {
            var row = document.getElementById(id);
            row.parentNode.removeChild(row);
            console.log(res);
        }).fail((jqXHR, res)=> {
            console.log('Fallido', response);
        })
    }</script>
@endpush