@props(['months', 'selectedMonth', 'users', 'selectedUser', 'staffId'])

<div class="row gtr-uniform">
    <div class="col-3 col-6-medium col-12-small">
        <select name="month" id="month">
            @foreach($months as $month => $value)
                <option
                    value="{{ $month }}"
                    {{ $month == $selectedMonth ? 'selected' : '' }}
                >
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>

    @if(Auth::user()->isAdmin() && empty($staffId))
    <div class="col-3 col-6-medium col-12-small">
        <select name="company_id" id="company_id">
            @foreach ($users as $user)
                @if (!empty($user->yclients_id))
                    <option
                        value="{{ $user->yclients_id }}"
                        {{ $user->yclients_id == $selectedUser ? 'selected' : '' }}
                    >
                        @if (isset($user->name) && $user->name !== '')
                            {{ $user->name }}
                        @else
                            {{ $user->login }}
                        @endif
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    @else
        <input type="hidden" value="{{ $selectedUser }}" name="company_id" />
    @endif

    @if(!empty($staffId))
        <input type="hidden" value="{{ $staffId }}" name="staff_id" />
    @endif

    <div class="col-3 col-6-medium col-12-small">
        <input type="submit" class="fit primary" value="Загрузить" name="load" />
    </div>

    <div class="col-3 col-6-medium col-12-small">
        <input type="submit" class="fit secondary" value="Синхронизировать" name="sync" />
    </div>
</div>
