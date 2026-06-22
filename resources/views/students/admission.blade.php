@extends('layouts.app')

@section('title', 'Student Admission')

@section('content')

<div class="dashboard-card">

    <h3>Student Admission</h3>

    <hr>

    <form>

        <div class="row mb-3">

            <div class="col-md-6">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Class</label>
                <input type="text" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary">
            Save Data
        </button>

    </form>

</div>

@endsection