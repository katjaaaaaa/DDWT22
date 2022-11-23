<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT22/week1/css/main.css">

        <title><?= $page_title ?></title>
    </head>
    <body>
        <!-- Menu -->
        <?= $navigation ?>

        <!-- Content -->
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp;</div>
            <?= $breadcrumbs ?>

            <div class="row">

                <!-- Left column -->
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg['message'])){echo $error_msg['message'];} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <form action="<?=$form_action?>" method="POST">
                        <div class="form-group row" >
                            <label for="exampleInputName" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value ="<?php if (isset($series_info)){echo $series_info['name'];} ?>" id="exampleInputName" name="s_name" placeholder="Enter the name of the series">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputCreator" class="col-sm-2 col-form-label">Creators</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value ="<?php if (isset($series_info)){echo $series_info['creator'];} ?>" id="exampleInputCreator" name="creators" placeholder="Enter the names of creators">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputSeasons" class="col-sm-2 col-form-label">Seasons</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" value ="<?php if (isset($series_info)){echo $series_info['seasons'];} ?>" id="exampleInputSeasons" name="num_seasons" placeholder="Enter the number of seasons">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="exampleInputAbstract" class="col-sm-2 col-form-label">Abstract</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="exampleInputAbstract" name="s_abstract" placeholder="Enter the abstract of the series"><?php if (isset($series_info)){echo $series_info['abstract'];} ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <input type="hidden" value="<?php if (isset($series_info)){echo $series_info['id'];} ?>" name="book_id">
                                <button type="submit" class="btn btn-primary"><?= $submit_btn?></button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right column -->
                <div class="col-md-4">

                    <?php include $right_column ?>

                </div>

            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js" integrity="sha512-/DXTXr6nQodMUiq+IUJYCt2PPOUjrHJ9wFrqpJ3XkgPNOZVfMok7cRw6CSxyCQxXn6ozlESsSh1/sMCTF1rL/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js" integrity="sha512-ubuT8Z88WxezgSqf3RLuNi5lmjstiJcyezx34yIU2gAHonIi27Na7atqzUZCOoY4CExaoFumzOsFQ2Ch+I/HCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>
</html>
