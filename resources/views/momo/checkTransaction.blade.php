@extends('welcome')

@section('content')
    <div class="row">

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    List transaction
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">TransactionId</th>
                                <th scope="col">OrderId</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <th scope="col">{{$payment->id}}</th>
                                    <th scope="col">{{$payment->transaction_id}}</th>
                                    <th scope="col">{{$payment->order_id}}</th>
                                    <th scope="col">{{$payment->amount}}</th>
                                    <th scope="col">{{$payment->status === 0 ? 'Done' : 'Waiting'}}</th>
                                </tr>                                
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    check tran saction
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('momo-check-post') }}">
                        @csrf
                        <div class="form-group">
                            <label>Enter transaction Id</label>
                            <input type="tel" class="form-control" placeholder="Enter transaction Id "
                                name="transaction-id">
                        </div>

                        <button type="submit" class="btn btn-primary">Check</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
