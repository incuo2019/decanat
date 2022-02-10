<section class="container mb-5 w-100">
    <div class="form-group">
        <?php if (isset($certificates)) { ?>
            <a class="btn btn-outline-success mx-2" href="/certificates/new">Заказать справку</a>
        <?php } ?>
        <?php if (isset($characteristics)) { ?>
            <a class="btn btn-outline-success mx-2" href="/characteristics/new">Заказать характеристику</a>
        <?php } ?>
    </div>
</section>