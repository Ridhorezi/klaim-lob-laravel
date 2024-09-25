<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Klaim Per LOB') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">

                    <form action="/klaim/import" method="POST" enctype="multipart/form-data" class="mb-4 flex">
                        @csrf
                        <input type="file" name="file"
                            class="border border-gray-300 rounded-l px-4 py-2 text-gray-600 w-full" required="required">
                        <button type="submit"
                            class="border border-gray-300 hover:bg-cyan-600 hover:text-white text-gray-600 px-2 py-2 rounded-r">
                            Import Data
                        </button>
                    </form>

                    <form action="/klaim/sendApi" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="border border-cyan-600 hover:bg-cyan-600 hover:text-white text-gray-600 px-4 py-2 rounded-md mb-5">
                            Kirim Data ke Penampungan
                        </button>
                    </form>

                    <!-- Tabel Data Klaim LOB -->
                    <table class="border-collapse table-auto w-full text-sm mb-5">
                        <thead>
                            <tr class="bg-white">
                                <th class="border font-medium p-4 text-left text-gray-400">LOB</th>
                                <th class="border font-medium p-4 text-left text-gray-400">Penyebab Klaim</th>
                                <th class="border font-medium p-4 text-left text-gray-400">Jumlah Nasabah</th>
                                <th class="border font-medium p-4 text-left text-gray-400">Beban Klaim</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php
                                $grandTotalTerjamin = 0;
                                $grandTotalBebanKlaim = 0;
                            @endphp

                            @foreach ($groupedKlaims as $lob => $klaims)
                                @php
                                    $subtotalTerjamin = 0;
                                    $subtotalBebanKlaim = 0;
                                @endphp

                                @foreach ($klaims as $klaim)
                                    <tr>
                                        <td class="border border-slate-100 p-4 text-gray-400">{{ $klaim->lob }}</td>
                                        <td class="border border-slate-100 p-4 text-gray-400">
                                            {{ $klaim->penyebab_klaim }}</td>
                                        <td class="border border-slate-100 p-4 text-gray-400">
                                            {{ number_format($klaim->total_terjamin) }}</td>
                                        <td class="border border-slate-100 p-4 text-gray-400">
                                            {{ number_format($klaim->total_beban_klaim, 2, '.') }}</td>
                                    </tr>

                                    @php
                                        $subtotalTerjamin += $klaim->total_terjamin;
                                        $subtotalBebanKlaim += $klaim->total_beban_klaim;
                                    @endphp
                                @endforeach

                                <tr class="bg-cyan-600">
                                    <td colspan="2" class="border border-slate-100 p-4 font-bold text-white">Subtotal
                                        {{ $lob }}</td>
                                    <td class="border border-slate-100 p-4 font-bold text-white">
                                        {{ number_format($subtotalTerjamin) }}</td>
                                    <td class="border border-slate-100 p-4 font-bold text-white">
                                        {{ number_format($subtotalBebanKlaim, 2, '.') }}</td>
                                </tr>

                                @php
                                    $grandTotalTerjamin += $subtotalTerjamin;
                                    $grandTotalBebanKlaim += $subtotalBebanKlaim;
                                @endphp
                            @endforeach

                            <tr class="bg-gray-500">
                                <td colspan="2" class="border-b border-slate-100 p-4 font-bold text-white">Total</td>
                                <td class="border-b border-slate-100 p-4 font-bold text-white">
                                    {{ number_format($grandTotalTerjamin) }}</td>
                                <td class="border-b border-slate-100 p-4 font-bold text-white">
                                    {{ number_format($grandTotalBebanKlaim, 2, '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-app-layout>
