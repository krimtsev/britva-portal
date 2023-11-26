<x-admin-layout>
    <x-header-section title="Просмотр Google Sheet" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.sheet.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="box">
            <div id="table-sheet-loading"> Таблица загружается ...</div>
            <div class="table-wrapper">
                <table class="alt hidden" id="table-sheet">
                    @if(!empty($sheet->table_header))
                        <thead>
                            <tr>
                                @foreach($sheet->table_header as $value)
                                    <th>
                                        {{ $value }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @foreach($sheet->table as $key => $values)
                            @if(!empty($sheet->table_header) && $key === 0)
                                @continue
                            @endif
                            <tr>
                                @foreach ($values as $key => $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin-layout>

<script src="{{ asset('assets/js/jquery.dataTables.min_russian.js') }}"></script>

<script>
    $(document).ready( function () {
        $('#table-sheet').removeClass('hidden');
        $('#table-sheet-loading').addClass('hidden');

        $('#table-sheet').DataTable( {
            lengthMenu: [[50, 100, -1], [50, 100, "Все"]],
            paging: true
        });
    } );
</script>
