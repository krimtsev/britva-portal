<x-admin-layout>
    <x-header-section title="Добавить в команду" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('p.teams.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <!-- Validation Errors -->
        <div id="auth-validation-errors" style="display: none;">
            <div class="font-medium text-red-600">
                Упс! Что-то пошло не так.
            </div>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600"></ul>
        </div>

        <form
            id="form"
            method="POST"
            action="{{ route('p.teams.update', $team->id) }}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="teams-wrapper">
                    <div class="row gtr-uniform">
					
						<div class="col-6">
							<div class="col-12 mb-2">
								<input
									id="name"
									type="text"
									name="name"
									value="{{ $team->name }}"
									placeholder=""
								/>
							</div>

							<div class="col-12 mb-2">
								<select name="role_id" id="role_id">
									<option value="" disabled selected> Градация </option>
									@foreach($rolesList as $key => $value)
										<option {{ $key == $team->role_id ? 'selected="selected"' : '' }} value="{{ $key }}">
											{{ $value['name'] }}
										</option>
									@endforeach
								</select>
							</div>

							<div class="col-12 mb-2">
								<textarea
									id="description"
									name="description"
									placeholder=""
									rows="10"
								>{{ $team->description }}</textarea>
							</div>
						</div>
						
						<div class="col-6">
							<div class="col-12 mb-2">
								<img
									id="image"
									src="{{ $team->photo ? asset('storage/' . $team->photo) : asset('assets/teams/default.jpeg') }}"
									alt="photo"
									style="max-width: 350px; max-height: 350px"
								/>

								<input
									type="file"
									name="photo"
									:value="old(photo)"
									placeholder="Фото"
									accept="image/png, image/jpg, image/jpeg"
									onchange="loadFileEvent(event)"
									style="max-width: 350px; max-height: 350px"
								/>
							</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="float: left">
                <input
                    type="submit"
                    value="Сохранить"
                    class="primary"
                />
            </div>
        </form>

		<div style="float: right">
			<form action="{{ route('p.teams.delete', $team->id) }}" method="post">
				@csrf
				@method('delete')

				<button type="submit" class="danger"> Удалить </button>
			</form>
		</div>
    </section>
</x-admin-layout>

<script>
    $(document).ready(function () {
        $("#form").on('submit', function (e) {
            e.preventDefault();
            UpdateTeams.call(this)
        });
    });
</script>
