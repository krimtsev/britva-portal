<x-admin-layout>
    <x-header-section title="Задачи" />

    <section>
        <div class="mb-2">
            Задачи добавлены за период {{ $date["start_date"] }} - {{ $date["end_date"] }}
        </div>
        <div>
            <div><b>Партнеры:</b></div>
            <ol>
                @foreach($partnerNames as $name)
                    <li> {{ $name }} </li>
                @endforeach
            </ol>
        </div>
    </section>
</x-admin-layout>
