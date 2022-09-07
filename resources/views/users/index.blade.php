@extends('layouts.app')
@section('content')

<div class="main-padding">
    <div class="table-card">
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <h1 class="heading mb-3">Users</h1>
            @canany(['user-add'])
            <a href="{{ route('users.create') }}">
                <button class="theme-btn me-2">Add New User</button>
            </a>
            @endcan
        </div>
        <div class="table-responsive">
            <table id="myTable" class="theme-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        //   d.approved = $('#approved').val(),
                        //  d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'action',
                        name: 'actions'
                    }
                ]
            });

            //   $('#approved').change(function(){
            //       table.draw();
            //   });

        });
    </script>
    <script>
        $('.data-table tbody').on('click', '.delete', function(e) {
                e.preventDefault();
                let that = jQuery(this);
                jQuery.confirm({
                    icon: 'fas fa-wind-warning',
                    closeIcon: true,
                    title: 'Are you sure!',
                    content: 'You can not undo this action.!',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        confirm: function() {
                            that.parent('form').submit();
                            //$.alert('Confirmed!');
                        },
                        cancel: function() {
                            //$.alert('Canceled!');
                        }
                    }
                });

        });
    </script>
@endpush