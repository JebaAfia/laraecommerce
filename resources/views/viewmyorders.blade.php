<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table style="width:100%" >
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Product Title</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr style="text-align: center;">
                                    <th scope="row">{{ $order->id }}</th>
                                    <td >{{ $order->user->name }}</td>
                                    <td>{{ $order->receiver_address }}</td>
                                    <td>{{ $order->receiver_phone }}</td>
                                    <td>{{ $order->product->product_title }}</td>
                                    <td>{{ $order->product->product_price }}</td>
                                    <td><img src="{{ asset('products/' . $order->product->product_image) }}"
                                            alt="" style="height:100px; width:100px; display: block; margin-left: auto; margin-right: auto;"></td>
                                    <td>{{ $order->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
