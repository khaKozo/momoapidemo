@extends('welcome')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Momo payment
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('momo-post')}}">
                    @csrf
                    <div class="form-group">
                        <label>Select momo type</label>
                        <select name="momo_type" class="form-control" >
                            <option value="1">Credit card</option>
                            <option value="2">QR code</option>
                            <option value="3">All</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Enter userId</label>
                        <input type="tel" class="form-control" placeholder="Enter user Id " name="user_id">
                    </div>

                    <div class="form-group">
                        <label>Enter amount</label>
                        <input type="number" class="form-control" placeholder="Enter amount" name="amount">
                    </div>
                    <button type="submit" class="btn btn-primary">Send money</button>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection