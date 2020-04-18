@extends('layouts.app')

@section('content')

<style>
    .list-group li:hover {
        /* background: #f4f4f4; */
        /* color: white */
    }

    select {
        /* for Firefox */
        -moz-appearance: none;
        /* for Chrome */
        -webkit-appearance: none;
    }
</style>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-bottom: none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Shopping</span>
                    <a href="{{route('list.index')}}" class="btn btn-outline-danger">Back to List</a>
                </div>

                <div class="card-body d-flex justify-content-between">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <input type="hidden" name="item_id_array" id="item_id_array" value="{{$items_picked}}">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-top: none">
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($list as $element)
                        <li class="list-group-item d-flex justify-content-between itemsListener {{$element->item_picked === 'Y' ? 'bg-success text-white' : ''}}"
                            list_id="{{$element->id}}">
                            <span>{{$element->item->item_name}}</span><span>{{$element->item->item_refresh}}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelector('.list-group').addEventListener('click', clickItems);

    let itemArray = document.querySelector('#item_id_array');

    function clickItems(e) {        
        if(e.target.parentElement.tagName === 'LI') {
            showEditModal(e.target.parentElement);
        } else if(e.target.tagName === 'LI') {
            showEditModal(e.target);
        }
    }


    function showEditModal(list_item) {
        
        let data = itemArray.value;
        let array = [];
        if(itemArray.value.length > 0) {
            array = data.split(',');
        }

        let index = list_item.getAttribute('list_id');
        
        
        if(array.indexOf(index) === -1) {
            array.push(index);
            list_item.classList.remove('bg-success', 'text-white');
        }else if(array.indexOf(index) >= 0) {
            array.splice(array.indexOf(index), 1);
            list_item.classList.add('bg-success', 'text-white');
        }
        itemArray.value = array;

        
        sendData(array);

    }



    function sendData(array) {

        const xhr = new XMLHttpRequest;
        xhr.open('PATCH', `{{route('shopping.update')}}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');

        let formData = {
            list_array_data: array
        }

        xhr.onload = function() {
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);
                console.log(data);
                
            }
        }
        xhr.send(JSON.stringify(formData));
    }



</script>





@endsection


{{-- @push('solarScripts')
<script>



</script>
@endpush --}}