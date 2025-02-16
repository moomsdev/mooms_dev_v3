<?php

/**
 * Theme footer partial.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPEmergeTheme
 */
?>
<!-- footer -->
<footer style="background-image: url('img/bgfoodicon.png');">
    <div class="container-fluid">
        <div class="row p-3-vh">

            <div class="col-md-4 centered">
                <div class="foo-cont">
                    <div class="title">
                        Our Address
                    </div>
                    <div class="detail">
                        <?= getOption('address'); ?>
                    </div>
                    <a class="btn" href="<?= getOption('googlemap'); ?>">View on Map</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="foo-reserv">
                    <div class="title">
                        Make Reservation
                    </div>
                    <div class="detail">
                        <div class="list">
                            <span class="day">Week Days</span>
                            <span class="time">09:00 AM - 21:00 PM</span>
                        </div>
                    </div>
                    <div class="detail">
                        <div class="list">
                            <span class="day">Saturday</span>
                            <span class="time">12:00 AM - 00:00 AM</span>
                        </div>
                    </div>
                    <div class="detail pb-1">
                        <div class="list">
                            <span class="day">Sunday</span>
                            <span class="time">11:00 AM - 22:00 PM</span>
                        </div>
                    </div>
                    <a class="btn-content mt-4" data-toggle="modal"
                        data-target="#resevmodal" href="/contact">
                        <span class="shine"></span>
                        BOOK NOW
                    </a>
                </div>
            </div>

            <div class="col-md-4 centered">
                <div class="foo-cont">
                    <div class="title">
                        Contact Us
                    </div>
                    <div class="detail">
                        Email: <?= getOption('email'); ?><br>
                        Phone: <?= getOption('phone_number'); ?>
                    </div>
                    <a class="btn mb-0" href="#">SEND MESSAGE</a>
                </div>
            </div>

        </div>
    </div>
</footer>
<div class="subfooter">
    <span>Copyright©<?= date('Y'); ?> Hoi An Heart restaurant All Rights Reserved.</span>
</div>
<!-- footer end -->

</div>
<!-- container-wrapper end -->

<!-- sidegalery-->
<div id="bgsidegalery"></div>
<div id="sidegalery" class="init">
    <div class="sidebar">
        <div id="closesidegalery" class="cl-sidebar init">
            <div>
                <span class="ti-close"></span>
            </div>
        </div>

        <h3>Our Menu</h3>
        <div class="s-galery">
            <div id="w-gallery-container"
                class="w-gallery-container side">
                <a class="w-gallery" href="img/gallery/1.jpg">
                    <img src="img/gallery/1.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/2.jpg">
                    <img src="img/gallery/2.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/3.jpg">
                    <img src="img/gallery/3.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/4.jpg">
                    <img src="img/gallery/4.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/5.jpg">
                    <img src="img/gallery/5.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/6.jpg">
                    <img src="img/gallery/6.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/7.jpg">
                    <img src="img/gallery/7.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/8.jpg">
                    <img src="img/gallery/8.jpg" alt=""
                        class="w-gallery-image">
                </a>

                <a class="w-gallery" href="img/gallery/9.jpg">
                    <img src="img/gallery/9.jpg" alt=""
                        class="w-gallery-image">
                </a>
            </div>
        </div>
        <h3>Share us: </h3>
        <div class="s-social">
            <a href="#"><span class="ti-twitter"></span></a>
            <a href="#"><span class="ti-facebook"></span></a>
            <a href="#"><span class="ti-youtube"></span></a>
            <a href="#"><span class="ti-instagram"></span></a>
        </div>

    </div>
</div>
<!-- sidegalery end -->

<!-- modal reservation -->
<div class="modal fade" id="resevmodal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog custommodal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="heading">
                    Online Reservation
                </div>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="modalform">
                    <div class="form-row">
                        <div class="col">
                            <input id="wa_name" type="text" required=""
                                class="form-control" placeholder="Name">
                        </div>
                        <div class="col">
                            <input id="wa_phone" type="text" required=""
                                class="form-control" placeholder="Phone">
                        </div>
                        <div class="col">
                            <input id="wa_email" type="text" required=""
                                class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input id="wa_guest" type="text" required=""
                                class="form-control" placeholder="Number of Guest">
                        </div>
                        <div class="col">
                            <input id="wa_date" type="text" required=""
                                class="form-control" placeholder="09/02/2021">
                        </div>
                        <div class="col">
                            <input id="wa_time" type="text" class="form-control"
                                placeholder="Time">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea rows="10" cols="50" required=""
                            class="form-control" id="message-text"
                            placeholder="Your Message"></textarea>
                        <a id="sendreservation" class="btn-content" href="#">
                            <span class="shine"></span>
                            SEND NOW
                        </a>
                    </div>
                    <div id="text-info"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal reservation -->

<!-- ScrolltoTop -->
<div id="totop" class="init">
    <i class="fa fa-chevron-up"></i>
</div>

<?php wp_footer(); ?>
</body>

</html>