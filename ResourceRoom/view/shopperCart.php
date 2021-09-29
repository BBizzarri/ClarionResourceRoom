<?php
    $title = " Shopper Shopping Cart Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section id="main" class="clarion-blue">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-2 clarion-white">
                    <div class="sticky-top" style="margin-top: 15px">
                        <h4>Finalize your Order!</h4>
                        <div class="sticky-top" style="margin-top: 50px; alignment: center">
                            <h6>Comments</h6>
                        </div>
                        <form method="post" class="d-flex flex-column">
                            <div>
                                <textarea class="sticky-top" name="Comments" rows="10" cols="25" id="Comments" style="font-family:sans-serif">Alert us of any allergies or special contact instructions here before you submit your order!</textarea>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-10">
                    <div class="container-fluid">
                        <div class="row sticky-top">
                            <div class="text-left clarion-white" style="margin-left: 25px">
                                <div style="margin-block: 15px">
                                    <h2>Your Shopping Cart<?php ?></h2>
                                </div>
                            </div>
                            <div class="text-right clarion-white" style="margin-left: 650px">
                                <div class="sticky-top" style="margin-block: 15px">
                                    <h4>Total Items in cart: 0<?php ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            for ($x = 0; $x <= 11; $x++) {
                                ?>
                                <div class = "col-sm-6 col-md-4 col-lg-3 col-xl-2 py-2">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <img src="https://dummyimage.com/256x256/000/fff.jpg" class="card-img-top" alt="...">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">Title</h4>
                                            <p class="card-text">Description</p>
                                        </div>
                                        <div class="card-footer d-flex flex-column">
                                            <input type="number" id="quantity" name="quantity" min="1" max="5">
                                            <a href="#" class="btn btn-primary" style="margin-top: 5px">Remove Item</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="row" style="float: right">
                            <a href ="#" class="btn btn-primary" style="background-color: white; border-color: #97824A; border-width: 2px; color: #003366; width: 150px; height: 40px; margin-top: 25px; margin-right: 15px">Submit Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>