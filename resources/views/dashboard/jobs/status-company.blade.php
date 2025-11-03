<x-admin-layout>
    <x-header-section title="Cтатистика аналитики" />

    <section>
        <div class="table-wrapper" style="font-size: 0.8em;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Имя</th>
                        @foreach ($months as $month)
                            <th class="text-center"> {{ $month }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach ($table as $one)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $one["name"] }}</td>
                        @foreach ($one["dates"] as $date)
                            <td class="text-center">
                                @if ($date)
                                    <i class="fa fa-check color-success"></i>
                                @else
                                    <i class="fa fa-ban color-danger"></i>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </section>
</x-admin-layout>
