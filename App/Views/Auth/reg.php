<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6 offset-xl-3 col-lg-8 offset-lg-2 col-md-10 offset-md-1">
            <div class="p-md-5 mb-4 border rounded-3 text-center">
                <div class="container py-5">
                    <form class="text-center was-validated" method="post">
                        <h1 class="display-5 my-5">Регистрация</h1>
                        <div class="form-group mt-5">
                            <input type="text" class="form-control" name="login" id="login" placeholder="Номер телефона или номер зачётной книжки" required minlength="6">
                        </div>

                        <div class="form-check mt-3 text-start">
                            <input class="form-check-input" type="checkbox" value="1" name="personal_data" id="personal_data" required checked>
                            <label class="form-check-label" for="personal_data">
                                Я согласен на <a href="/PersonalData/Consent">обработку персональных данных</a>
                            </label>
                        </div>
                        <div class="form-group my-3">
                            <button class="btn btn-primary" type="submit">Зарегистрироваться</button>
                        </div>
                        <div class="form-group my-5">
                            <a href="/auth/login/" class="text-muted">Авторизация</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>