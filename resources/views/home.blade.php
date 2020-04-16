@extends('layouts.app')

@section('content')

<style>
    li:hover {
        background: #333;
        color: white
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
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                        data-target="#exampleModalCenter" id="newItemBtn">
                        New Item
                    </button>






                    <ul class="list-group mt-3">
                        @foreach ($items as $item)
                        <li class="list-group-item d-flex justify-content-between" item_id="{{$item->id}}">
                            <span>{{$item->item_name}}</span><span>{{$item->item_refresh}}</span>

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
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('item.store')}}" method="POST" id="formData">
                    @csrf

                    <input type="hidden" id="item_id">

                    <div class="form-group">
                        <label for="item_name" class="">Item Name:</label>
                        <input type="text" name="item_name" id="item_name" class="form-control" autocomplete="off">
                        <span class="invalid-feedback" role="alert" id="item_nameError">
                            <strong></strong>
                        </span>
                    </div>


                    <div class="form-group">
                        <label for="item_refresh">Buy every:</label>
                        <select class="form-control" name="item_refresh" id="item_refresh">
                            @foreach ($data as $item)
                            <option value="+{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert" id="item_refreshError">
                            <strong></strong>
                        </span>
                    </div>

                    <button type="submit" id="formSubmit" class="btn btn-primary btn-block mt-3">Submit</button>
                </form>
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
    // document.querySelector('#formSubmit').addEventListener('click', addLogic);
    document.querySelector('#newItemBtn').addEventListener('click', addLogic);
    document.querySelector('.list-group').addEventListener('click', clickItems);
    document.querySelector('#formSubmit').addEventListener('click', addItemSubmit);
    document.querySelector('#saveChangesBtn').addEventListener('click', editItemSubmit);
    document.querySelector('#deleteBtn').addEventListener('click', deleteItem);


    

    function clickItems(e) {        
        if(e.target.parentElement.tagName === 'LI') {
            showEditModal(e.target.parentElement);
        } else if(e.target.tagName === 'LI') {
            showEditModal(e.target);
        }
    }


    function addLogic() {
        document.querySelector('.modal-footer').classList.add('d-none');
        document.querySelector('#formSubmit').classList.remove('d-none');
        document.querySelector('#exampleModalCenterTitle').textContent = 'Add New Item';
        clearInputAndError();
    }


    function showEditModal(list_item) {
        let item_id = list_item.getAttribute('item_id');
        clearInputAndError();

        $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});
        document.querySelector('.modal-footer').classList.remove('d-none');
        document.querySelector('#formSubmit').classList.add('d-none');
        document.querySelector('#exampleModalCenterTitle').textContent = 'Edit Item';
        document.querySelector('#item_id').value = item_id;
        document.querySelector('#item_name').value = list_item.children[0].textContent;
        document.querySelector('#item_refresh').value = list_item.children[1].textContent;
    }


    

    function addItemSubmit(e) {
        document.querySelector('#formSubmit').disabled = true;
        let formData = {
            item_name: document.querySelector('#item_name').value,
            item_refresh: document.querySelector('#item_refresh').value
        }
        const xhr = new XMLHttpRequest;
        xhr.open('POST', `{{route('item.store')}}`, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function() {
            document.querySelector('#formSubmit').disabled = false;
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);
                let item_name = document.querySelector('#item_name').value;
                let item_refresh = document.querySelector('#item_refresh').value;
                let myParent = document.querySelector('.list-group');
                let li = document.createElement('li');
                li.classList.add('list-group-item', 'd-flex', 'justify-content-between');
                li.setAttribute('item_id', data.item.id);
                li.innerHTML = `
                    <span>${item_name}</span><span>${item_refresh}</span>
                `;
                myParent.appendChild(li);
                $('#exampleModalCenter').modal('hide');
                clearInputAndError();
            } else if(this.status === 422) {
               validation(JSON.parse(this.responseText).errors)
            }
        }
        xhr.send(JSON.stringify(formData));
    }

    function editItemSubmit(e) {
        document.querySelector('#saveChangesBtn').disabled = true;
        let formData = {
            item_id: document.querySelector('#item_id').value,
            item_name: document.querySelector('#item_name').value,
            item_refresh: document.querySelector('#item_refresh').value
        }
        const xhr = new XMLHttpRequest;
        let raw = `{{route('item.update', ':id')}}`;
        raw = raw.replace(':id', document.querySelector('#item_id').value);
        xhr.open('PATCH', raw, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function() {
            document.querySelector('#saveChangesBtn').disabled = false;
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);
                document.querySelectorAll('.list-group-item').forEach(element => {
                    if(element.getAttribute('item_id') == data.item.id) {
                        console.log(element);
                        element.children[0].textContent = data.item.item_name;
                        element.children[1].textContent = data.item.item_refresh;
                    }
                })
                $('#exampleModalCenter').modal('hide');
                clearInputAndError();
            } else if(this.status === 422) {
                validation(JSON.parse(this.responseText).errors)
            }
        }
        xhr.send(JSON.stringify(formData));
    }

    function validation(errors) {
        document.querySelectorAll('.form-control').forEach(element => {
                element.classList.remove('is-invalid');
            })

        Object.keys(errors).forEach(element => {
            let myVar = document.querySelector(`#${element}`);
            let myVarError = document.querySelector(`#${element}Error`);
            
            myVar.classList.add('is-invalid')
            myVarError.innerHTML = `<strong>${errors[element]}</strong>`;
        })
    }


    function clearInputAndError(){
        document.querySelectorAll('.form-control').forEach(element => {
            element.classList.remove('is-invalid');
        })
        document.querySelector('#item_name').value = '';
        document.querySelector('#item_refresh').selectedIndex = 0;
    }

    function deleteItem() {
        const xhr = new XMLHttpRequest;
        let raw = `{{route('item.delete', ':id')}}`;
        raw = raw.replace(':id', document.querySelector('#item_id').value);
        xhr.open('DELETE', raw, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', `{{csrf_token()}}`);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function() {
            document.querySelector('#deleteBtn').disabled = false;
            if(this.status === 200) {
                let data = JSON.parse(this.responseText);

                document.querySelectorAll('.list-group-item').forEach(element => {
                    if(element.getAttribute('item_id') == data.item.id) {
                        element.remove();
                    }
                })
                $('#exampleModalCenter').modal('hide');
                clearInputAndError();
            }
        }
        xhr.send();
    }







</script>





@endsection