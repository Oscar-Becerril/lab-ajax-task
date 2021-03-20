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
                    @forelse ($tasks as $task)
                    <tr id="{{$task->id}}">
                        <td>
                            {{ $task->id }}
                        </td>
                        <td>
                            {{ $task->description }}
                        </td>
                        <td>
                        @if($task->is_done==1)
                            <input type="checkbox" id="state" name="pending" value="pending" onclick="updateTask({{ $task->id }}, {{$task->is_done}});" checked>
                        @else
                            <input type="checkbox" id="state" name="pending" value="pending" onclick="updateTask({{ $task->id }}, {{$task->is_done}});" >
                            @endif
                            {{ $task->is_done ? 'Terminada' : 'Pendiente' }}
                        </td>
                        <td>
                            <input type="button" value="Borrar" onclick="deleteTask({{ $task->id }});" >
                        </td>
                    </tr>
                    @empty
                    @endforelse
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
            console.log(response);
            $('#description').val('');
            $('.table tbody').append('<tr id='+ response.id +'><td>' + response.id + '</td><td> ' + response.description + '</td><td><input type="checkbox" id="state" name="pending" onclick="updateTask('+ response.id +','+0+');"/> Pendiente</td><td><input type="button" value="Borrar" onclick="deleteTask('+response.id+');" ></td></tr>');
            
        })
        .fail(function(jqXHR, response) {
            console.log('Fallido', response);
        });
    }
    
</script>
<script>
    function updateTask(id, req_done){
        var done;
        if(req_done == 1){
            done = 0;
        }else{
            done = 1;
        }
        var url = "{{ route('tasks.update',0)}}";
        var updUrl = url+id;
        //console.log(theState + " " + updUrl);
        
        $.ajax({
            url: updUrl,
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                is_done : done
                
            }
        }).done((res) => {
            //var isChecked = document.getElementById(id).checked;
            if(res.is_done==0){
                var is_done_text = 0;
                var status= ' Pendiente';
                var checked_text = '';
            }else{
                var is_done_text = 1;
                var status= ' Terminada';
                var checked_text = 'checked';
            }
            
            var row = document.getElementById(id);
            $( row ).replaceWith( "<tr id='"+ res.id +"'><td>"+ res.id +"</td><td>"+ res.description +"</td><td><input type='checkbox' id='state' name='pending' onclick='updateTask("+ res.id +","+ is_done_text +");' "+checked_text+"/>"+ status +"</td><td><input type='button' value='Borrar' onclick='deleteTask("+res.id+");' ></td></tr>");
            
            
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