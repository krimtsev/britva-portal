<x-admin-layout>
    <x-header-section title="Добавить в команду" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.teams.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
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
                action="{{ route('d.teams.store') }}"
                enctype="multipart/form-data"
            >
                @csrf
                <div class="row gtr-uniform">

					<div class="col-6">
						<div class="col-12 mb-2">
							<input
								id="name"
								type="text"
								name="name"
								:value="old('name')"
								placeholder="Имя"
							/>
						</div>

						<div class="col-12 mb-2">
							<select name="partner_id" id="partner_id">
								<option value="" disabled selected="selected"> Выберите партнера </option>
								@foreach($partners as $id => $name)
									<option value="{{ $id }}">
										{{ $name }}
									</option>
								@endforeach
							</select>
						</div>

						<div class="col-12 mb-2">
							<select name="role_id" id="role_id">
								<option value="" disabled selected="selected"> Выберите специалзиацию </option>
								@foreach($rolesList as $key => $value)
									<option value="{{ $key }}">
										{{ $value['name'] }}
									</option>
								@endforeach
							</select>
						</div>

						<div class="col-12 mb-2">
							<textarea
								id="description"
								name="description"
								placeholder="Описание"
								rows="8"
							>{{ old('description') }}</textarea>
						</div>
                    </div>
					
                    <div class="col-6">
						<div class="col-12 mb-2">
							<img
								id="image"
								src="{{ asset('assets/teams/default.jpeg') }}"
								alt="photo"
								style="max-width: 400px; max-height: 400px"
							/>

							<input
								type="file"
								name="photo"
								:value="old(photo)"
								placeholder="Фото"
								accept="image/png, image/jpg, image/jpeg"
								onchange="loadFileEvent(event)"
								style="max-width: 400px; max-height: 400px"
							/>
						</div>
                    </div>
					
				<div class="col-12 mb-2">
					<button type="submit" class="primary ">Добавить</button>
				</div>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>

<script>
    $(document).ready(function () {
        $("#form").on('submit', function (e) {
            e.preventDefault();
            CreateTeams.call(this)
        });
    });
</script>

