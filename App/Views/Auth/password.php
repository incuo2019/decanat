<?php

use App\System\Auth;

$password = !empty(Auth::get()->password);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1">
            <div class="p-md-5 mb-4 border rounded-3 text-center">
                <div class="container py-5">
                    <form class="text-center was-validated" method="post" action="/auth/password/">
                        <h1 class="display-5 my-5"><?php echo ($password) ? 'Введите пароль' : 'Придумайте пароль'; ?></h1>
                        <div class="form-group mt-5">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Пароль" required minlength="8">
                        </div>
                        <?php if (!$password) { ?>
                            <div class="form-group mt-5">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Подтвердите пароль" required minlength="8">
                            </div>
                        <?php } ?>
                        <div class="form-group my-3">
                            <button class="btn btn-primary" type="submit"><?php echo ($password) ? 'Авторизоваться' : 'Зарегистрироваться'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>