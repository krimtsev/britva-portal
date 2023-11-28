<div class="flex flex-col w-full">
    <div data-id="header">
        {{ $header }}
    </div>

    <div data-id="content">
        {{ $slot }}
    </div>

    <div class="flex justify-content-center pa-8" data-id="loading">
        <div class="hidden"><i class="fas fa-sync fa-spin"></i>&nbsp;&nbsp;Данные загружаются... </div>
    </div>
</div>

