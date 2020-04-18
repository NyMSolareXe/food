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


    /* li {
        position: relative;
    }

    .btnDelete {
        position: absolute;
        top: 50%;
        left: 20%;
        transform: translate(-20%, -50%);
    } */
</style>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="border-bottom: none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Your List</span>
                    <a href="{{route('home')}}" class="btn btn-primary text-white">Back to Items</a>
                </div>

                <div class="card-body d-flex justify-content-between">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif




                    <button class="btn btn-outline-danger" id="buttonClicked">Modify List</button>

                    <input type="hidden" name="item_id_array" id="item_id_array" value="{{$listItems}}">

                    <a href="{{route('shopping.index')}}" class="btn btn-outline-success">Begin Shopping</a>

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
                        <li class="list-group-item d-flex justify-content-between" item_id="{{$element->item->id}}">
                            <span>{{$element->item->item_name}}</span><span>{{$element->item->item_refresh}}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center" style="position: relative">
                <h5 class="modal-title" id="exampleModalCenterTitle">All Items</h5>
                <button class="btn btn-outline-danger" id="removeALlBtn"
                    style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%)">Remove
                    All</button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card" style="border-top: none">
                    <div class="card-body">
                        <ul class="list-group itemsListener">
                            @foreach ($items as $item)
                            <li class="list-group-item d-flex justify-content-between" item_id="{{$item->id}}">
                                <span>{{$item->item_name}}</span><span>{{$item->item_refresh}}</span>

                            </li>

                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-none pt-4">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-outline-danger btnDelete" id="deleteBtn">Delete</button>
                <button type="button" class="btn btn-outline-success" id="saveChangesBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelector('.itemsListener').addEventListener('click', clickItems);
    document.querySelector('#buttonClicked').addEventListener('click', colorItems);
    document.querySelector('#removeALlBtn').addEventListener('click', removeAll);


    let itemArray = document.querySelector('#item_id_array');

    function clickItems(e) {        
        if(e.target.parentElement.tagName === 'LI') {
            showEditModal(e.target.parentElement);
        } else if(e.target.tagName === 'LI') {
            showEditModal(e.target);
        }
    }

    function colorItems() {
        const xhr = new XMLHttpRequest;
        xhr.open('GET', `{{route('list.showOccupied')}}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');

        let formData = {
            item_array_data: document.querySelector('#item_id_array').value
        }

        xhr.onload = function() {
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);
                data.items.forEach(element => {
                    document.querySelector(`.itemsListener [item_id="${element.id}"]`).classList.add('bg-dark', 'text-white');
                })
                $('#exampleModalCenter').modal('show');
            }
        }
        xhr.send(JSON.stringify(formData));
    }


    function showEditModal(list_item) {
        
        let data = itemArray.value;
        let array = [];
        if(itemArray.value.length > 0) {
            array = data.split(',');
        }

        let index = list_item.getAttribute('item_id');
        
        if(array.indexOf(index) === -1) {
            array.push(index);
            list_item.classList.add('bg-dark', 'text-white');
        }else if(array.indexOf(index) >= 0) {
            array.splice(array.indexOf(index), 1);
            list_item.classList.remove('bg-dark', 'text-white');
        }
        itemArray.value = array;

    }



    function removeAll() {
        itemArray.value = '';
        let allListItems = document.querySelectorAll('.itemsListener li.bg-dark');
        allListItems.forEach(element => {
            element.classList.remove('bg-dark', 'text-white');
        })
        
    }



    window.addEventListener('load', function() {        
        $(document).on('hide.bs.modal','#exampleModalCenter', function () {
            modalClosed();
        });
    })

    function modalClosed() {

        const xhr = new XMLHttpRequest;
        xhr.open('POST', `{{route('list.store')}}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');

        let formData = {
            item_array_data: document.querySelector('#item_id_array').value
        }

        xhr.onload = function() {
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);

                document.querySelector('.list-group').innerHTML = '';


                data.items.forEach(element => {
                    let item_name = element.item_name;
                    let item_refresh = element.item_refresh;
                    let myParent = document.querySelector('.list-group');
                    let li = document.createElement('li');
                    li.classList.add('list-group-item', 'd-flex', 'justify-content-between');
                    li.setAttribute('item_id', element.id);
                    li.innerHTML = `
                        <span>${item_name}</span><span>${item_refresh}</span>
                    `;
                    myParent.appendChild(li);
                })
                
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