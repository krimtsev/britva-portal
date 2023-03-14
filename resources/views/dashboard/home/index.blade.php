<x-dashboard-layout>
    <x-header-section title="Главная" />
    <section>
        <div class="row">
            <div class="col-4 col-12-medium">
                <div class="box text-center ">
                    <h3>Посты</h3>
                    <p><b>{{ $counts->post }}</b></p>
                </div>
            </div>
            <div class="col-4 col-12-medium">
                <div class="box text-center ">
                    <h3>Страницы</h3>
                    <p><b>{{ $counts->page }}</b></p>
                </div>
            </div>
            <div class="col-4 col-12-medium">
                <div class="box text-center ">
                    <h3>Пользователи</h3>
                    <p><b>{{ $counts->user }}</b></p>
                </div>
            </div>
            <div class="col-4 col-12-medium">
                <div class="box text-center ">
                    <h3>Google Sheets</h3>
                    <p><b>{{ $counts->sheet }}</b></p>
                </div>
            </div>
            <div class="col-4 col-12-medium">
                <div class="box text-center ">
                    <h3>Дайджесты</h3>
                    <p><b>{{ $counts->digest }}</b></p>
                </div>
            </div>
        </div>
    </section>
</x-dashboard-layout>
