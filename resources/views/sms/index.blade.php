@extends('welcome')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Add Phone Number
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('store-message')}}">
                    @csrf
                    <div class="form-group">
                        <label>Enter Phone Number</label>
                        <input type="tel" class="form-control" placeholder="Enter Phone Number" name="phone_number">
                    </div>
                    <button type="submit" class="btn btn-primary">Register User</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-header">
                Send SMS message
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('custom-message')}}">
                    @csrf
                    <div class="form-group">
                        <label>Select users to notify</label>
                        <select multiple class="form-control" name="users[]">
                            @foreach ($users as $user)
                                <option>{{ $user->phone_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notification Message</label>
                        <textarea class="form-control" rows="3" name="body"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection