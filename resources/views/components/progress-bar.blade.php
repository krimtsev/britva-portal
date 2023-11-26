@props(["total"])

<?php $id = Str::random();  ?>

<div id="progressbar" data-id="{{ $id }}">
    <div></div>
</div>

<style>
    #progressbar[data-id={{ $id }}] {
        border: 1px solid #B0CB1F;
    }

    #progressbar[data-id={{ $id }}] > div {
        background-color: #B0CB1F;
        width: <?php echo $total; ?>%;
        height: 20px;
    }
</style>
