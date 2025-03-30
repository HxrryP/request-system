@extends('layouts.user')

@section('content')
    <div class="px-4 py-6 md:px-6 md:py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            Pay for your document request
        </h1>

        <form action="{{ route('requests.pay', $documentRequest) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700">
                    Payment method
                </label>

                <select id="payment_method" name="payment_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="gcash">GCash</option>
                    <option value="maya">Maya</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Pay ₱{{ number_format($documentRequest->documentType->price, 2) }}
                </button>
            </div>
        </form>
    </div>
@endsection
