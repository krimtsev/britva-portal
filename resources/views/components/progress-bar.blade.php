@props(['total'])

<div id="progressbar">
    <div></div>
</div>

<style>
    #progressbar {
        border: 1px solid #B0CB1F;
    }

    #progressbar > div {
        background-color: #B0CB1F;
        width: <?php echo $total; ?>%;
        height: 20px;
    }
</style>
