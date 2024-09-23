<x-layout>
  <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100  selection:bg-red-500 selection:text-black">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                @auth
                    <a href="{{ url('/admin') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif


    <section class="text-gray-600 body-font">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col">
                <div class="mt-16 h-1 bg-gray-200 rounded overflow-hidden">
                    {{-- <div class="w-32 h-full bg-indigo-500"></div> --}}
                </div>

                <div class="flex flex-wrap sm:flex-row flex-col py-6 mb-12">
                    <h1 class="sm:w-1/4 text-gray-900 font-medium title-font text-2xl mb-2 sm:mb-0">International House of Prayer Mission Statement</h1>

                    <p class="sm:w-2/3 leading-relaxed text-base sm:pl-10 pl-0">The IHOPKC Community exists to partner in the Great Commision by Advancing 24/7 prayer with worship and by proclaiming the beauty of Jesus and His glorious return.</p>
                </div>
            </div>
            <div class="flex flex-wrap sm:-m-4 -mx-4 -mb-10 -mt-4">
                <a href="https://www.ihopkc.org/sacredtrust/" class="p-4 md:w-1/3 sm:mb-0 mb-6">
                    {{-- <div class="rounded-lg h-64 overflow-hidden">
                        <img alt="content" class="object-cover object-center h-full w-full" src="https://dummyimage.com/1203x503">
                    </div> --}}
                    <h2 class="text-xl font-medium title-font text-gray-900 mt-5">Sacred Trust entry</h2>
                    {{-- <p class="text-base leading-relaxed mt-2">Enter your Sacred trust as before.</p> --}}
                </a>

                {{-- <div class="p-4 md:w-1/3 sm:mb-0 mb-6">
                    {{-- <div class="rounded-lg h-64 overflow-hidden">
                        <img alt="content" class="object-cover object-center h-full w-full" src="https://dummyimage.com/1099x904">
                    </div> --}}
                    {{-- <h2 class="text-xl font-medium title-font text-gray-900 mt-5">Sacred Trust Wall account method.</h2>
                    <p class="text-base leading-relaxed mt-2">Sacred Trust is rigiourious enough without have difficult computers to deal with. Learn more how to login and maintain your Sacred trust online</p>
                    <x-learn-more />
                </div>
                <div class="p-4 md:w-1/3 sm:mb-0 mb-6"> --}}
                    {{-- <div class="rounded-lg h-64 overflow-hidden">
                        <img alt="content" class="object-cover object-center h-full w-full" src="https://dummyimage.com/1205x505">
                    </div> --}}
                    {{-- <h2 class="text-xl font-medium title-font text-gray-900 mt-5">Future access</h2>
                    <p class="text-base leading-relaxed mt-2">Something new </p>
                    <x-learn-more />
                </div> --}}
            </div>
        </div>
    </section>
  </div>

</x-layout>
