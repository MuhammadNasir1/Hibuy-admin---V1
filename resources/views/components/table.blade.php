<div class="overflow-auto ">
    <table id="datatable" class="dataTable ">
        <thead class="w-full font-semibold text-white bg-customblue">
            <tr class="w-full border-b whitespace-nowrap">
                @foreach ($headers as $header)
                    <th class="text-sm">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="border-b border-gray-500" id="tableBody">
            {{-- <tr class="border-b"> --}}

            </tr>
            {{ $tablebody }}
            {{-- {!! $body !!} --}}

        </tbody>
    </table>

</div>
