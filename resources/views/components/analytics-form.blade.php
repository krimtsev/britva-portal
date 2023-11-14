<div class="row gtr-uniform">
    <div class="col-3">
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

    <?php if(Auth::user()->isAdmin()): ?>
    <div class="col-3">
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
    <?php endif; ?>

    <div class="col-3">
        <input type="submit" class="fit primary" value="Загрузить" name="load" />
    </div>

    <div class="col-3">
        <input type="submit" class="fit secondary" value="Синхронизировать" name="sync" />
    </div>
</div>
