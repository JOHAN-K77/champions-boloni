@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-card">
    <h3>Dashboard</h3>
    <p>Welcome to The Champions - Little Champions School Administrative System.</p>
</div>

<div class="row">

    <div class="col-md-4">
        <div class="dashboard-card">
            <h5>Total Students</h5>
            <h2>152</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-card">
            <h5>Admissions</h5>
            <h2>38</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-card">
            <h5>Finance Records</h5>
            <h2>81</h2>
        </div>
    </div>

</div>

@endsection