<x-admin-layout>
    <x-header-section title="Аудит" />

    <section>
        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Type</th>
                        <th style="width: 400px; word-break: break-word;">New</th>
                        <th style="width: 400px; word-break: break-word;">Old</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($audit as $one)
                    <tr>
                        <td> {{ $one->id }}</td>
                        <td> {{ $one->type }}</td>
                        <td style="width: 400px; word-break: break-word;"> {{ $one->new }}</td>
                        <td style="width: 400px; word-break: break-word;"> {{ $one->old }}</td>
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
