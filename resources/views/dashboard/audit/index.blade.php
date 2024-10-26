<x-admin-layout>
    <x-header-section title="Аудит" />

    <section>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Type</th>
                        <th>New</th>
                        <th>Old</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audit as $one)
                    <tr>
                        <td> {{ $one->id }}</td>
                        <td> {{ $one->type }}</td>
                        <td> {{ $one->new }}</td>
                        <td> {{ $one->old }}</td>
                        <td> {{ $one->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $audit->links() }}
    </div>
</x-admin-layout>
