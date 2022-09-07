@extends('layouts.app')
@section('content')
    <div class="main-padding">
        <div class="table-card">
            <h1 class="heading">All Activity</h1>
            <div class="table-responsive">
                <table id="activity-log" class="theme-table">
                    <thead>
                        <tr>
                            <th>Log Name</th>
                            <th>description</th>
                            <th>Causer</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($activities as $activity)
                            <tr>
                                <td>
                                    {{$activity->log_name}}
                                </td>
                                <td>
                                    {{$activity->description}}
                                </td>
                                <td>
                                    By {{ $activity->causer->name ?? '' }}<br/>
                                </td>
                                <td>

                                    {{ $activity->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                    {{-- <tfoot>
                        <tr>
                            <td colspan="6">
                                {{$activities->links()}}
                            </td>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(function() {
        var table = $('#activity-log').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('activity-log.index') }}",
                data: function(d) {
                    //   d.approved = $('#approved').val(),
                    //  d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {
                    data: 'log_name',
                    name: 'log_name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'causer.name',
                    name: 'causer.name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },

            ]
        });
        //   $('#approved').change(function(){
        //       table.draw();
        //   });

    });
</script>
    <script>
        jQuery(document).ready(function(){
            $('.delete').on('click', function(e){
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
                        confirm: function () {
                            that.parent('form').submit();
                            //$.alert('Confirmed!');
                        },
                        cancel: function () {
                            //$.alert('Canceled!');
                        }
                    }
                });
            })
        })

    </script>
@endpush
