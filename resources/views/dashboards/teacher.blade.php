<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard Guru') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Flash success message --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('success') }}</div>
                    @endif

                    {{-- General error from controller (friendly DB errors) --}}
                    @if($errors->has('general'))
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">{{ $errors->first('general') }}</div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Hantar Aduan Kerosakan</h3>
                    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @include('complaints._form_fields')

                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded">Hantar Aduan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-3">Sejarah Aduan Anda</h3>
                @php $myComplaints = \App\Models\Complaint::where('reported_by', auth()->id())->latest()->take(10)->get(); @endphp
                @if($myComplaints->count())
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-4">
                            <ul>
                                @foreach($myComplaints as $c)
                                    <li class="py-2 border-b">#{{ $c->complaint_number }} — {{ Str::limit($c->description, 80) }} <span class="text-sm text-gray-500">({{ ucfirst($c->status) }})</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Belum ada aduan dihantar oleh anda.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
