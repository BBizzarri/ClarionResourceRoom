<?php
    $title = "Home Page";
    require '../view/headerInclude.php';
?>
    <section id="main">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-2">
                    <h3>Catagory Box</h3>
                </div>
                <div class="col-10">
                    <div class="card-columns">
                    <?php
                    for ($x = 0; $x <= 10; $x++) {
                         ?>
                        <div class="container-fluid">
                            <div class="card flex-row flex-wrap">
                                <div class="card-header">
                                    <img src="https://dummyimage.com/256x256/000/fff.jpg" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Title</h4>
                                    <p class="card-text">Description</p>
                                </div>
                                <div class="card-block text-center">
                                    <input type="number" id="quantity" name="quantity" min="1" max="5">
                                    <a href="#" class="btn btn-primary">Add To Cart</a>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
    require '../view/footerInclude.php';
?>