@props(['months', 'selectedMonth', 'partners' => null, 'selectedPartner' => null, 'staffId' => null, 'isDashboard' => null])

<div style="display: flex; gap: 1em;">
    <div style="min-width: 13em">
        <select name="month" id="month" data-id="analytics-months">
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

    @if(!empty($partners) && !empty($selectedPartner))
        @if((Auth::user()->isSysAdmin() || Auth::user()->isAdmin()) && empty($staffId))
            <div style="min-width: 18em">
                <select name="company_id" id="company_id" data-id="analytics-users">
                    @foreach ($partners as $partner)
                        @if (!empty($partner->yclients_id))
                            <option
                                value="{{ $partner->yclients_id }}"
                                {{ $partner->yclients_id == $selectedPartner ? 'selected' : '' }}
                            >
                                {{ $partner->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" value="{{ $selectedPartner }}" name="company_id" />
        @endif
    @endif

    @if(!empty($staffId))
        <input type="hidden" value="{{ $staffId }}" name="staff_id" />
    @endif

    <div>
        <input type="submit" class="primary" value="Загрузить" name="load" data-id="analytics-load" />
    </div>

    @if($isDashboard)
        <div>
            <button type="submit" class="primary icon solid fa-sync button-icon-fix"  value="Синхронизировать" name="sync" data-id="analytics-sync" />
        </div>
    @endif
</div>

<script src="{{ asset('assets/js/reload.js') }}"></script>
