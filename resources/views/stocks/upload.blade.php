<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Stock Prices</title>
    <!-- Correct Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white border border-gray-200 p-10 rounded-xl shadow-xl w-full max-w-md">
    <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Upload Stock Prices</h1>

    {{-- Success message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 p-3 mb-4 rounded-lg text-center">
        {{ session('success') }}
    </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-3 mb-4 rounded-lg text-center">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Upload Form --}}
    <form action="{{ url('stocks/upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="file" class="block text-gray-700 font-medium mb-2 text-center">Select Excel / CSV File</label>
            <input type="file" name="file" id="file"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                   accept=".xlsx,.csv">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all shadow-md hover:shadow-lg">
            Upload File
        </button>
    </form>

    <p class="mt-6 text-gray-500 text-sm text-center">
        Please upload an Excel or CSV file containing columns: <strong>Date</strong> and <strong>Price</strong>.
    </p>
</div>

</body>
</html>
