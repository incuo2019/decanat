<section class="my-5">
    <div class="container">
        <div class="py-3 px-5 bg-light border rounded-3">
            <h2 class="mb-5"><?php echo $title; ?></h2>
            <?php if (!empty($templates)) { ?>
                <?php foreach ($templates as $template) { ?>
                    <div class="form-group my-4">
                        <a class='btn btn-outline-secondary' href="/files/static/<?php echo $template->link ?? ''; ?>"><?php echo $template->name ?? ''; ?>
                            <span class="ms-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                </svg>
                            </span>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>