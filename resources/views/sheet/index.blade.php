<x-main-layout>
    <x-header-section title="{{$sheet->title}}" />

    <section>
        <div>В данной таблице представлена информация по отгрузке (куда и какой пластик поехал). Не пытайтесь искать здесь сертификаты проданные через сайт. 
		<br />Если не получается найти обычный сертификат, пишите сразу <a target="_blank" href="https://wa.me/79994845317">Диме</a>, он посмотрит где продавался по Yclients.
		<br />Если не удается оплатить визит по сертификату с сайта, то пишите сразу <a target="_blank" href="https://wa.me/79652914902">Артему</a>.
		</div>
		<br />
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
    </section>
</x-main-layout>

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


