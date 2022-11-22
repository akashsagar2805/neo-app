<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gradient-to-r from-purple-500 to-pink-500 ">
    <div class="row">
        <h1 class="text-center  text-3xl lg:mt-3 mt-6  font-extrabold text-white">Near Earth Object</h1>
    </div>
    <div class="container  w-full mt-5 px-5 mx-auto py-2 my-auto lg:my-6">
        <div class="lg:w-full justify-center  shadow-lg bg-gray-200  rounded-lg p-6 mt-10 md:mt-0 ">
            <h1 class="text-purple-600 text-center mb-3 font-bold text-2xl">Select Date</h1>
            <form action="/">
                <div class="relative mb-4">
                    <label for="start_date" class="leading-7 font-extrabold text-sm text-gray-600">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full bg-white rounded border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    @error('start_date')
                        <div class="text-sm text-red-600 mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="relative mb-4">
                    <label for="end_date" class="leading-7 text-sm font-extrabold text-gray-600">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full bg-white rounded border border-gray-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    @error('end_date')
                        <div class="text-sm text-red-600 mt-1 ml-1">{{ $message }}</div>
                    @enderror
                </div>
                <button
                    class="text-white mt-3 bg-purple-600 border-0 py-1 px-4 focus:outline-none hover:bg-purple-700 rounded text-lg w-full">Submit</button>
                <a href="/"
                    class="text-white mt-3 bg-purple-600 border-0 px-4 py-1 focus:outline-none hover:bg-purple-700 rounded text-lg block w-full text-center">Reset</a>
            </form>

        </div>
    </div>

    @if (!empty($allResponse))
        <div class="container lg:flex mx-auto gap-6 px-5 py-2 my-auto lg:my-4 ">
            <div class="container lg:mt-0 mt-5 px-5  py-6 shadow-lg  rounded-lg  lg:mx-auto bg-gray-300">
                <h1 class="text-purple-700 text-center font-bold text-2xl">Fastest Asteroid</h1>
                <p
                    class="lg:text-sm text-xs flex-inline p-3 mb-3 text-center lg:w-auto w-3/3 mx-auto mt-4 bg-white font-bold">
                    ID {{ $allResponse['fastest']['id'] }}</p>
                <p class="lg:text-sm text-xs p-3 lg:w-auto w-3/3 text-center mx-auto mt-4 bg-white font-bold">
                    {{ $allResponse['fastest']['speed'] }} kmph

            </div>
            <div class="container lg:mt-0 mt-5 px-5  py-6 shadow-lg  rounded-lg mx-auto bg-gray-300">
                <h1 class="text-purple-700 text-center font-bold text-2xl">Closest Asteroid</h1>
                <p
                    class="lg:text-sm text-xs flex-inline p-3 mb-3 text-center lg:w-auto w-3/3 mx-auto mt-4 bg-white font-bold">
                    ID {{ $allResponse['closest']['id'] }}</p>
                <p class="lg:text-sm text-xs p-3 lg:w-auto w-3/3 text-center mx-auto mt-4 bg-white font-bold">
                    {{ $allResponse['closest']['distance'] }} km</p>

            </div>
            <div class="container lg:mt-0 mt-5 mb-4 lg:mb-0 px-5  py-6 shadow-lg  rounded-lg mx-auto bg-gray-300">
                <h1 class="text-purple-700 text-center font-bold text-2xl">Average Size</h1>
                <p
                    class="lg:text-md text-xs flex-inline p-3 mb-3 text-center lg:w-auto w-3/3 mx-auto mt-4 bg-white font-bold">
                    {{ $allResponse['avgsize'] }}.kmph</p>

            </div>
        </div>
    @endif

    @if (!empty($allResponse))
        <div class="container w-full  mx-auto gap-5 px-5 py-2  my-6">
            <canvas id="myChart" height="100px"
                class="bg-gray-200 rounded-lg lg:h-auto h-80 shadow  px-4 py-4 flex justify-center"></canvas>
        </div>
        <script>
            var labels = {{ Js::from($allResponse['chartData']['lables']) }}
            var values = {{ Js::from($allResponse['chartData']['values']) }}

            const data = {
                labels: labels,
                datasets: [{
                    label: 'NEO STATS',
                    backgroundColor: 'rgb(255, 89, 139)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: values,
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {}
            };

            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>
    @endif
</body>

</html>
