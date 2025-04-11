<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">

   <h1 class="heading">Shop Components</h1>

   <div class="box-container">

    <div class="box">
        <div class="thumb">
              <img src="images/Arduino starter kit.jpg" alt="">
        </div>
        <h3 class="title">Arduino Starter Kit</h3>
        <a href="https://www.lazada.sg/products/new-arrival-upgraded-version-arduino-uno-r3-learning-suite-raid-learning-rfid-starter-kit-i2406232179-s14157627164.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Aarduino%252Bstarter%252Bkit%253Bnid%253A2406232179%253Bsrc%253ALazadaMainSrp%253Brn%253A537021f738376689b36f8c29e5620955%253Bregion%253Asg%253Bsku%253A2406232179_SGAMZ%253Bprice%253A31.9%253Bclient%253Adesktop%253Bsupplier_id%253A1860%253Bbiz_source%253Ahttps%253A%252F%252Fwww.lazada.sg%252F%253Bslot%253A2%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10000489%253Bitem_id%253A2406232179%253Bsku_id%253A14157627164%253Bshop_id%253A73818%253BtemplateInfo%253A116807_A0%25231103_B_L%2523-1_A3%2523107878_C_D_E%2523&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Singapore&price=31.9&priceCompare=skuId%3A14157627164%3Bsource%3Alazada-search-voucher%3Bsn%3A537021f738376689b36f8c29e5620955%3BunionTrace%3Aa3b5c79d17306400467426180e%3BoriginPrice%3A3190%3BvoucherPrice%3A3190%3BdisplayPrice%3A3190%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1730640047213&ratingscore=5.0&request_id=537021f738376689b36f8c29e5620955&review=14&sale=82&search=1&source=search&spm=a2o42.searchlist.list.2&stock=1" class="inline-btn">Buy now</a>
     </div>
 
    <div class="box">
         <div class="thumb">
            <a href="ArduinoUnoInfo.html">
                <img src="images/thumb-1.jpg" alt="">
            </a>
        </div>
        
         <h3 class="title">Arduino UNO</h3>
         <a href="https://www.lazada.sg/products/uno-r3-development-board-atmega328p-ch340-atega16u2-compatible-for-arduino-with-cable-r3r4-uno-proto-shield-expansion-board-i2455457313-s14588965946.html?" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <a href="ResistorInfo.html">
               <img src="images/thumb-2.png" alt="">
           </a>
         </div>
         <h3 class="title">Resistor</h3>
         <a href="https://www.lazada.sg/products/1000-pcs-05-watt-resistor-assortment-set-diy-carbon-film-resistor-1-ohm-10m-ohm-12w-colored-ring-resistance-assorted-package-i1634234354.html?spm=a2o42.searchlist.list.1.719d3820tnpMF0" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/thumb-3.png" alt=""> 
         </div>
         <h3 class="title">Breadboard</h3>
         <a href="https://www.lazada.sg/products/solderless-breadboard-830-400-170-tie-point-i2450457483.html?spm=a2o42.searchlist.list.7.6c1942daK0u5y1" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/thumb-4.jpg" alt="">
         </div>
         <h3 class="title">Wires</h3>
         <a href="https://www.lazada.sg/products/dupont-line-10cm20cm30cm-male-to-malefemale-to-male-female-to-female-jumper-wire-dupont-cable-for-arduino-diy-kit-i2828476228.html?spm=a2o42.searchlist.list.1.4d1e193f1ZntLz" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/thumb-5.jpg" alt="">
         </div>
         <h3 class="title">Capacitors</h3>
         <a href="https://www.lazada.sg/products/electrolytic-capacitor-63v-16v-25v-35v-50v-63v-200v-400v-450v-10uf-22uf-47uf-100uf-220uf-330uf-470uf-1000uf-01uf-022uf-i2704357313.html?spm=a2o42.searchlist.list.1.477d5e99YOksVi" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/thumb-6.jpg" alt="">
         </div>
         <h3 class="title">Liquid-Crystal Display (LCD)</h3>
         <a href="https://www.lazada.sg/products/lcd1602-1602-lcd-module-blue-yellow-green-screen-16x2-character-lcd-display-pcf8574t-pcf8574-iic-i2c-interface-5v-for-arduino-i2455452311.html?spm=a2o42.searchlist.list.5.4146ed45kUHgo2" class="inline-btn">Buy now</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/thumb-7.jpg" alt="">
         </div>
         <h3 class="title">LED Lights</h3>
         <a href="https://www.lazada.sg/products/200-pcs-3mm-led-diode-light-assorted-kit-diy-diode-set-3mm-red-green-blue-yellow-white-led-diode-for-arduino-i1544370694.html?spm=a2o42.searchlist.list.1.5e843b3fw2oeqH" class="inline-btn">Buy now</a>
      </div>


   </div>

</section>












<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>