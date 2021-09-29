<?php
    $title = " Shopper Home Page";
    require '../view/headerInclude.php';
?>
<html>
<body>
    <section id="main" class="clarion-blue">
        <div class="container-fluid">
            <div class ="row">
                <div class="col-2 clarion-white">
                    <div class="sticky-top">
                        <h3>Pick A Category</h3>
                    <?php foreach ($CategoryResults as $CategoryRow) {
                        ?>
                        <div class="container-fluid"><?php echo htmlspecialchars($CategoryRow['DESCRIPTION']) ?></div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
                <div class="col-10">
                    <div class="container-fluid">
                        <h3>Selected Category</h3>
                        <div class="row">
                            <?php foreach ($ProductResults as $ProductRow) {
                                ?>
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 py-2">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <img src="https://dummyimage.com/256x256/000/fff.jpg" class="card-img-top" alt="...">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo htmlspecialchars($ProductRow['NAME']) ?></h4>
                                    </div>
                                    <div class="card-footer d-flex flex-column">
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
        </div>
    </section>
</body>
</html>
<?php
    require '../view/footerInclude.php';
?>