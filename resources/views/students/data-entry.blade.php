@extends('layouts.app')

@section('title', 'Data Entry')

@section('content')

<div class="dashboard-card">

    <h3>Data Entry</h3>

    <hr>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>10-A</td>
                <td>
                    <button class="btn btn-sm btn-warning">
                        Edit
                    </button>
                </td>
            </tr>
        </tbody>

    </table>

</div>

@endsection