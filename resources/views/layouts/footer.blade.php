<footer id="footer">
    <div class="container">
        <section>
            <article class="first">
                <h3 style="text-align: center;">Contact</h3>
                <ul>
                    <li class="address"><a href="#">Helwan<br>Egypt, MI 48226</a></li>
                    <li class="mail"><a href="#">fcih@helwan.edu.eg</a></li>
                    <li class="phone last"><a href="#">(+20) 110930092</a></li>
                </ul>
            </article>
            <article class="second">
                <h3  style="text-align: center;">Forum topics</h3>
                <ul>
                    <li><a href="#">IBM cloud computing training.</a></li>
                    <li><a href="#">SAP sessions in Hall 7.</a></li>
                    <li><a href="#">Fun day for Level 1.</a></li>
                    <li class="last"><a href="#">Certification for students.</a></li>
                </ul>
            </article>
            <article class="third">
                <h3  style="text-align: center;">Social media</h3>
                <p>You can follow us in the social media.</p>
                <ul class="col-xs-12">
                    <li class="facebook col-xs-12" style="float: none;"><a href="#" style="width: 80%; margin: auto;">Facebook</a></li>
                    <li class="google-plus col-xs-12" style="float: none;"><a href="#" style="width: 80%; margin: auto;">Google+</a></li>
                    <li class="twitter col-xs-12" style="float: none;"><a href="#" style="width: 80%; margin: auto;">Twitter</a></li>
                </ul>
            </article>
            <article class="col-lg-4">
                <h3  style="text-align: center;">Newsletter</h3>
                <p>Subscribe now to receive all our news.</p>
                <form action="#">
                    <input placeholder="Email address..." type="text">
                    <button type="submit">Subscribe</button>
                </form>
                <ul>
                    <li><a href="#"></a></li>
                </ul>
            </article>
        </section>
        <p class="copy">Copyright 2018 FCIH Helwan University.</p>
    </div>
    <!-- / container -->
</footer>
<!-- / footer -->

<div id="fancy">
    <h2>Request information</h2>
    <form action="#">
        <div class="left">
            <fieldset class="mail"><input placeholder="Email address..." type="text"></fieldset>
            <fieldset class="name"><input placeholder="Name..." type="text"></fieldset>
            <fieldset class="subject"><select><option>Choose subject...</option><option>Choose subject...</option><option>Choose subject...</option></select></fieldset>
        </div>
        <div class="right">
            <fieldset class="question"><textarea placeholder="Question..."></textarea></fieldset>
        </div>
        <div class="btn-holder">
            <button class="btn blue" type="submit">Send request</button>
        </div>
    </form>
</div>

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>window.jQuery || document.write("<script src='/js/jquery-1.11.1.min.js'>\x3C/script>")</script>
<script src="{{ asset('js/frontend/plugins.js') }} "></script>
<script src=" {{ asset('/js/frontend/main.js') }}"></script>
<script src=" {{ asset('/js/frontend/admin.js') }}"></script>
<script src=" {{ asset('/js/frontend/ajaxDelete.js') }}"></script>
<script src=" {{ asset('/js/frontend/ajaxSearch.js') }}"></script>
@yield('scripts')
</body>
</html>