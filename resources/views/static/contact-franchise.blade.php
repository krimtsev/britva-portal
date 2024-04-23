<x-main-layout>
    <x-header-section title="Контакты владельцев франшиз" />
    <section>
		<table id="table-franchise" class="table">
			<col width="30%" valign="top">
			<col width="45%" valign="top">

			<thead>
				<tr>
					<th>Филиал</th>
					<th>Контакты партнеров и их руководителей</th>
				</tr>
			</thead>
            @foreach ($partners as $partner)
                <tr>
                    <td>{{ $partner["name"] }}</td>
                    <td>
                        @foreach ($partner["telnums"] as $telnum)
                            <a href="https://wa.me/{{ $telnum["number"] }}">+{{ $telnum["number"] }}</a>
                            @if($telnum["name"])
                                {{ $telnum["name"]  }}
							</br>
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
		</table>

    </section>
</x-main-layout>

<script src="{{ asset('assets/js/jquery.dataTables.min_russian.js') }}"></script>

<script>
    $(document).ready( function () {

        $('#table-franchise').DataTable( {
            paging: false
        });
    } );
</script>

