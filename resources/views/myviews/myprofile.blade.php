@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>My Profile
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bodered">
                            <thead>
                                <tr>
                                    <th>Fullname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($user as $orders)
                                    <tr>
                                        <td>{{ $orders->fullname }}</td>
                                        <td>{{ $orders->username }}</td>
                                        <td>{{ $orders->email }}</td>
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
