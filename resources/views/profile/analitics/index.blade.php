<x-profile-layout>
    <x-header-section title="Аналитика" />

    <section>

        <div>
            <form action="{{ route('p.analytics.show') }}" method="post">
                @csrf

                <div class="row gtr-uniform">
                    <div class="col-4">
                        <select name="month" id="month">
                            <option value="">Выберите месяц</option>
                            @foreach ($months as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-4">
                        <input type="submit" value="Создать отчет" class="primary">
                    </div>
                </div>
            </form>
        </div>

    </section>

</x-profile-layout>
