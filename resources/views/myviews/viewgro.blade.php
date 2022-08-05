@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Group
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bodered">
                            <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Group Description</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($group as $groups)
                                    <tr>
                                        <td>{{ $groups->name }}</td>
                                        <td>{{ $groups->description }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
